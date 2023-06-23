<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class Details extends Controller {
    
    public function __invoke($product_id){
        
        include_once __DIR__ . '/../../Database/config.php';
            
        # Get the user and the product details
        $details = $pdo -> prepare("
            SELECT users.id as uid, 
            products.id as pid, 
            id_user, 
            price, 
            descr, 
            class, 
            mail, 
            image,
            name 
            
            FROM products
            
            INNER JOIN users 
            ON users.id=products.id_user 
            WHERE products.id=:id

            ORDER BY products.id DESC
        ");

        $details -> execute( [ "id" => $product_id ] );
        $data = $details -> fetch();


        if($data){
            session_start();

            $response = Http::get('http://127.0.0.1:8000/api/comments/'. $data['pid']);
            
            if($response -> notFound()){
                $comments = [];
            }
            else {
                $comments = $response -> body();
            }


            $rating_req = Http::get('http://127.0.0.1:8000/api/rating/'. $data['pid']);
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
