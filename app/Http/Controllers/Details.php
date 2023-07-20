<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;


use App\Models\Product;
use App\Models\Notif;


class Details extends Controller {
    
    /**
     * Get the details of a given product
     *
     * @param Product $product      The product model
     * @param Notif $notif          The notif model
     * @param int $product_id       The id of the product
     * 
     * @return view                 A view with all the details of
     *                              the product, including comments
     *                              technicals details, rating etc. 
     * 
     */

    public function get_details(Product $product, Notif $notif, $product_id){
                    
        $data = $product
            -> select("users.id as uid", "products.id as pid", "id_user", "price", "descr", "class", "mail", "image", "name")
            -> join('users', 'users.id', '=', 'products.id_user')
            -> where('products.id', '=', $product_id )
            -> get()
            -> toArray();


        if(empty($data)){
            abort(404);
        }
        else {
            $data = $data[0];
        }

        
        # Delete all notifications linked to it
        session_start();

        if(isset($_SESSION["logged"])){

            # If i m the seller of this product
            if($_SESSION["id"] === $data["uid"]){

                # Delete all notifs linked to it
                $notif 
                -> where("id_user", "=", $_SESSION["id"]) 
                -> where("type", "=", "comment")
                -> where("moreinfo", "=", $product_id)
                -> delete();
            }
        }

        # Get all the comments of the product
        $comment_req = Http::get('http://127.0.0.1:8000/api/comments/'. $data['pid']);

        
        # If API returns 404, that means that there is no comments
        if($comment_req -> notFound()){
            $comments = [];
        }
        else {
            $comments = $comment_req -> body();
        }

        # Get the rating of this product
        $rating_req = Http::get('http://127.0.0.1:8000/api/rating/'. $data['pid']);
        

        # If API returns 404, that means that there is no rating
        if($rating_req -> notFound()){
            $rating = null;
        }
        else {
            $rating = json_decode($rating_req -> body(), 1);
        }

        return view("product.details", ["data" => $data, "comments" => $comments, "rating" => $rating]);
    
    }
}
