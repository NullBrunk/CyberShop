<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComments;
use App\Http\Requests\UpdateComment;
use App\Http\Sql;


class Comments extends Controller {

    /**
     * Store a comment in the database
     *
     * @param StoreComments $req  The informations of the comments.
     * 
     * @return redirect  Redirection to the page where the user commented.
     */

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



    /**
     * Get all the comment of a given product
     *
     * @param StoreComments $id  The id of the product.
     * 
     * @return array  A hashmap with all the comments of the product.
     */

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



    /**
     * Delete a comment from the database
     *
     * @param int $article  The id of the commented product
     * @param int $id   The id of the comment
     * 
     * @return redirect  Redirection to the page where the user commented 
     *                   (thanks to the $article variable).
     */

    public function delete($article, $id){

        dd($article);
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



    /**
     * Get a view of an update form to update a given comment
     *
     * @param int $slug     The id of the comment
     * 
     * @return view  A view with a form to edit the comment
     */

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

        // If there is no such comment OR the comment is not from the current user
        if(empty($data)){
            return abort(403);
        }
        else {
            return view("user.updatecomment", [ "data" => $data[0] ]);
        }
    }



    /**
     * Update a comment into the database
     *
     * @param UpdateComment $req     The new informations to put in the comment
     * 
     * @return redirect  Redirection to the location where the comment 
     *                   has been posted.
     */

    public function update(UpdateComment $req){

        // If the user clicked the abort button
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
