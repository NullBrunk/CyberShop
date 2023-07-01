<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Cart extends Controller {

    public function initialize(){

        

        $_SESSION['cart'] = [];


        $pdo = config("app.pdo");

        $get_cart = $pdo -> prepare("
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
        ");
        $get_cart -> execute([ "id" => $_SESSION["id"] ]);

        
        $data = $get_cart -> fetchall(\PDO::FETCH_ASSOC);

        foreach($data as $d){
            $_SESSION['cart'][$d["cid"]] = $d;
        }



        return redirect(url() -> previous());
    }
   
    public function add(Request $req){
        
        $pdo = config("app.pdo");


        $product_id = $req["id"];

        if($product_id){
            
            $get_product = $pdo -> prepare("SELECT id as pid, name, image, price FROM products WHERE id=:id");
            $get_product -> execute([ "id" => $product_id ]); 

            $data = ($get_product -> fetchAll(\PDO::FETCH_ASSOC))[0];
            

            if($data){
                $add_to_cart = $pdo -> prepare("
                INSERT INTO cart(id_user, id_product)
                VALUES
                    (:uid, :pid)
                ");

                $add_to_cart -> execute([
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


    public function remove($id){

        $pdo = config("app.pdo");        
        
        if(isset($_SESSION["cart"][$id])){

            $remove = $pdo -> prepare("
                DELETE FROM cart 
                WHERE 
                    id=:id
                AND
                    id_user=:uid
            ");
            $remove -> execute([
                "id" => $id,
                "uid" => $_SESSION["id"]
            ]);

            unset($_SESSION["cart"][$id]);

            return redirect(route("root"));
        }

        return abort(403);
    }

}
