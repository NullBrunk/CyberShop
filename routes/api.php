<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
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

Route::get("/products/{search}", [ Products::class, "search" ]);
Route::get("/comments/{id}", [ Comments::class, "get" ]);
Route::get("/rating/{id}", [Products::class, "rating"]);