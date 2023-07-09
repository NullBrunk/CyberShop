<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Sql;


class Cart extends Controller {

    /**
     * Initialize a cart by putting all the in database
     * information into a SESSION variable.
     *
     * @return redirect     Redirection to the page that call this function.
     */

    public function initialize(){

        $_SESSION['cart'] = [];

        $data = Sql::query("
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
   
    

    /**
     * Add a product to the cart and call the inititialize function
     * to change the content of the session.
     *
     * @param Request $req     The infotmations of the commended product.
     * 
     * @return redirect        Redirection to the cart or to a 403 page if 
     *                         the user is not authorized.
     * 
     */

    public function add(Request $req){
        

        $product_id = $req["id"];

        if($product_id){
            
            $data = Sql::query(
                "SELECT id as pid, name, image, price FROM products WHERE id=:id",
                [ "id" => $product_id ]
            )[0]; 


            if($data){
                Sql::query("
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



    /**
     * Remove a product from teh SESSION cart and from the Database.
     *
     * @param int $id      The id of the product in the cart table.
     * 
     * @return redirect    Redirection a redirection, but this method is always
     *                     called from js in a fetch(), so no matter what it returns
     * 
     */

    public function remove($id){

        if(isset($_SESSION["cart"][$id])){

            Sql::query("
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
