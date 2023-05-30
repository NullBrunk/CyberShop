<?php

namespace App\Http\Controllers;



class Products extends Controller
{

    // Search a given string in the name of the products using LIKE
    public function search(string $search) : array
    {

        include_once __DIR__ . "/../Database/config.php";

        $search_product = $pdo -> prepare("SELECT id,image,price,class FROM product WHERE `name` LIKE CONCAT('%', :search, '%')");
        $search_product -> execute([
            "search" => $search
        ]); 

        $data = $search_product -> fetchAll(\PDO::FETCH_ASSOC);
        
        return($data);
    }

}
