<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\UpdateProduct;
use App\Http\Requests\StoreReq;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Comment;
use App\Models\Cart;

/**
 * Test if a product is from a given user, if he is return product informations
 * and users information.
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
     * @param Request $request
     * @param Product $product   The product model
     * @param string $category   The ctageory of the product
     *  
     * @return view
     * 
     */

    
    public function search(Request $request, Product $product, string $category)
    {

        if($request -> input("search")){
            $search = $request -> input("search");
        }
        else {
            return abort(403);
        }

        if(!in_array($category, [ "all", "gaming", "laptop", "dresses", "food" ])){
            return abort(404);
        }
       
        
        if($category === "all"){
            $data = $product -> orderBy('id', 'desc') -> where("name", "like", "%" . $search . "%") -> get();
        }
        else {
            $data = $product -> where("class", "=", "filter-" . $category) -> where("name", "like", "%" . $search . "%") -> orderBy('id', 'desc') -> get();
        }
            
        return view("product.categories", ["products" => $data, "name" => $category, "notpaginated" => true]);

    }



    /**
     * Store a product from the /sell page.
     *
     * @param StoreReq $request      The request with all the informations
     * @param Product $product       The product model
     *  
     * @return view                  Return the view of /sell (will change)
     * 
     */

     public function store(StoreReq $request, Product $product){      

        $req = $request -> validated();

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
            $product -> descr = htmlspecialchars($req["description"]);
            $product -> price = $req["price"];

            $product -> class = $req["category"];
            $product -> image = substr($imgPath, 12);

            $product -> save();

        }

        else {
            return to_route("product.store") -> withErrors(["imgerror" => "Invalid image"]) ;
        }

        return to_route("details", $product -> id) -> with("selled", "The product has been succesfully selled !");
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

        # Delete the product itself
        $product -> where("id", "=", $id) -> delete();
    }


    /**
     * Show an edition form to update a product if the user is allowed to 
     * 
     * @param Product $product      The product model
     * @param int $id               The id of the product
     *  
     * @return abort | view         a 403 page if he is not allowed
     *                              a view if he is.
     * 
     */

    public function edit_form(Product $product, $id){

        $data = is_from_user($product, $id);
        
        if(empty($data)){
            return abort(403);
        }

        return view("product.form_product", ["data" => $data[0]]);
    }



    /**
     * Edit a product if the user is allowed to
     *
     * @param UpdateProduct $request     The informations of the new product 
     * @param Product $product           The product model
     * @param Comment $comment           The comment model
     * @param Cart $cart                 The cart model
     * @param int $id                    The id of the product
     *  
     * @return redirect                  A 403 page if he is not allowed
     *                                   redirect to the page of the updated product
     *                                   if he is allowed to.
     * 
     */

    public function edit(UpdateProduct $req, Product $product, Comment $comment, Cart $cart, $id, ){

        # If the user clicked on the delete button 

        if($req["submit"] === "delete"){
            self::delete($product, $comment, $cart, $id);

            return to_route("root") -> with("deletedproduct", "The product has been deleted successfully.");
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
            "descr" => htmlspecialchars($req["description"]),
            "class" => $req["category"],
        ]);

        return to_route("details", $id) -> with("updated", "Product updated successfully.");
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
        if($number === 0){
            return abort(404);
        }


        $toshow = "";
        $rating = [
            "round" => intdiv($rating, $number),
            "real" => round($rating / $number, 1),
            "rate" => (int)$number,
        ];


        
        # On effectue une boucle for pour afficher 
        # le nombre d'étoiles en jaune
        
        for($i=0; $i<$rating['round']; $i++){
            $toshow .= '<i class="bi bi-star-fill" style="color: #de7921;"></i>';
        }
        
        # On affiche éventuellement une demi étoile jaune
        # si le nombre des dixiemes est supérieur à .5,
        # Si ce n'est pas le cas on affiche une étoile blanche

        if($rating["real"] >= $rating["round"] + 0.5){
            $toshow .= '<i style="color: #de7921;" class="bi bi-star-half"></i>';
        }
        elseif($rating["real"] != 5.0){
            $toshow .= '<i class="bi bi-star" style="color: #de7921;"></i>';
        }

        
        # On affiche rating - 1 étoiles en blanche
        # (-1 car on a deja affiché soit une demi étoile soit une etoile blanche dans le if juste au dessus)    

        for($i = $rating['round'] + 1; $i < 5; $i++){
            $toshow .= '<i class="bi bi-star" style="color: #de7921;"></i>';
        }

        $rating["icons"] = $toshow;


        return $rating;

    }



    /** 
     * Show products of a given category
     * 
     * @param Product $product         The product model
     * @param string $slug             The category name
     * 
     * @return view
    */

    public function show(Request $request, Product $product, $slug){

        if(!in_array($slug, [ "all", "gaming", "laptop", "dresses", "food" ])){
            return abort(404);
        }
       
        if($request -> server("HTTP_HX_REQUEST") === "true" ){
            $view = "static.pagination";
        }
        else {
            $view = "product.categories";
        }

        
        if($slug === "all"){
            $data = $product -> orderBy('id', 'desc') -> paginate(4);
        }
        else {
            $data = $product -> where("class", "=", "filter-" . $slug) -> orderBy('id', 'desc') -> paginate(4);
        }

            
        return view($view, ["products" => $data, "name" => $slug]);

    }
}
