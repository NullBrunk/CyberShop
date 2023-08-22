<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notif;
use App\Models\Comment;
use App\Events\NotificationReceived;
use App\Http\Requests\StoreComments;
use App\Http\Requests\UpdateComment;
use App\Notifications\CommentedProductNotification;


class Comments extends Controller {

    /**
     * Get the id of the latest user comment
     * 
     * @return int                  the id
     * 
     */

    public function getid(){

        return Comment::where("id_user", "=", $_SESSION["id"]) 
                        -> desc() 
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
    
    
    public function store(StoreComments $request, int $slug){
        

        $req = $request -> validated();


        # Store the comment in the database 

        Comment::create([
            "id_product" => $req["id"],
            "writed_at" => date('Y-m-d H:i:s'),
            "id_user" => $_SESSION['id'],
            "title" => $req['title'],
            "content" => htmlspecialchars($req["comment"]),
            "rating" => $req["rating"],
        ]);



        # Generate a notifications to the seller of the product

        $user = User::find($slug);
        $user -> notify(new CommentedProductNotification([
            "content" => "From " . $_SESSION["mail"] . ".",
            "id_product" => $req["id"],
            "link" => "/details/" . $req["id"] . "/#div" . self::getid(),
        ]));


        # Generate an event
        NotificationReceived::dispatch($slug);
        
        return to_route("details", $req["id"]) 
                -> with('posted', "Your comment has been posted !");
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

    public function delete(Comment $comment, int $article){

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
     * 
     * @return redirect                  Redirection to the location where the 
     *                                   comment has been posted.
     */

    public function edit(UpdateComment $request){

        # If the user clicked on the abort button
        if($request["abort"] === "Abort"){
            return to_route("details", $request["id_product"]);
        }

        $comm = Comment::findOrFail($request['id']) ;
        
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

        return to_route("details", $request["id_product"]) 
                -> with("updatedcomm", "Your comment has been updated !");        
    }
}
