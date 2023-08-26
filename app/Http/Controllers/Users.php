<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\SignupEvent;
use Illuminate\Support\Str;
use App\Http\Requests\Login;
use Illuminate\Http\Request;
use App\Http\Requests\Signup;
use App\Models\MailValidation;
use App\Http\Requests\UpdateSettings;

if(!isset($_SESSION)){
    session_start();
}

class Users extends Controller {


    /**
     * Log the user if he gave the good username and password.
     *
     * @param Login $request     The request with the username & password.
     *  
     * @return redirect          redirect to / if he is logged 
     *                           redirect to /login if he isn't.
     * 
     */
    
    public function login(Login $request){
               
        $req = $request -> validated();

        # If the user is already logged 

        if(isset($_SESSION['logged'])){
            return redirect('/');
        }

        $data = User::where([
            [ "mail", "=",  $req["email"]],
            [ "pass", "=", hash("sha512", hash("sha512", $req["pass"])) ],
        ]) -> get() -> toArray();

        if(!empty($data)){

            $data = $data[0];

            if(!$data["verified"] ){
                return to_route("auth.login") -> withErrors([
                    "verify" => "You need to verify your mail address"
                ]) -> onlyInput("email");
            }

            $_SESSION['id'] = $data['id'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];
            session(['mail' => $data['mail']]);
            
            $_SESSION['pass'] = $data['pass'];
            $_SESSION["closed"] = [];


            return to_route("cart.initialize");
        }

        else {

            return to_route("auth.login") -> withErrors([
                "invalid" => "Wrong mail or password !"
            ]) -> onlyInput("email");
        }
    }



    /**
     * Signup a user
     *
     * @param Signup $request     The informations to store a new user in the database
     * 
     * @return redirect           Redirect / when he signed up
     * 
     */
    
    public function store(Signup $request, User $user){

        $req = $request -> validated();
        $checksum = Str::uuid();
        
        // Send verification mail

        $user = User::create([
            "mail" => $req["mail"],
            "pass" => hash("sha512", hash("sha512", $req["pass"])),
        ]);

        MailValidation::create([
            "id_user" => $user -> id,
            "checksum" => $checksum,
        ]);
      

        // Dispatch a SignupEvent so that the SignupListenner catch it
        SignupEvent::dispatch($user -> mail, $checksum);

        return to_route("auth.login") -> with("success", "A confirmation mail have been sent to " . $user -> mail);
    }


    
    /** 
     * Confirm the email of a given user
     * 
     * @param string $checksum         The random string sended to the user
     * 
     */

    public function confirm_mail(string $checksum){

        $data = MailValidation::where("checksum", "=", $checksum) -> get();

        if($data -> first()){

            $info = $data[0];

            User::where("id", "=", $info -> id_user) -> update([
                "verified" => 1
            ]);

            $info -> delete();

            return to_route("auth.login") -> with("success", "Your mail have been confirmed, you can log-in now.");
        }

        return abort(403);
    }



    /**
     * Show the settings form view  
     * 
     * return view
     */

    public function settings_form() {
        
        $user = User::find($_SESSION["id"]) -> first();

        return view("users.settings", [
            "user" => $user,
        ]);
    }



    /** 
     * Update the password of a user if he is allowed to 
     * 
     * @param UpdateSettings $request
     * 
     * @return redirect
    */

    public function settings(UpdateSettings $request) {

        $hashed_new_pass = hash("sha512", hash("sha512", $request -> input("newpass")));
        $user = User::find($_SESSION["id"]);

        if($user -> pass === hash("sha512", hash("sha512", $request -> input("pass")))) {
            $user -> update([
                "pass" => $hashed_new_pass
            ]);
            
            $_SESSION['pass'] = $hashed_new_pass;

            return back() -> with("success", true);
        }
        else {
            return back() -> withErrors(["pass" => "Invalid password"]);
        }
    }



    /**
     * Delete a user if he is allowed to
     *
     * @param Request $request 
     *
     * @return redirect              Redirect to the / page
     * 
     */

    public function delete(Request $request){

        if($request -> input("password")){
            $response = User::where("mail", $_SESSION["mail"]) 
            -> where("pass", 
                hash("sha512", hash("sha512", $request -> input("password")))
            ) -> delete();
            
            if($response === 1){
                session(["deletedaccount" => true]);
                return to_route("logout");
            }
            else {
                return back() -> withErrors(["password_error" => true]);
            }
        }
        return back();
    }
}
