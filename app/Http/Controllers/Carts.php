<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Cart;


class Carts extends Controller {

    /**
     * Initialize a cart by putting all the in database
     * information into a SESSION variable.
     *
     * @return redirect     Redirection to the page that call this function.
     */

    public function initialize(){

        $_SESSION['cart'] = [];

        foreach(Cart::where("id_user", "=", $_SESSION["id"]) -> get() as $d){
            $_SESSION['cart'][$d["id"]] = $d;
        }

        return redirect(url() -> previous());
    }
   
    

    /**
     * Add a product to the cart and call the inititialize function
     * to change the content of the session.
     *
     * @param Product $product       Product threw model binding.

     *     
     * @return response             
     * 
     */

    public function add(Product $product){
        
        # Insert it into the cart

        $add_to_cart = Cart::create([
            "id_user" =>  $_SESSION["id"],
            "id_product" => $product -> id,
        ]);

        # Add it into the session

        $_SESSION['cart'][$add_to_cart -> id] = $add_to_cart;


        return [
            "added" => true,
            "id" => $add_to_cart -> id
        ];

    }



    /**
     * Remove a product from teh SESSION cart and from the Database.
     *
     * @param Cart $id      The concerned produc (threw model binding)
     * 
     * @return redirect     Redirection a redirection, but this method is always
     *                      called from js in a fetch(), so no matter what it returns
     * 
     */

    public function remove(Cart $id){

        if ($id["id_user"] === $_SESSION["id"]){
            $id -> delete();
        }
    
        unset($_SESSION["cart"][$id["id"]]);

        return to_route("root");
    }
}
