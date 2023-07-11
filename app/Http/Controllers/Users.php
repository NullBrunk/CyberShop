<?php

namespace App\Http\Controllers;

use App\Http\Requests\Signup;
use App\Http\Requests\Login;
use App\Http\Requests\UpdateProfile;
use Illuminate\Support\Facades\Storage;
use App\Http\Sql;

use Exception;

session_start();

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
    
    public function show(Login $request){
               
        # If the user is already logged 

        if(isset($_SESSION['logged'])){
            return redirect('/');
        }
        

        $data = Sql::query("
            SELECT * FROM `users` 
            WHERE 
                mail=:mail 
            AND 
                BINARY pass=:pass
        ", [ 
            "mail" => $request["email"],
            "pass" =>  hash("sha512", hash("sha512", $request["pass"]))
        ], "fetch");


        if(!empty($data)){

            $_SESSION['id'] = $data['id'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];

            return redirect(route("cart.initialize"));
        }
        else {

            return redirect(route("login") . "?f");
        }
    }



    /**
     * Signup a user
     *
     * @param Signup $request     The informations to store a new user in the database
     *  
     * @return redirect | view    Redirect / if he is allowed to create the user
     *                            view of /signup if he isn't
     * 
     */
    
    public function store(Signup $request){

        
        try {
            Sql::query("
                INSERT INTO 
                    users (mail, pass) 
                VALUES 
                    (:mail, :pass)
            ", [
                "mail" => $request["email"],
                "pass" => hash("sha512", hash("sha512", $request["pass"]))
            ]);
        }
        catch (Exception $e) {
            return view("login.signup", ["error" => true]);
        }

        return redirect("/login");
    }



    /**
     * Update the profile of a given user if he is allowed to 
     *
     * @param UpdateProfile $req     The request with all the valuable informations 
     *      *  
     * @return redirect              Redirect to the profile page in all the cases
     * 
     */
    
    public function profile(UpdateProfile $req){

        # Check if the hashed given password is the good one

        $verify_user = Sql::query("
            SELECT * FROM users WHERE mail=:mail AND pass=:pass
        ", [
            "mail" => $_SESSION['mail'],
            "pass" => hash("sha512", hash("sha512", $req['oldpass']))
        ]);

        # The user is trying to change his profile information
        # with wrong credentials, abort

        if(empty($verify_user)){
            $_SESSION["notsame"] = true;
            return redirect(route("profile"));
        }

        # The user is authorized     
       
        try {
            Sql::query("
            
                UPDATE users SET 
                    mail=:newmail, 
                    pass=:newpass 
                WHERE 
                    mail=:oldmail 
            ", [
                "newmail" => $req['email'],
                "newpass" => hash("sha512", hash("sha512", $req['newpass'])),
                "oldmail" => $_SESSION['mail'],
            ]);

        }
        catch (Exception $e){
            $_SESSION['nul'] = true;
            return redirect(route("profile"));            
        }


        $_SESSION['mail'] = $req['email'];
        $_SESSION['done'] = true;

        return redirect(route("profile"));
    }



    /**
     * Show the profile page of the current user
     *
     * @return view     The profile page
     * 
     */
    
    public function showProfile(){


        # Get all the selled products of the current user 
        $data = Sql::query("
            SELECT * FROM products WHERE id_user = :id
        ", [
            "id" => $_SESSION["id"]
        ]);         
        
        return view("user.profile", [ "data" => $data ]);
    }



    /**
     * Delete a user if he is allowed to
     *
     * @return redirect    Redirect to the / page
     * 
     */

    public function delete(){

        # Delete images linked to the users products
        
        $imgs = Sql::query(
            "SELECT image FROM products WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );

        foreach($imgs as $img){
            Storage::disk("public") -> delete("product_img/" . $img["image"]);
        }


        # Delete all the user comments

        Sql::query(
            "DELETE FROM comments WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );
            
        
        # Delete comments under user products

        $c = Sql::query(
            "SELECT * FROM products WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );

        foreach($c as $product){
            Sql::query(
                "DELETE FROM comments WHERE id_product=:id",
                [ "id" => $product["id"] ]
            );
        }


        # Delete user products 

        Sql::query(
            "DELETE FROM products WHERE  id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );


        # Delete contacts messages from the user

        Sql::query("
            DELETE FROM contact WHERE id_contactor=:id OR id_contacted=:id",
            [ "id" => $_SESSION["id"] ]
        );


        # Delete the user itself
        # finally ... phew

        Sql::query("
            DELETE FROM users WHERE id=:id",
            [ "id" => $_SESSION["id"] ]
        );

        
        return redirect(route("disconnect"));
    }
}
