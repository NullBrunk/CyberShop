<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignupReq;
use Exception;

class Signup extends Controller
{
    public function __invoke(SignupReq $request){

        $validated = $request -> validated();
        
        include_once __DIR__ . '/../Database/config.php';

        
        $r = $pdo -> prepare("INSERT INTO `users`(mail, pass) VALUES (:mail, :pass)");
        try {
            $r -> execute(array(
        	    "mail" => $validated["email"],
        	    "pass" => $validated["pass"]
            ));
        }
        catch (Exception $e) {
            return view("login.signup", ["error" => true]);
        }

        return redirect("/login");

    }
}

