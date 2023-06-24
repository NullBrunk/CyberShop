<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupReq;
use App\Http\Requests\LoginReq;
use App\Http\Requests\UpdateProfileReq;
use Illuminate\Support\Facades\Storage;

use Exception;


session_start();

class Users extends Controller {

    public function show(LoginReq $request){
        
        $pdo = config("app.pdo");
       
        if(isset($_SESSION['logged'])){
            return redirect('/');
        }
        
        $user_info = $pdo -> prepare("
            SELECT * FROM `users` 
            WHERE 
                mail=:mail AND 
            BINARY pass=:pass
        ");
        $user_info -> execute(array(
            "mail" => $request["email"],
        	"pass" =>  hash("sha512", hash("sha512", $request["pass"]))
        ));

        $data = $user_info -> fetch();

        if($data){

            $_SESSION['id'] = $data['id'];
            $_SESSION['admin'] = $data['is_admin'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];
            $_SESSION['cart'] = [];

            return redirect(route("root"));
        }
        else {
            # Error
            return redirect(route("login") . "?f");
        }
    }


    public function store(SignupReq $request){

        $pdo = config("app.pdo");
        
        $create_user = $pdo -> prepare("
            INSERT INTO 
                `users`(mail, pass) 
                VALUES (:mail, :pass)
        ");

        try {
            $create_user -> execute(array(
                "mail" => $request["email"],
                "pass" => hash("sha512", hash("sha512", $request["pass"]))
            ));
        }
        catch (Exception $e) {
            return view("login.signup", ["error" => true]);
        }

        return redirect("/login");
    }


    public function profile(UpdateProfileReq $req){

        $pdo = config("app.pdo");

        # Check if the hashed given password is the good one

        $verify_user = $pdo -> prepare("
            SELECT * FROM users WHERE mail=:mail AND pass=:pass
        ");
        $verify_user -> execute([
            "mail" => $_SESSION['mail'],
            "pass" => hash("sha512", hash("sha512", $req['oldpass']))
        ]);


        # The user is trying to change his profile information
        # with wrong credentials, abort

        if(empty($verify_user -> fetch())){
            $_SESSION["notsame"] = true;
            return redirect(route("profile"));
        }

        # The user is authorized 

        $update_user = $pdo -> prepare("
            UPDATE users SET 
        
                mail=:newmail, 
                pass=:newpass 
        
            WHERE 
                mail=:oldmail 
        ");
       
        try {

            $update_user -> execute([
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


    public function showProfile(){

        $pdo = config("app.pdo");


        # Get all the selled products of the current user 
        $selling_product = $pdo -> prepare("
            SELECT * FROM products WHERE id_user = :id
        "); 

        $selling_product -> execute([
            "id" => $_SESSION["id"]
        ]);
        
        
        return view("user.profile", [ "data" => $selling_product -> fetchAll(\PDO::FETCH_ASSOC) ]);
    }


    public function delete(){

        $pdo = config("app.pdo");

        # Delete images that are linked to the users products
        
        $imgs = $pdo -> prepare("SELECT image FROM products WHERE id_user=:id");
        $imgs -> execute([
            "id" => $_SESSION["id"]
        ]); 
        $imgs = $imgs -> fetchAll(\PDO::FETCH_ASSOC);

        foreach($imgs as $img){
            Storage::disk("public") -> delete("product_img/" . $img["image"]);
        }


        # Delete all the user comments

        $c = $pdo -> prepare("DELETE FROM comments WHERE id_user=:id");
        $c -> execute([
            "id" => $_SESSION["id"]
        ]); 


        # Delete comments under user products

        $c = $pdo -> prepare("SELECT * FROM product WHERE id_user=:id");
        $c -> execute([
            "id" => $_SESSION["id"]
        ]);   

        foreach($c -> fetchAll(\PDO::FETCH_ASSOC) as $product){
            $delete = $pdo -> prepare("DELETE FROM comments WHERE id_product=:id");
            $delete -> execute(["id" => $product["id"]]);
        }

        # Delete user products 

        $up = $pdo -> prepare("DELETE FROM products WHERE  id_user=:id");
        $up -> execute(["id" => $_SESSION["id"]]);

        # Delete contacts messages from the user

        $c = $pdo -> prepare("
            DELETE FROM contact WHERE id_contactor=:id OR id_contacted=:id"
        );
        $c -> execute(["id" => $_SESSION["id"]]);


        # Delete the user itself
        # finally ... phew

        $user_del = $pdo -> prepare("DELETE FROM users WHERE id=:id");
        $user_del -> execute(["id" => $_SESSION["id"]]); 

        return redirect(route("disconnect"));
    }
}
