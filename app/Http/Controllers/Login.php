<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginReq;

session_start();


class Login extends Controller
{
    public function login(LoginReq $req){
        
        if(isset($_SESSION['logged'])){
            return redirect('/users');
        }

        $validated = $req -> validated();
    
        include_once __DIR__ . '/../Database/config.php';
        $r = $pdo -> prepare("SELECT * FROM `users` WHERE mail=:mail AND BINARY pass=:pass");
        $r -> execute(array(
            "mail" => $validated["email"],
        	"pass" => $validated["pass"]
        ));
        $r = $r -> fetch();

        if($r){
            $_SESSION['admin'] = $r['is_admin'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $r['mail'];
            $_SESSION['pass'] = $r['pass'];
            return redirect('/users');
        }
        else {
            return redirect('/login');
        }

    }
}
