<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComments;
use App\Http\Requests\UpdateComment;


use App\Models\Comment;
use App\Models\Notif;


class Comments extends Controller {

    /**
     * Get the id of the latest user comment
     * 
     * @param Comment $comment      the Comment model
     * 
     * @return int                  the id
     * 
     */

    public function getid(Comment $comment){

        return $comment -> where("id_user", "=", $_SESSION["id"]) 
                        -> orderBy("id", "desc") 
                        -> limit(1) 
                        -> get() 
                        -> toArray()[0]["id"];
    }
    


    /**
     * Store a comment in the database.
     *
     * @param StoreComments $req     The informations of the comments.
     * @param Comment $comment       The comment model
     * @param Notif $notif           The notif model
     * @param int $slug              The id of the seller of the product (to notify it)
     * 
     * @return redirect              Redirection to the page where the user 
     *                               commented.
     * 
     */

    public function store(StoreComments $req, Comment $comment, Notif $notif, $slug){
        
        # Store the comment in the database 

        $comment -> id_product = $req["id"];
        $comment -> writed_at = date('Y-m-d H:i:s');
        $comment -> id_user = $_SESSION['id'];
        $comment -> title = $req['title'];
        $comment -> content = htmlspecialchars($req["comment"]);
        $comment -> rating = $req["rating"];

        $comment -> save();


        # Generate a notifications to the seller of the product

        $notif -> id_user = $slug;
        $notif -> icon = "bx bx-detail";
        $notif -> name = "Commented product.";
        $notif -> content = "From " . $_SESSION["mail"] . ".";
        $notif -> link = route("details", $req["id"]) . "#div" . self::getid($comment);
        $notif -> type = "comment";
        $notif -> moreinfo = $req["id"];

        $notif -> save();

        return to_route("details", $req["id"]) -> with('posted', "Your comment has been posted !");
    }



    /**
     * Get all the comment of a given product
     *
     * @param Comment $comment      The comment model  
     * @param int $id               The id of the product.
     * 
     * @return array                A hashmap with all the comments of 
     *                              the product.
     * 
     */

    public function get(Comment $comment, $id){
        
        $data = $comment
                -> select('comments.id', 'rating', 'id_product', 'content', 'writed_at', 'mail', 'title')
                -> join('users', 'users.id', '=', 'comments.id_user')
                -> where('id_product', '=', $id )
                -> orderBy("id", "desc")
                -> get() -> toArray();

        if(empty($data)){
            return abort(404);
        }

        return ($data);
    }



    /**
     * Delete a comment from the database
     *
     * @param Comment $comment      Model binding of the comment threw his id
     * @param int $article          The id of the commented product
     * 
     * @return redirect             Redirection to the page where the user commented 
     *                              (thanks to the $article variable).
     */

    public function delete(Comment $comment, $article){

        if($comment["id_user"] === $_SESSION["id"]){
            $comment -> delete();
        }
        else {
            return abort(403);
        }

        return to_route("details", $article);
    }


    /**
     * Get a view of an update form to update a given comment
     *
     * @param Comment $comment     Model binding of the comment threw his id
     * 
     * @return view         A view with a form to edit the comment
     */

    public function get_update_form(Comment $comment){
        if($comment["id_user"] === $_SESSION["id"]){
            return view("user.updatecomment", [ "data" => $comment-> toArray() ]);
        }
        else {
            return abort(403);
        }
    }


    /**
     * Update a comment into the database
     *
     * @param UpdateComment $req     The new informations to put in the comment
     * @param Comment $comment       The comment model
     * 
     * @return redirect              Redirection to the location where the comment 
     *                               has been posted.
     */

    public function update(UpdateComment $req, Comment $comment){

        # If the user clicked on the abort button
        if($req["abort"] === "Abort"){
            return to_route("details", $req["id_product"]);
        }

        $comment 
            -> where("id_user", "=", $_SESSION['id'])
            -> where("id", "=", $req["id"])
            -> update([ 
                "writed_at" => date('Y-m-d H:i:s'),
                "title" => $req['title'],
                "content" => htmlspecialchars($req["comment"]),
                "rating" => $req["rating"], 
            ]);


        return to_route("details", $req["id_product"]) -> with("updatedcomm", "Your comment has been updated !");
        
    }
}
