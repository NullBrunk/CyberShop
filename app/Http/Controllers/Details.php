<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class Details extends Controller {
    
    public function __invoke($product_id){
        
        include_once __DIR__ . '/../../Database/config.php';
            
        # Get the user and the product details
        $details = $pdo -> prepare("
            SELECT users.id as uid, 
            product.id as pid, 
            id_user, 
            price, 
            descr, 
            class, 
            mail, 
            image,
            name 
            
            FROM product 
            
            INNER JOIN users 
            ON users.id=product.id_user 
            WHERE product.id=:id
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

            return view("details", ["data" => $data, "comments" => $comments]);
        }
        else {
            abort(404);
        }       
    }

}
