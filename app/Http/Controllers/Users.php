<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\UpdateProfile;
use App\Http\Requests\Signup;
use App\Http\Requests\Login;


use App\Models\Product;
use App\Models\Comment;
use App\Models\Notif;
use App\Models\User;
use App\Models\Cart;

use App\Http\Sql;

use Exception;

session_start();

class Users extends Controller {


    /**
     * Log the user if he gave the good username and password.
     *
     * @param Login $request     The request with the username & password.
     * @param User  $user        The user model
     *  
     * @return redirect          redirect to / if he is logged 
     *                           redirect to /login if he isn't.
     * 
     */
    
    public function show(Login $request, User $user){
               
        # If the user is already logged 

        if(isset($_SESSION['logged'])){
            return redirect('/');
        }

        $data = $user -> where([
            [ "mail", "=",  $request["email"]],
            [ "pass", "=", hash("sha512", hash("sha512", $request["pass"])) ],
        ]) -> get() -> toArray();

        if(!empty($data)){

            $data = $data[0];

            $_SESSION['id'] = $data['id'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];

            return to_route("cart.initialize");
        }
        else {

            return to_route("login") -> withErrors([
                "email" => "Wrong mail or password !"
            ]) -> onlyInput("email");
        }
    }



    /**
     * Signup a user
     *
     * @param Signup $request     The informations to store a new user in the database
     * @param User   $user        The user model 
     * 
     * @return redirect | view    Redirect / if he is allowed to create the user
     *                            view of /signup if he isn't
     * 
     */
    
    public function store(Signup $request, User $user){

        $user -> mail = $request["email"];
        $user -> pass = hash("sha512", hash("sha512", $request["pass"]));
        

        try {
            $user -> save();
        }
        catch(Exception $e){
            return to_route("signup") -> withErrors([
                "email" => "An error has occured"
            ])->withInput($request->input());
        }

        return to_route("login");

    }



    /**
     * Update the profile of a given user if he is allowed to 
     *
     * @param UpdateProfile $req     The request with all the valuable informations 
     * @param User          $user    The user model
     * 
     * @return redirect              Redirect to the profile page in all the cases
     * 
     */
    
    public function profile(UpdateProfile $req, User $user){

        # Check if the hashed given password is the good one

        $verify_user = $user -> where([
            [ "mail", "=",  $_SESSION['mail']],
            [ "pass", "=", hash("sha512", hash("sha512", $req['oldpass'])) ],
        ]) -> get() -> toArray();


        # The user is trying to change his profile information
        # with wrong credentials, abort

        if(empty($verify_user)){
            $_SESSION["notsame"] = true;
            return to_route("profile");
        }

        # The user is authorized     
       
        try {
            
            $user -> where("mail", "=", $_SESSION['mail'])
                  -> update([ 
                    "mail" => $req['email'],
                    "pass" => hash("sha512", hash("sha512", $req['newpass'])), 
                ]);

        }
        catch (Exception $e){
            $_SESSION['nul'] = true;
            return to_route("profile");            
        }


        $_SESSION['mail'] = $req['email'];
        $_SESSION['done'] = true;

        return to_route("profile");
    }



    /**
     * Show the profile page of the current user with
     * all the products that he is selling
     * 
     * @param  Product $user    The product model
     *
     * @return view             The profile page
     * 
     */
    
    public function showProfile(Product $user){


        $data = $user 
            -> where("id_user", "=", $_SESSION["id"]) 
            -> get() 
            -> toArray();

        return view("user.profile", [ "data" => $data ]);
    }



    /**
     * Delete a user if he is allowed to
     * 
     * @param User    $user         The user model
     * @param Product $product      The product model
     * @param Notif   $notif        The notification model
     * @param Cart    $cart         The cart model
     * @param Comment $comment      The comment model
     * 
     *
     * @return redirect             Redirect to the / page
     * 
     */

    public function delete(User $user, Product $product, Notif $notif, Cart $cart, Comment $comment){


        # Delete images linked to the users products

        $imgs = $product -> select("image") 
                 -> where("id_user", "=", $_SESSION["id"])
                 -> get()
                 -> toArray() ;

        foreach($imgs as $img){
            Storage::disk("public") -> delete("product_img/" . $img["image"]);
        }


        # Delete all the user comments
        $comment -> where("id", "=", $_SESSION["id"]) -> delete();

                
        # Delete comments under user products

        $c = Sql::query(
            "SELECT * FROM products WHERE id_user=:id",
            [ "id" => $_SESSION["id"] ]
        );

        foreach($c as $pr){
            
            # Comments under user products
            $comment -> where("id_product", "=", $pr["id"]) -> delete();


            # Delete users products on other carts
            $cart -> where("id_product", "=", $pr["id"]) -> delete();
        }


        # Delete user products 
        $product -> where("id_user", "=", $_SESSION["id"]) -> delete();


        # Delete user notifs
        $notif -> where("id_user", "=", $_SESSION["id"]) -> delete();


        # Delete users cart
        $cart -> where("id_user", "=", $_SESSION["id"]) -> delete();

        # Delete contacts messages from the user

        Sql::query("
            DELETE FROM contact WHERE id_contactor=:id OR id_contacted=:id",
            [ "id" => $_SESSION["id"] ]
        );


        # Delete the user itself
        # finally ... phew

        $user -> where("id", "=", $_SESSION["id"]) -> delete();

        return to_route("disconnect");
    }
}
