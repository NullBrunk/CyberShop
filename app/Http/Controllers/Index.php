<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\Product;


class Index extends Controller
{ 
    /**
     * Show the index page
     * 
     * @param Product $product         The product model
     * 
     * @return view
     */

    public function show(Product $product){
;

        // All doesn't exists as a category, so we add it manually

        $data[0] = [
            "name" => "all", 
            "number" => $product -> select(DB::raw('count(*) as number')) -> get() -> toArray()[0]["number"]
        ];

        
        foreach($product -> select("class as name", DB::raw('count(*) as number')) -> groupBy("class") -> get() -> toArray() as $d){
            array_push($data, $d);
        }

        return view("static.index", [ "data" => $data ]);
    }
}