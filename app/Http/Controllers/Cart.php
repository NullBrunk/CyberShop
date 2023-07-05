<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Query;


class Cart extends Controller {

    public function initialize(Query $sql){

        $_SESSION['cart'] = [];

        $data = $sql -> query("
            SELECT 
                cart.id as cid,
                cart.id_user as cidu,
                cart.id_product as cip,
                products.id as pid,
            
                name, image, price
        
            FROM cart INNER JOIN products ON 
                products.id = cart.id_product
            AND
                cart.id_user = :id
        ", [ 
            "id" => $_SESSION["id"] 
        ]);

        
        foreach($data as $d){
            $_SESSION['cart'][$d["cid"]] = $d;
        }

        return redirect(url() -> previous());
    }
   
    public function add(Query $sql, Request $req){
        

        $product_id = $req["id"];

        if($product_id){
            
            $data = $sql -> query(
                "SELECT id as pid, name, image, price FROM products WHERE id=:id",
                [ "id" => $product_id ]
            )[0]; 


            if($data){
                $sql -> query("
                    INSERT INTO cart(id_user, id_product)
                    VALUES
                        (:uid, :pid)
                ", [
                    "uid" => $_SESSION["id"],
                    "pid" => $product_id
                ]);

                unset($_SESSION["cart"]);       
                return redirect(route("cart.initialize"));

            }
            else {
                return abort(403);
            }
        }
        
        return abort(403);
    }


    public function remove(Query $sql, $id){

        if(isset($_SESSION["cart"][$id])){

            $sql -> query("
                DELETE FROM cart 
                WHERE 
                    id=:id
                AND
                    id_user=:uid
            ", [
                "id" => $id,
                "uid" => $_SESSION["id"]
            ]);

            unset($_SESSION["cart"][$id]);

            return redirect(route("root"));
        }

        return abort(403);
    }

}
