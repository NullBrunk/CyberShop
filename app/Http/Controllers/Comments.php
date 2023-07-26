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
     * @param StoreComments $request      The informations of the comments.
     * @param Comment $comment            The comment model
     * @param Notif $notif                The notif model
     * @param int $slug                   The id of the seller of the product (to notify it)
     * 
     * @return redirect                   Redirection to the page where the user 
     *                                    commented.
     * 
     */

    public function store(StoreComments $request, Comment $comment, Notif $notif, $slug){
        
        $req = $request -> validated();


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
        $notif -> link = "/details/" . $req["id"] . "/#div" . self::getid($comment);
        $notif -> type = "comment";
        $notif -> moreinfo = $req["id"];

        $notif -> save();


        return to_route("details", $req["id"]) -> with('posted', "Your comment has been posted !");
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
     * @return view                A view with a form to edit the comment
     */

    public function update_form(Comment $comment){

        if($comment["id_user"] === $_SESSION["id"]){
            return view("user.form_comment", [ "data" => $comment -> toArray() ]);
        }
        else {
            return abort(403);
        }
    }


    
    /**
     * Edit a comment if the user is allowed to
     *
     * @param UpdateComment $request     The new informations to put in the comment
     * @param Comment $comment           The comment model
     * 
     * @return redirect                  Redirection to the location where the 
     *                                   comment has been posted.
     */

    public function edit(UpdateComment $request, Comment $comment){

        # If the user clicked on the abort button
        if($request["abort"] === "Abort"){
            return to_route("details", $request["id_product"]);
        }

        $comm = $comment -> findOrFail($request['id']) ;
        
        if($comm -> id_user === $_SESSION["id"]){
            $comm -> update([ 
                "writed_at" => date('Y-m-d H:i:s'),
                "title" => $request['title'],
                "content" => htmlspecialchars($request["comment"]),
                "rating" => $request["rating"], 
            ]);
        }
        else {
            return abort(403);
        }

        return to_route("details", $request["id_product"]) -> with("updatedcomm", "Your comment has been updated !");        
    }
}
