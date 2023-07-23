<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class Index extends Controller {
    
    /**
     * Get a pagination of selled product on the website and
     * order by yhe latest element
     * 
     * @param Request $request     
     * @param Product $product     The product model
     * 
     * @return view                The index page
     * 
     */

    public function __invoke(Request $request, Product $product){
        
        $products = $product -> orderBy('id', 'desc') -> paginate(4);

        if($request -> server("HTTP_HX_REQUEST") === "true" ){
            $view = "static.pagination";
        }
        else {
            $view = "static.index";
        }


        return view($view, [ "products" => $products ]);
    }
}




