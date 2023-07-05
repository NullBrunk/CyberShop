<?php

namespace App\Http\Controllers;

use App\Http\Sql;

class Index extends Controller {

    public function __invoke(){
        
        $data = Sql::query(
            "SELECT * FROM `products` ORDER BY id DESC"
        );
            
        return view("static.index", ["data" => $data]);

    }
}

