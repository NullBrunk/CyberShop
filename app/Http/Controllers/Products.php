<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReq;
use App\Http\Requests\UpdateProduct;

use Illuminate\Support\Facades\Storage;


use App\Models\Product;
use App\Models\Comment;
use App\Models\Cart;


/**
 * Test if a product is from a given user
 *
 * @param Product $product       The product model 
 * @param int $id                The id of the product
 *  
 * @return array                 An empty array if not, a fill array if yes
 * 
 */

function is_from_user(Product $product, $id){
    
    return $product 
    -> select("users.id as uid", "products.id as pid", "id_user", "price", "descr", "class", "mail", "image", "name")
    -> join('users', 'users.id', '=', 'products.id_user')
    -> where('products.id', '=', $id )
    -> where('id_user', '=', $_SESSION['id'] )
    -> get()
    -> toArray();
}

class Products extends Controller
{ 
 
    /**
     * Search threw all the product with a LIKE operator
     *
     * @param Product $product   The product model
     * @param string $search     The product to search
     *  
     * @return array             The products that matched the like
     * 
     */
    public function search(Product $product, string $search) : array
    {

        return $product 
            -> select("id", "image", "price", "class")
            -> where("name", "like", "%" . $search . "%")
            -> get()
            -> toArray();
    }


    /**
     * Store a product from the /sell page.
     *
     * @param StoreReq $req      The request with all the informations
     * @param Product $product   The product model
     *  
     * @return view             Return the view of /sell (will change)
     * 
     */

     public function store(StoreReq $req, Product $product){      

        # Check if te user category is a valid category 

        if(!in_array($req["category"], [ 
            "filter-laptop", 
            "filter-dresses",
            "filter-gaming",
            "filter-food",
            "filter-other"
        ])){
            return abort(403);
        }


        # Test the image 

        $img = $req["product_img"];

        if($img !== null && !$img -> getError()){

            # Store the image 

            $imgPath = $req["product_img"] -> store("product_img", "public");


            # Store the product 

            $product -> id_user = $_SESSION["id"];

            $product -> name = $req["name"];
            $product -> descr = $req["description"];
            $product -> price = $req["price"];

            $product -> class = $req["category"];
            $product -> image = substr($imgPath, 12);

            $product -> save();

        }

        else {
            $_SESSION["error"] = true;
            return view("sell");
        }


        $_SESSION["done"] = true;
        return view("sell");
    }


    /**
     * Delete a given product if the user is allowed to 
     *
     * @param int $id               The id of the product
     * @param Product $product      The product model
     * @param Comment $comment      The comment model
     * @param cart $cart            The cart model
     *  
     * @return redirect             Redirection to / if success, or to a 403
     *                              page if not.
     * 
     */

    public function delete(Product $product, Comment $comment, Cart $cart, $id){

        # Check if the product exists and is selled by the current user

        $data = $product -> findOrFail($id) -> toArray();
        if($data["id_user"] !== $_SESSION["id"] or empty($data)){
            return abort(403);
        }
       

        # Delete the image associated with the product
        Storage::disk("public") -> delete("product_img/" . $data['image']);


        # Delete comments attached to the product
        $comment -> where("id_product", "=", $id) -> delete();


        # Delete all the cart where product exists
        $cart -> where("id_product", "=", $id) -> delete();


        # Delete the product itself
        $product -> where("id", "=", $id) -> delete();

        return to_route("root");

    }


    /**
     * Show an update form to update a product if the user is allowed to 
     * 
     * @param Product $product      The product model
     * @param int $id               The id of the product
     *  
     * @return abort | view         a 403 page if he is not allowed
     *                              a view if he is.
     * 
     */

    public function show_update_form(Product $product, $id){

        $data = is_from_user($product, $id);
        
        if(empty($data)){
            return abort(403);
        }

        return view("updateform", ["data" => $data[0]]);
    }



    /**
     * Update a product if the user is allowed to
     *
     * @param UpdateProduct $req     The informations of the new product 
     * @param Product $product       The product model
     * @param Comment $comment       The comment model
     * @param Cart $cart             The cart model
     * @param int $id                The id of the product
     * 
     * @return redirect              A 403 page if he is not allowed
     *                               redirect to the page of the updated product
     *                               if he is allowed to.
     * 
     */

    public function update(UpdateProduct $req, Product $product, Comment $comment, Cart $cart, $id, ){

        # If the user clicked on the delete button 

        if($req["submit"] === "delete"){
            self::delete($product, $comment, $cart, $id);
            $_SESSION["deletedproduct"] = true;
            return to_route("root");
        }
        
        $data = is_from_user($product, $id);
        if(empty($data)){
            return abort(403);
        }

        
        # Test if the given category is valid
        
        if(!in_array($req["category"], [ 
            "filter-laptop", 
            "filter-dresses",
            "filter-gaming",
            "filter-food",
            "filter-other"
        ])){
            return abort(403);
        }


        $product 
        -> where("id", "=", $id)
        -> update([
            "name" => $req["name"],
            "price" => $req["price"],
            "descr" => $req["description"],
            "class" => $req["category"],
        ]);


        $_SESSION["done"] = "updated";

        return to_route("details", $id);
    }



    /**
     * Calculate the different rating (rounded, real, number of rates) 
     *
     * @param Comment $comment      The comments model
     * @param int $id               The id of the product
     *  
     * @return array | redirect     An array with all the valuable informations
     *                              A 404 page if no one rated,
     * 
     */
    
    public function rating(Comment $comment, $id){

        $rating = $comment 
            -> where("id_product", "=", $id)
            -> sum("rating") ;


        $number = $comment -> where("id_product", "=", $id) -> get() -> count();

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
