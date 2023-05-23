<?php

namespace App\Http\Controllers;

class Details extends Controller
{
    public function __invoke(){
        
        include_once __DIR__ . '/../Database/config.php';
        
        
        $r = $pdo -> prepare("SELECT users.id as uid, product.id as pid, id_user, price, descr, class, mail, name FROM product LEFT JOIN users ON users.id=product.id_user WHERE product.id=:id");
        $r -> execute( [ "id" => $_GET['id'] ] );
        $r = $r -> fetch();

        return view("static.details", ["data" => $r]);

    }
}
