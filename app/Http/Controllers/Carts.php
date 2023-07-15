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
     * @param Cart $cart    The cart model
     *
     * @return redirect     Redirection to the page that call this function.
     */

    public function initialize(Cart $cart){

        $_SESSION['cart'] = [];


        $data = $cart
            -> select('carts.id as cid', 'carts.id_user as cidu', 'carts.id_product as cip', 'products.id as pid', 'name', 'image', 'price')
            -> join('products', 'products.id', '=', 'carts.id_product')
            -> where('carts.id_user', '=', $_SESSION["id"] )
            -> get();

        
        foreach($data as $d){
            $_SESSION['cart'][$d["cid"]] = $d;
        }

        return redirect(url() -> previous());
    }
   
    

    /**
     * Add a product to the cart and call the inititialize function
     * to change the content of the session.
     *
     * @param Request $req         The infotmations of the commended product.
     * @param Product $product     The product model
     * @param Cart $cart           The cart model
     *     
     * @return redirect            Redirection to the cart or to a 403 page if 
     *                             the user is not authorized.
     * 
     */

    public function add(Request $req, Cart $cart, Product $product){
        
        $product_id = $req -> input("id");

        if(!$product_id){
            return abort(403);
        }


        # Check if the product exists
        $product -> findOrFail($product_id); 
    

        # Insert it into the cart
        $cart -> id_user =  $_SESSION["id"];
        $cart -> id_product = $product_id;

        $cart -> save();


        # Regenerate it
        self::initialize($cart);

        return redirect(url() -> previous());        
    
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
