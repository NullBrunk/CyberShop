<?php

namespace App\Http\Controllers;

use App\Http\Requests\Signup;
use App\Http\Requests\Login;
use App\Http\Requests\UpdateProfile;
use Illuminate\Support\Facades\Storage;
use App\Http\Query;

use Exception;

session_start();

class Users extends Controller {

    public function show(Query $sql, Login $request){
               
        if(isset($_SESSION['logged'])){
            return redirect('/');
        }
        
        $data = $sql -> query("
            SELECT * FROM `users` 
            WHERE 
                mail=:mail 
            AND 
                BINARY pass=:pass
        ", [ 
            "mail" => $request["email"],
            "pass" =>  hash("sha512", hash("sha512", $request["pass"]))
        ]);


        if($data){

            $data = $data[0];

            $_SESSION['id'] = $data['id'];
            $_SESSION['admin'] = $data['is_admin'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];

            return redirect(route("cart.initialize"));
        }
        else {

            return redirect(route("login") . "?f");
        }
    }


    public function store(Query $sql, Signup $request){

        
        try {
            $sql -> query("
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


    public function profile(Query $sql, UpdateProfile $req){

        # Check if the hashed given password is the good one

        $verify_user = $sql -> query("
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
            $sql -> query("
            
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


    public function showProfile(Query $sql){


        # Get all the selled products of the current user 
        $data = $sql -> query("
            SELECT * FROM products WHERE id_user = :id
        ", [
            "id" => $_SESSION["id"]
        ]);         
        
        return view("user.profile", [ "data" => $data ]);
    }


    public function delete(Query $sql){


        # Delete images that are linked to the users products
        
        $imgs = $sql -> query(
            "SELECT image FROM products WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );

        foreach($imgs as $img){
            Storage::disk("public") -> delete("product_img/" . $img["image"]);
        }

        # Delete all the user comments

        $sql -> query(
            "DELETE FROM comments WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );
            
        # Delete comments under user products

        $c = $sql -> query(
            "SELECT * FROM products WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );

        foreach($c as $product){
            $sql -> query(
                "DELETE FROM comments WHERE id_product=:id",
                [ "id" => $product["id"] ]
            );
        }

        # Delete user products 

        $sql -> query(
            "DELETE FROM products WHERE  id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );

        # Delete contacts messages from the user

        $sql -> query("
            DELETE FROM contact WHERE id_contactor=:id OR id_contacted=:id",
            [ "id" => $_SESSION["id"] ]
        );


        # Delete the user itself
        # finally ... phew

        $sql -> query("
            DELETE FROM users WHERE id=:id",
            [ "id" => $_SESSION["id"] ]
        );

        return redirect(route("disconnect"));
    }
}
