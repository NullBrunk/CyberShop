<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Http\Sql;

class Details extends Controller {
    
    /**
     * Get the details of a given product
     *
     * @param int $product_id  The id of the product
     * 
     * @return view     A view with all the details of
     *                  the product, including comments
     *                  technicals details, rating etc. 
     * 
     */

    public function __invoke($product_id){
                    
        # Get the user that sell the product, and all the details
        $data = Sql::query("
            SELECT 
                users.id as uid, products.id as pid, 
                id_user, price, descr, class, mail, 
                image, name 
            FROM products
            
            INNER JOIN users 
            ON 
                users.id=products.id_user 
            WHERE 
                products.id=:id

            ORDER BY products.id DESC
        ", [ "id" => $product_id ] );


        # If this product exists
        if(!empty($data)){

            $data = $data[0];

            session_start();

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

            return view("details", ["data" => $data, "comments" => $comments, "rating" => $rating]);
        }

        else {
            abort(404);
        }       
    }
}
