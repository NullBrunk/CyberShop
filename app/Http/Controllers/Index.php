<?php

namespace App\Http\Controllers;

class Index extends Controller {

    public function __invoke(){
        
        $pdo = config("app.pdo");
        
        $products = $pdo -> query("SELECT * FROM `products` ORDER BY id DESC");
        $data = $products -> fetchAll();
        return view("static.index", ["data" => $data]);

    }
}

