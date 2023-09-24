<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;


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
            $_SESSION['cart'][$d -> id_product] = $d;
        }

        return back();
    }
   
    

    /**
     * Ajouter un element au panier soit en incrémentant le champs "quantity",
     * soit en créant un nouvel enregistrel 
     *
     * @param Product $product       Product threw model binding.

     *     
     * @return response             
     * 
     */

    public static function add(int $id){
        
        $product = Product::findOrFail($id);

        $product_in_cart = Cart::where([
            "id_user" =>  $_SESSION["id"],
            "id_product" => $product -> id,
        ]) -> first();          


        # If the product isnt already in the cart 
        
        if(!$product_in_cart) {
            $add_to_cart = Cart::create([
                "id_user" =>  $_SESSION["id"],
                "id_product" => $product -> id,
                "quantity" => 1,
            ]);

            $_SESSION['cart'][$product -> id] = $add_to_cart;

            return [
                "added" => true,
                "id" => $product -> id,
                "add" => false,
            ];
    
        } else {
            $product_in_cart -> quantity += 1;
            $product_in_cart -> save();

            $_SESSION["cart"][$product_in_cart -> id_product]["quantity"] += 1;
        
            return [
                "added" => true,
                "id" => $product_in_cart -> id_product,
                "add" => true,
            ];    
        }
    }



    /**
     * Remove a product from the SESSION cart and from the Database.
     *     * 
     * @return redirect     
     * 
     */

    public static function remove(int $id){
        
        $product = Cart::where("id_user", $_SESSION["id"]) -> where("id_product", $id) -> first();
        
        if($product -> quantity === 1) {
            
            $product -> delete();
            unset($_SESSION["cart"][$id]);

            return [
                "removed" => true,
            ];    
        } else {
            $product -> quantity = $product -> quantity - 1;
            $product -> save();

            $_SESSION["cart"][$id]["quantity"] -= 1;

            return [
                "removed" => false,
            ];
        }
    }
}
