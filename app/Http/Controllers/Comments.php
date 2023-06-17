<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsReq;


class Comments extends Controller
{
    public function store(CommentsReq $request){
        
        include_once __DIR__ . "/../../Database/config.php";
       
        $id_user = $_SESSION['id'];
        $id_product = $request -> input("id");
        $comment = $request -> input("comment");
        $rating = $request -> input("rating");
        
        $add_comment = $pdo -> prepare("
            INSERT INTO comments
                (`id_product`, `id_user`, `content`, `writed_at`, `rating`)
            VALUES
                (:id_product, :id_user, :comment, :writed_at, :rating)
        ");
        $add_comment -> execute([
            "id_product" => $id_product, 
            "writed_at" => date('Y-m-d H:i:s'),
            "id_user" => $id_user,
            "comment" => $comment,
            "rating" => $rating,
        ]);

        $_SESSION['done'] = true;
        return redirect(route("details", $id_product));
    }

    public function get($id){
        
        include_once __DIR__ . "/../../Database/config.php";

        $getComments = $pdo -> prepare("SELECT 
        comments.id,
        rating,
        id_product,
        content,
        writed_at,
        mail

        FROM comments
        INNER JOIN  
        users 
        ON 
        users.id=comments.id_user
        
        WHERE id_product=:id
        ORDER BY writed_at DESC
        "
        
    );
        
        $getComments -> execute([
            ":id" => $id
        ]);

        $data = $getComments -> fetchAll(\PDO::FETCH_ASSOC);

        if(empty($data)){
            return abort(404);
        }

        return ($data);
        
    }
    
    public function delete($article, $id){

        include_once __DIR__ . "/../../Database/config.php";

        $delete_comment = $pdo -> prepare("
            DELETE FROM comments 
            WHERE 
                id=:id 
            AND 
                id_user=:id_user
        ");
        
        $delete_comment -> execute([
            "id_user" => $_SESSION["id"],
            "id" => $id
        ]);

        return redirect(route("details", $article));

    }

}
