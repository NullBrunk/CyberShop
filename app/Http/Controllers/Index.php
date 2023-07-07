<?php

namespace App\Http\Controllers;

use App\Http\Sql;

class Index extends Controller {
    
    /**
     * Get all the selled product on the website and
     * display them with glightbox 
     * 
     * @return view     The index page
     * 
     */

    public function __invoke(){
        
        # id DESC to get latest product in first
        $data = Sql::query(
            "SELECT * FROM `products` ORDER BY id DESC"
        );
            
        return view("static.index", ["data" => $data]);
    }
}

