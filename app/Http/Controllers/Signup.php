<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupReq;
use Exception;

class Signup extends Controller
{
    public function __invoke(SignupReq $request){

        include_once __DIR__ . '/../Database/config.php';
        
        $create_user = $pdo -> prepare("INSERT INTO `users`(mail, pass) VALUES (:mail, :pass)");
        try {
            $create_user -> execute(array(
        	    "mail" => $request["email"],
        	    "pass" => $request["pass"]
            ));
        }

        # Account is already created
        catch (Exception $e) {
            return view("login.signup", ["error" => true]);
        }

        return redirect("/login");
    }
}

