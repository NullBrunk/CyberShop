<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReq;

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

    public function store(StoreReq $req){      

        include_once __DIR__ . "/../../Database/config.php";

        if(!in_array($req["category"], [ 
            "filter-laptop", 
            "filter-dresses",
            "filter-gaming",
            "filter-health",
            "filter-beauty"
        ])){
            return abort(403);
        }

        $img = $req["product_img"];

        if($img !== null && !$img -> getError()){

            $imgPath = $req["product_img"] -> store("product_img", "public");

            $store_product = $pdo -> prepare("
                INSERT INTO 
                    product(`id_user`, `name`, `price`, `descr`, `class`, `image`)
                VALUES
                    (:id_user, :name, :price, :descr, :class, :image)
            ");
            $store_product -> execute([
                "id_user" => $_SESSION["id"],
                "name" => $req["name"],
                "price" => $req["price"],
                "descr" => $req["description"],
                "class" => $req["category"],
                "image" => substr($imgPath, 12),
            ]);

        }
        else {
            $_SESSION["error"] = true;
            return view("sell");
        }

        $_SESSION["done"] = true;
        return view("sell");

    }
    
    public function delete($id){
        
        include_once __DIR__ . "/../../Database/config.php"; 


        $values = [
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ];


        $select_pr = $pdo -> prepare("SELECT * FROM product WHERE id=:product_id AND id_user=:id_user");
        $select_pr -> execute($values);
        $data = $select_pr -> fetch();

        if(empty($data)){
            return abort(403);
        }
        unlink(__DIR__ . "/../../../public/storage/product_img/" . $data['image'] );


        $del_product = $pdo -> prepare("
            DELETE FROM 
                product 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        ");

        $del_product -> execute($values);

        return redirect("/");
    }
}
