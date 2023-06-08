<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Products extends Controller
{

    // Search a given string in the name of the products using LIKE
    public function search(string $search) : array
    {

        include_once __DIR__ . "/../../Database/config.php";

        $search_product = $pdo -> prepare("SELECT id,image,price,class FROM product WHERE `name` LIKE CONCAT('%', :search, '%')");
        $search_product -> execute([
            "search" => $search
        ]); 

        $data = $search_product -> fetchAll(\PDO::FETCH_ASSOC);
        
        return($data);
    }

    public function addProductToCart(Request $req){
        
        include_once __DIR__ . "/../../Database/config.php";

        $product_id = $req -> input('id');
        if($product_id){
            $to_add = $pdo -> prepare("
                SELECT 
                id,
                name,
                image,
                price 

                FROM product 
                WHERE id=:id
            ");
        
            $to_add -> execute([
                "id" => $product_id
            ]); 
            $data = ($to_add -> fetchAll(\PDO::FETCH_ASSOC))[0];
            
            array_push($_SESSION['cart'], $data); 
            return redirect(route("root"));
        }
        return abort(403);
    }

    public function deleteProductFromCart($id){

        if(isset($_SESSION['cart'][$id])){
            array_splice($_SESSION['cart'], $id, 1);
            return redirect(route("root"));
        }

        return abort(403);
    }
}
