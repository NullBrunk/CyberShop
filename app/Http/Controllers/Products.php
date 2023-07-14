<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReq;
use App\Http\Requests\UpdateProduct;

use Illuminate\Support\Facades\Storage;
use App\Http\Sql;

/**
 * Test if a product is from a given user
 *
 * @param int $id     The id of the product
 *  
 * @return array      An empty array if not, a fill array if yes
 * 
 */

function is_from_user($id){

    $validate = Sql::query("
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
    ", [
        "id_user" => $_SESSION['id'],
        "id_product" => $id 
    ]);

    return $validate;
}

class Products extends Controller
{ 
 
    /**
     * Search threw all the product with a LIKE operator
     *
     * @param string $search     The product to search
     *  
     * @return array             The products that matched the like
     * 
     */
    public function search(string $search) : array
    {

        $data = Sql::query("
            SELECT id,image,price,class 
            FROM products 
            WHERE 
                `name` LIKE CONCAT('%', :search, '%')
        ", [ "search" => $search ]);


        return ($data);
    }



    /**
     * Store a product from the /sell page.
     *
     * @param StoreReq $req     The request with all the informations
     *  
     * @return view             Return the view of /sell (will change)
     * 
     */

     public function store(StoreReq $req){      

        # If te user category is a valid category 

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

            
            Sql::query("
                INSERT INTO 
                    products(`id_user`, `name`, `price`, `descr`, `class`, `image`)
                VALUES
                    (:id_user, :name, :price, :descr, :class, :image)
            ", [
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
   
    

    /**
     * Delete a given product if the user is allowed to 
     *
     * @param int $id     The id of the product
     *  
     * @return redirect   Redirection to / if success, or to a 403
     *                    page if not.
     * 
     */

    public function delete($id){

        # Check if the product exists and is selled by the current user

        $data = Sql::query("
            SELECT * FROM products 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        ", [
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ]); 
    
        if(empty($data)){
            return abort(403);
        }
   

        # Delete the image associated with the product
        Storage::disk("public") -> delete("product_img/" . $data[0]['image']);


        # Delete comments attached to the product
        Sql::query("
            DELETE FROM comments WHERE id_product=:id
        ", ["id" => $id]
        );


        # Delete all the cart where product exists
        Sql::query("
            DELETE FROM cart WHERE id_product=:id
        ", ["id" => $id]);


        # Delete the product itself
        Sql::query("
            DELETE FROM 
                products 
            WHERE 
                id=:product_id 
            AND 
                id_user=:id_user
        ", [
            "product_id" => $id,
            "id_user" => $_SESSION["id"],
        ]);

        return redirect("/");
    }



    /**
     * Show an update form to update a product if the user is allowed to 
     * 
     * @param int $id          The id of the product
     *  
     * @return abort | view    a 403 page if he is not allowed
     *                         a view if he is.
     * 
     */

    public function show_update_form($id){

        $data = is_from_user($id);
        if(!$data){
            return abort(403);
        }

        return view("updateform", ["data" => $data[0]]);
    }



    /**
     * Update a product if the user is allowed to
     *
     * @param int $id               The id of the product
     * @param UpdateProduct $req    The informations of the new product 
     * 
     * @return redirect              A 403 page if he is not allowed
     *                               redirect to the page of the updated product
     *                               if he is allowed to.
     * 
     */

    public function update($id, UpdateProduct $req){

        # If the user clicked on the delete button 

        if($req["submit"] === "delete"){
            self::delete($id);
            $_SESSION["deletedproduct"] = true;
            return to_route("root");
        }
        
        $data = is_from_user($id);
        if(!$data){
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

        Sql::query("
            UPDATE products 
            SET
                `name`=:name, 
                `price`=:price, 
                `class`=:class, 
                `descr`=:descr
            WHERE
                id=:product_id
            ",
            [
                "name" => $req["name"],
                "price" => $req["price"],
                "descr" => $req["description"],
                "class" => $req["category"],
                "product_id" => $id
            ]);
            

        $_SESSION["done"] = "updated";

        return to_route("details", $id);
    }



    /**
     * Calculate the different rating (rounded, real, number of rates) 
     *
     * @param int $id               The id of the product
     *  
     * @return array | redirect     An array with all the valuable informations
     *                              A 404 page if no one rated,
     * 
     */
    
    public function rating($id){

        # Get the sum of all the rates
        $rating = Sql::query("
            SELECT SUM(rating) as rating FROM comments 
            WHERE 
                id_product=:id 
        ", [ "id" => $id, ]);

        $rating = $rating[0]["rating"];


        # Get the number of person ho gave a rate
        $number = Sql::query("
            SELECT COUNT(*) as number FROM comments 
            WHERE 
                id_product=:id
        ", [ "id" => $id, ]);
        $number = $number[0]["number"];


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
