<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReq;
use App\Http\Requests\UpdateProductReq;

use Illuminate\Support\Facades\Storage;



function verify_if_product_is_from_current_user($pdo, $id){
    $validate = $pdo -> prepare("
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
        WHERE product.id=:id_product
        AND id_user=:id_user
    ");
    $validate -> execute([
        "id_user" => $_SESSION['id'],
        "id_product" => $id 
    ]);
    return $validate -> fetchAll(\PDO::FETCH_ASSOC);
}

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
        Storage::disk("public") -> delete("product_img/" . $data['image']);

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

    public function show_update_form($id){

        include_once __DIR__ . "/../../Database/config.php";

        $data = verify_if_product_is_from_current_user($pdo, $id);
        if(!$data){
            return abort(403);
        }

        return view("updateform", ["data" => $data[0]]);
    }

    public function update($id, UpdateProductReq $req){

        include_once __DIR__ . "/../../Database/config.php";
        
        $data = verify_if_product_is_from_current_user($pdo, $id);

        if(!$data){
            return abort(403);
        }

        if(!in_array($req["category"], [ 
            "filter-laptop", 
            "filter-dresses",
            "filter-gaming",
            "filter-health",
            "filter-beauty"
        ])){
            return abort(403);
        }

        $update_product = $pdo -> prepare("
            UPDATE product 
            SET
                `name`=:name, 
                `price`=:price, 
                `class`=:class, 
                `descr`=:descr
            WHERE
                id=:product_id
            ");
            
        $update_product -> execute([
            "name" => $req["name"],
            "price" => $req["price"],
            "descr" => $req["description"],
            "class" => $req["category"],
            "product_id" => $id
        ]);

        $_SESSION["done"] = "updated";
        return redirect(route("details", $id));


    }
}