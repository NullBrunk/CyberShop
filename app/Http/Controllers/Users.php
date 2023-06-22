<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupReq;
use App\Http\Requests\LoginReq;
use App\Http\Requests\UpdateProfileReq;
use Illuminate\Support\Facades\Storage;

use Exception;


session_start();

class Users extends Controller
{
    
    /* 
    Check if a user is in the database, 
    
    if he is 
        Put his informations in the SESSION
    else
        Display an error
    */
    

    public function show(LoginReq $request){
        
        include_once __DIR__ . '/../../Database/config.php';
       
        if(isset($_SESSION['logged'])){
            return redirect('/');
        }
    
        
        $user_info = $pdo -> prepare("SELECT * FROM `users` WHERE mail=:mail AND BINARY pass=:pass");
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

            return redirect(route("login") . "?f");
        }

    }


    /* 
    Add a user in the database and redirect to the login page 
    
    if he is already in the DB
        Display an error
    */

    public function store(SignupReq $request){

        include_once __DIR__ . '/../../Database/config.php';
        
        $create_user = $pdo -> prepare("INSERT INTO `users`(mail, pass) VALUES (:mail, :pass)");
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

        include_once __DIR__ . '/../../Database/config.php';

        $verify_user = $pdo -> prepare("
            SELECT * FROM users WHERE mail=:mail AND pass=:pass
        ");
        $verify_user -> execute([
            "mail" => $_SESSION['mail'],
            "pass" => hash("sha512", hash("sha512", $req['oldpass']))
        ]);

        if(empty($verify_user -> fetch())){
            $_SESSION["notsame"] = true;
            return redirect(route("profile"));
        }



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

        include_once __DIR__ . '/../../Database/config.php';


        # get products that the current user is selling 
        $selling_product = $pdo -> prepare("
            SELECT * FROM product WHERE id_user = :id
        "); 

        $selling_product -> execute([
            "id" => $_SESSION["id"]
        ]);
        
        # return the correct view with the informations
        return view("user.profile", [ "data" => $selling_product -> fetchAll(\PDO::FETCH_ASSOC) ]);
    }


    public function delete(){

        include_once __DIR__ . '/../../Database/config.php';


        # Delete images that are linked to the users products
        
        $imgs = $pdo -> prepare("SELECT image FROM product WHERE id_user=:id");
        $imgs -> execute([
            "id" => $_SESSION["id"]
        ]); 
        $imgs = $imgs -> fetchAll(\PDO::FETCH_ASSOC);

        foreach($imgs as $img){
            Storage::disk("public") -> delete("product_img/" . $img["image"]);
        }


        # Delete comments
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

        # Delete user product 
        $up = $pdo -> prepare("DELETE FROM product WHERE  id_user=:id");
        $up -> execute(["id" => $_SESSION["id"]]);

        # Delete contacts messages from the user
        $c = $pdo -> prepare("
            DELETE FROM contact WHERE id_contactor=:id OR id_contacted=:id"
        );
        $c -> execute(["id" => $_SESSION["id"]]);


        # Delete the user itself

        $user_del = $pdo -> prepare("DELETE FROM users WHERE id=:id");
        $user_del -> execute(["id" => $_SESSION["id"]]); 

        return redirect(route("disconnect"));
    
    }
}
