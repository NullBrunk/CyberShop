<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ForgotPassword;
use App\Events\ForgotPasswordEvent;
use App\Http\Requests\ResetPassword;

class ForgotPasswords extends Controller
{
    /**
     * Send a unique link to the given mail to reset his password
     * 
     * @param Request $request     the request with the mail
     */

    public function forgot(Request $request)
    {
        
        $validated = $request -> validate([
            "email" => "required|email",
        ]);

        
        $user = User::where("mail", $validated["email"]) -> first();
        
        if(!$user){
            return back() -> withErrors(["email" => "This email is not associated with any account"]);
        }

        $reset_code = Str::uuid();

        ForgotPassword::create([
            "reset_code" => $reset_code,
            "id_user" => $user -> id,
        ]);


        // Dispatch a ForgotPasswordEvent so that the ForgotPasswordListenner catch it
        ForgotPasswordEvent::dispatch($user -> mail, $reset_code);

        return to_route("auth.login") -> with("reset_sent", true);

    }



    /**
     * Return a view with a form to reset the password if the user provide the good 
     * slug.
     * 
     * @param ForgotPassword $reset     The forgot passwordthrough model binding
     */

    public function reset_form(ForgotPassword $reset) {
        return view("auth.reset_form", [
            "code" => $reset -> reset_code,
        ]);
    }



    /**
     * Reset the password of a user
     * 
     * @param ResetPassword $req        
     */

    public function reset(ResetPassword $req, ForgotPassword $reset) {
        
        $user = User::where("mail", $req -> input("mail")) -> first();

        if(!$user) {
            return back() -> withErrors([
                "mail" => "This email is not associated with any account",
            ]);
        }

        if($user -> id !== $reset -> id_user) {
            return back() -> withErrors([
                "mail" => "The reset code is not associated with your email",
            ]);
        }

        // Change the password of the user
        $user -> pass = hash("sha512", hash("sha512", $req -> input("pass")));
        $user -> save();

        // Delete the reset link in the table
        $reset -> delete();
        
        return to_route("auth.login") -> with("reset", "Your password has been changed successfully.");
    }
}
