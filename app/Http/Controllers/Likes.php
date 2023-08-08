<?php

namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Like;

if(!isset($_SESSION)){
    session_start();
}

class Likes extends Controller
{

    /**
     * Function that returns true if i have liked a product, else false
     *
     * @param Comment $comment          the comment threw model binding 
     *
     * 
     * @return array
     * 
     */

    public function is_liked(Comment $comment){

        if(!isset($_SESSION["logged"])){
            return [ "value" => false ];
        }

        return !empty($comment -> like() -> where("id_user", "=", $_SESSION["id"]) -> get() -> toArray()) ? [ "value" => true ] : [ "value" => false ];
    }



    /**
     * If the user already liked the comment, remove the like, else, 
     * like the comment
     *
     * @param Like $like            the like model
     * @param Comment $comment          the comment id 
     *
     */

    public function toggle(Like $like, Comment $comment){
        
        if(self::is_liked($comment)["value"]){
            $like -> where("id_user", "=", $_SESSION["id"]) -> where("id_comment", "=", $comment -> id) -> delete();
        }   
        else {
            $like -> id_user = $_SESSION["id"];
            $like -> id_comment = $comment -> id;

            $like -> save();
        }     
    }
    


}
