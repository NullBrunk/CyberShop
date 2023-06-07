<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsReq;


class Comments extends Controller
{
    public function store(CommentsReq $request){
        
        include_once __DIR__ . "/../Database/config.php";
       
        $id_user = $_SESSION['id'];
        $id_product = $request -> input("id");
        $comment = $request -> input("comment");
        
        $add_comment = $pdo -> prepare("
            INSERT INTO comments
                (`id_product`, `id_user`, `content`, `writed_at`)
            VALUES
                (:id_product, :id_user, :comment, :writed_at)
        ");
        $add_comment -> execute([
            "id_product" => $id_product, 
            "writed_at" => date('Y-m-d H:i:s'),
            "id_user" => $id_user,
            "comment" => $comment,
        ]);


        return redirect(route("details", $id_product));
    }

    public function get(){

    }
    
    public function remove(){

    }
    
    public function update(){

    }
    
}
