<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Details extends Controller
{
    public function __invoke(Request $request){
        
        include_once __DIR__ . '/../Database/config.php';
        
        $id = $request -> input("id");

        if($id){
            $r = $pdo -> prepare("SELECT users.id as uid, product.id as pid, id_user, price, descr, class, mail, name FROM product LEFT JOIN users ON users.id=product.id_user WHERE product.id=:id");
            $r -> execute( [ "id" => $id ] );
            $r = $r -> fetch();
            
            if($r){
                return view("static.details", ["data" => $r]);
            }
            else {
                abort(403);
            }
        }
        else {
            abort(403);
        }
        


    }
}
