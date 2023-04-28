<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignupReq;

class Signup extends Controller
{
    public function signup(SignupReq $request){

        $validated = $request -> validated();
        
        include_once __DIR__ . '/../Database/config.php';

        
        $r = $pdo -> prepare("INSERT INTO `users`(mail, pass) VALUES (:mail, :pass)");
        $r -> execute(array(
        	"mail" => $validated["email"],
        	"pass" => $validated["pass"]
        ));

        return redirect("/login");

    }
}

