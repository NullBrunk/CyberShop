<?php

namespace App\Http\Controllers;

class Index extends Controller {
    
    public function showIndex(){
        
        include_once __DIR__ . '/../Database/config.php';
        $r = $pdo -> query("SELECT * FROM `product`");
        $r = $r -> fetchAll();

        return view("index", ["data" => $r]);

    }
}

