<?php

namespace App\Http\Controllers;

use App\Http\Query;

class Index extends Controller {

    public function __invoke(Query $sql){
        
        $data = $sql -> query(
            "SELECT * FROM `products` ORDER BY id DESC"
        );
            
        return view("static.index", ["data" => $data]);

    }
}

