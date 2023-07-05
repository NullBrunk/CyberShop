<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReq;
use App\Http\Requests\UpdateProduct;

use Illuminate\Support\Facades\Storage;
use App\Http\Query;


function verify_if_product_is_from_current_user($id){

    $pdo = config("app.pdo");

    $validate = $pdo -> prepare("
        SELECT 
            users.id as uid, products.id as pid, 
            id_user, price, descr, class, mail, image,
            name 
                
        FROM products 
        INNER JOIN users 
        ON 
            users.id=products.id_user 
        WHERE 
            products.id=:id_product
        AND 
            id_user=:id_user
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
    public function search(Query $sql, string $search) : array
    {

        $data = $sql -> query("
            SELECT id,image,price,class 
            FROM products 
            WHERE 
                `name` LIKE CONCAT('%', :search, '%')
        ", [ "search" => $search ]);


        return ($data);
    }

    public function store(Query $sql, StoreReq $req){      

        if(!in_array($req["category"], [ 
            "filter-laptop", 
            "filter-dresses",
            "filter-gaming",
            "filter-food",
            "filter-other"
        ])){
            return abort(403);
        }

        $img = $req["product_img"];

        if($img !== null && !$img -> getError()){

            $imgPath = $req["product_img"] -> store("product_img", "public");

            $sql -> query("
                INSERT INTO 
                    products(`id_user`, `name`, `price`, `descr`, `class`, `image`)
                VALUES
                    (:id_user, :name, :price, :descr, :class, :image)
            ", [
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
    

    public function delete(Query $sql, $id){

        # Check if the product exists and is selled by the current user
        $data = $sql -> query("
            SELECT * FROM products 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        ", [
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ]); 
    
        if(empty($data)){
            return abort(403);
        }
        # Delete the image associated with the product
        Storage::disk("public") -> delete("product_img/" . $data[0]['image']);


        # Delete comments attached to the product
        $sql -> query("
            DELETE FROM comments WHERE id_product=:id
        ", ["id" => $id]
        );


        # Delete all the cart where product exists
        $sql -> query("
            DELETE FROM cart WHERE id_product=:id
        ", ["id" => $id]);


        # Delete the product itself
        $sql -> query("
            DELETE FROM 
                products 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        ", [
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ]);

        return redirect("/");
    }


    public function show_update_form($id){

        $data = verify_if_product_is_from_current_user($id);
        if(!$data){
            return abort(403);
        }

        return view("updateform", ["data" => $data[0]]);
    }


    public function update(Query $sql, $id, UpdateProduct $req){

        if($req["submit"] === "delete"){
            self::delete($sql, $id);
            $_SESSION["deletedproduct"] = true;
            return redirect(route("root"));
        }
        
        $data = verify_if_product_is_from_current_user($id);
        if(!$data){
            return abort(403);
        }

        if(!in_array($req["category"], [ 
            "filter-laptop", 
            "filter-dresses",
            "filter-gaming",
            "filter-food",
            "filter-other"
        ])){
            return abort(403);
        }

        $sql -> query("
            UPDATE products 
            SET
                `name`=:name, 
                `price`=:price, 
                `class`=:class, 
                `descr`=:descr
            WHERE
                id=:product_id
            ",
            [
                "name" => $req["name"],
                "price" => $req["price"],
                "descr" => $req["description"],
                "class" => $req["category"],
                "product_id" => $id
            ]);
            

        $_SESSION["done"] = "updated";

        return redirect(route("details", $id));
    }


    public function rating(Query $sql, $id){

        # Get the sum of all the rates
        $rating = $sql -> query("
            SELECT SUM(rating) as rating FROM comments 
            WHERE 
                id_product=:id 
        ", [ "id" => $id, ]);

        $rating = $rating[0]["rating"];


        # Get the number of person ho gave a rate
        $number = $sql -> query("
            SELECT COUNT(*) as number FROM comments 
            WHERE 
                id_product=:id
        ", [ "id" => $id, ]);
        $number = $number[0]["number"];


        if(!($number === 0)){

            return [
                "round" => intdiv($rating, $number),
                "real" => round($rating / $number, 1),
                "rate" => (int)$number,
            ];

        }
        
        else {
            return abort(404);
        }
    }
}
