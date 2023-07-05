<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComments;
use App\Http\Requests\UpdateComment;
use App\Http\Sql;


class Comments extends Controller {

    public function store(StoreComments $req){
        
        Sql::query("
            INSERT INTO comments
                (`id_product`, `id_user`, `title`, `content`, `writed_at`, `rating`)
            VALUES
                (:id_product, :id_user, :title, :comment, :writed_at, :rating)
        ", [
            "id_product" => $req["id"], 
            "writed_at" => date('Y-m-d H:i:s'),
            "id_user" => $_SESSION['id'],
            "title" => $req['title'],
            "comment" => htmlspecialchars($req["comment"]),
            "rating" => $req["rating"],
        ]);

        $_SESSION['done'] = true;

        return redirect(route("details", $req["id"]));
    }


    public function get($id){
        
        $data = Sql::query("
            SELECT 
                comments.id, rating, id_product,
                content, writed_at, mail, title

            FROM comments
            INNER JOIN users 
            ON 
                users.id=comments.id_user
    
            WHERE 
                id_product=:id
        
            ORDER BY writed_at DESC
        ", [
            "id" => $id
        ]);
        

        if(empty($data)){
            return abort(404);
        }

        return ($data);
    }

    
    public function delete($article, $id){

        Sql::query("
            DELETE FROM comments 
            WHERE 
                id=:id 
            AND 
                id_user=:id_user
        ", [
            "id_user" => $_SESSION["id"],
            "id" => $id
        ]);

        return redirect(route("details", $article));
    }


    public function get_update_form($slug){
        
        $data = Sql::query("
            SELECT * FROM comments 
            WHERE 
                id_user=:id_user
            AND 
                id=:id 
        ", [
            "id_user" => $_SESSION["id"],
            "id" => $slug
        ]);


        if(empty($data)){
            return abort(403);
        }
        else {
            return view("user.updatecomment", [ "data" => $data[0] ]);
        }
    }


    public function update(UpdateComment $req){

        if($req["abort"] === "Abort"){
            return redirect(route("details", $req["id_product"]));
        }

        Sql::query("
            UPDATE comments
            SET 
                `title` = :title , 
                `content` = :comment, 
                `writed_at` = :writed_at, 
                `rating` = :rating

            WHERE
                `id_user` = :id_user          
            AND 
                `id` = :id
        ", [
            "id" => $req["id"], 
            "writed_at" => date('Y-m-d H:i:s'),
            "id_user" => $_SESSION['id'],
            "title" => $req['title'],
            "comment" => htmlspecialchars($req["comment"]),
            "rating" => $req["rating"],
        ]);

        $_SESSION['updated'] = true;

        return redirect(route("details", $req["id_product"]));

    }
}
