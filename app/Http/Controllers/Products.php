<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreReq;
use App\Http\Requests\UpdateProductReq;

use Illuminate\Support\Facades\Storage;



function verify_if_product_is_from_current_user($pdo, $id){

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
    public function search(string $search) : array
    {

        $pdo = config("app.pdo");

        $search_product = $pdo -> prepare("
            SELECT id,image,price,class 
            FROM products 
            WHERE 
                `name` LIKE CONCAT('%', :search, '%')
        ");
        $search_product -> execute([
            "search" => $search
        ]); 

        $data = $search_product -> fetchAll(\PDO::FETCH_ASSOC);
        

        return($data);
    }

    public function store(StoreReq $req){      

        $pdo = config("app.pdo");

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
                    products(`id_user`, `name`, `price`, `descr`, `class`, `image`)
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
        
        $pdo = config("app.pdo"); 

        # Check if the product exists and is selled by the current user
        $select_products = $pdo -> prepare("
            SELECT * FROM products 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        "); 
        $select_products -> execute([
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ]);
        
        $data = $select_products -> fetch();
        
    
        if(empty($data)){
            return abort(403);
        }

        # Delete the image associated with the product
        Storage::disk("public") -> delete("product_img/" . $data['image']);


        # Delete comments attached to the product
        $delete_comments = $pdo -> prepare("
            DELETE FROM comments WHERE id_product=:id
        ");
        $delete_comments -> execute(
            ["id" => $id]
        );


        # Delete the product itself

        $del_product = $pdo -> prepare("
            DELETE FROM 
                products 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        ");

        $del_product -> execute([
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ]);

        return redirect("/");
    }


    public function show_update_form($id){

        $pdo = config("app.pdo");

        $data = verify_if_product_is_from_current_user($pdo, $id);
        if(!$data){
            return abort(403);
        }

        return view("updateform", ["data" => $data[0]]);
    }


    public function update($id, UpdateProductReq $req){

        $pdo = config("app.pdo");
        
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
            UPDATE products 
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


    public function rating($id){

        $pdo = config("app.pdo");

        # Get the sum of all the rates
        $rating = $pdo -> prepare("
            SELECT SUM(rating) as rating FROM comments 
            WHERE 
                id_product=:id 
        ");

        $rating -> execute([ "id" => $id, ]);
        $rating = $rating -> fetch()[0];


        # Get the number of person ho gave a rate
        $number = $pdo -> prepare("
            SELECT COUNT(*) as number FROM comments 
            WHERE 
                id_product=:id
        ");
        $number -> execute([ "id" => $id, ]);
        $number_of_rate = $number -> fetch()[0];


        if(!($number_of_rate === 0)){

            return [
                "round" => intdiv($rating, $number_of_rate),
                "real" => $rating / $number_of_rate,
                "rate" => (int)$number_of_rate,
            ];

        }
        else {
            return abort(404);
        }
    }
}
