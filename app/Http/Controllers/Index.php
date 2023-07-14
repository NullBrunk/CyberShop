<?php

namespace App\Http\Controllers;


use App\Models\Product;


class Index extends Controller {
    
    /**
     * Get all the selled product on the website and
     * order by yhe latest element
     * 
     * @param Product $product     The product model
     * 
     * @return view                The index page
     * 
     */

    public function __invoke(Product $product){
        
        $data = $product -> orderBy('id', 'desc') -> get() -> toArray();
        
        return view("static.index", [ "data" =>  $data ]);

    }
}




