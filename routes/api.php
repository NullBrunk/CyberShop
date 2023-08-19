<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Users;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::get('/user', function (Request $request) {
    return $request->user();
});

*/

Route::get("/products/{category}/{search}", [ Products::class, "search" ]);

# Get all the informations of a product

Route::get("/products/{product}", [ Products::class, "api_details" ]);


# get all the comments of a given product 

Route::get("/comments/{id}", [ Comments::class, "get" ]);

# Get the rating of a product
Route::get("/rating/{product}", [ Products::class, "rating" ]) -> name("rating");

