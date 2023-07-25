<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\UpdateProduct;
use App\Http\Requests\StoreReq;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Comment;
use App\Models\Cart;
use App\Models\Notif;

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
     * Get the details of a given product
     *
     * @param Notif $notif          The notif model
     * @param Product $product      The product threw model binding 
     * 
     * @return view                 A view with all the details of
     *                              the product, including comments
     *                              technicals details, rating etc. 
     * 
     */

     public function get_details(Notif $notif, Product $product){
                  
        include_once __DIR__ . "/../Utils/Style.php";

        $data = $product -> toArray();
        $data["mail"] = $product -> user -> mail;
        $data["uid"] = $product -> user -> id;

        
        # Delete all notifications linked to it
        session_start();

        if(isset($_SESSION["logged"])){

            # If i m the seller of this product
            if($_SESSION["id"] === $data["uid"]){

                # Delete all notifs linked to it
                $notif 
                -> where("id_user", "=", $_SESSION["id"]) 
                -> where("type", "=", "comment")
                -> where("moreinfo", "=", $data["id"])
                -> delete();
            }
        }

        # Get all the comments of the product
        $comments = $product -> comments() -> orderBy('id', 'desc') -> get();


        $rating = self::rating($product);

        # Bueatify our text 
        $data["descr"] = style($data["descr"]);

        return view("product.details", ["data" => $data, "comments" => $comments, "rating" => $rating]);
    
    }



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
            
        foreach($data as $d){
            $rating[$d["id"]] = self::rating($d)["icons"]; 
        }

        return view("product.categories", ["products" => $data, "name" => $category, "rating" => $rating, "notpaginated" => true]);

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

        $data = is_from_user($product, $id);
        if(empty($data)){
            return abort(403);
        }


        # If the user clicked on the delete button 

        if($req["submit"] === "delete"){
            self::delete($product, $comment, $cart, $id);

            return to_route("root") -> with("deletedproduct", "The product has been deleted successfully.");
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
     * @param Product $product      A product threw model binding
     *  
     * @return array | redirect     An array with all the valuable informations
     *                              A 404 page if no one rated,
     * 
     */
    
    public function rating(Product $product){

        $numbers_of_rate = 0;
        $total_rating = 0;
        $toshow = "";

        foreach($product -> comments as $rat){
            $numbers_of_rate++;
            $total_rating += (int)$rat -> rating;
        }

        if($numbers_of_rate === 0){
            return [];
        }

        $round = intdiv($total_rating, $numbers_of_rate);
        $real = round($total_rating / $numbers_of_rate, 1);
       

        # On effectue une boucle for pour afficher 
        # le nombre d'étoiles en jaune
        
        for($i=0; $i<$round; $i++){
            $toshow .= '<i class="bi bi-star-fill" style="color: #de7921;"></i>';
        }
        
        # On affiche éventuellement une demi étoile jaune
        # si le nombre des dixiemes est supérieur à .5,
        # Si ce n'est pas le cas on affiche une étoile blanche

        if($real >= $round + 0.5){
            $toshow .= '<i style="color: #de7921;" class="bi bi-star-half"></i>';
        }
        elseif($real != 5.0){
            $toshow .= '<i class="bi bi-star" style="color: #de7921;"></i>';
        }

        
        # On affiche rating - 1 étoiles en blanche
        # (-1 car on a deja affiché soit une demi étoile soit une etoile blanche dans le if juste au dessus)    

        for($i = $round + 1; $i < 5; $i++){
            $toshow .= '<i class="bi bi-star" style="color: #de7921;"></i>';
        }

        return [
            "icons" => $toshow,
            "round" => $round,
            "rate" => $numbers_of_rate,
            "real" => $real,
        ];
    }



    /** 
     * Show products of a given category (in a little card)
     * 
     * @param Product $product         The product model
     * @param string $slug             The category name
     * 
     * @return view
    */

    public function show(Request $request, Product $product, $slug){

        if(!in_array($slug, [ "all", "gaming", "laptop", "dresses", "food", "other" ])){
            return abort(404);
        }
       
        // If HTMX is doing the request, don't display the navbar
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
