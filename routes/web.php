<?php

use App\Http\Middleware\Logged;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Details;
use App\Http\Controllers\Users;
use App\Http\Controllers\Index;


/*
|---------------------------------------------
| Render statics views 
|

*/

Route::view('/about', 'static.about') -> name("about");

Route::view('/contact', 'static.contact') -> name("contact");

Route::view("/sell", "sell") -> middleware(Logged::class) -> name("sell"); 



/*
|---------------------------------------------
| Others 
|

*/

Route::get('/', Index::class ) -> name("root");



# Login / Signup

Route::view('/signup', 'login.signup') -> name("signup");
Route::view('/login', 'login.login') -> name("login");

Route::post('/login',  [ Users::class, "show" ] );
Route::post('/signup', [ Users::class, "store" ] );



# Disconnect

Route::get('/disconnect', function () {

    session_destroy();
    return redirect('/');

}) -> name("disconnect") -> middleware(Logged::class);



# Details 

Route::get('/details/{product_id}', Details::class ) -> name("details");


# Articles && SearchBar

Route::view("/articles", "articles") -> name("articles");




# Cart Managment
Route::prefix('cart') -> group(function () {
    Route::post(
        "/add", 
        [ Products::class, 'addProductToCart' ] 
    ) -> middleware(Logged::class) -> name('addCart');

    Route::get(
        "/delete/{id}", 
        [ Products::class, 'deleteProductFromCart' ] 
    ) -> middleware(Logged::class) -> name('removeCart');
});




# Comments
Route::prefix('comments') -> group(function () {

    Route::post(
        "",
        [ Comments::class, "store" ]
    ) -> middleware(Logged::class) -> name("addComment");


    Route::get(
        "/delete/{article}/{id}",
        [ Comments::class, "delete" ]
    ) -> middleware(Logged::class) -> name("deleteComment");
    
});




# Products 
Route::prefix('product') -> group(function () {

    Route::post(
        "/sell",
        [ Products::class, "store" ]
    ) -> middleware(Logged::class) -> name("sellProduct");


    Route::delete(
        "/delete/{slug}",
        [ Products::class, "delete" ]
    ) -> middleware(Logged::class) -> name("deleteProduct");


    Route::get(
        "/update/{id}",
        [ Products::class, "show_update_form" ]
    ) -> middleware(Logged::class) -> name("updateForm");


    Route::post(
        "/update/{id}",
        [ Products::class, "update" ]
    ) -> middleware(Logged::class) -> name("updateProduct");
    

    
});




# Profile
Route::prefix('profile') -> group(function () {

    Route::post(
        "",
        [ Users::class, "profile"]
    ) -> middleware(Logged::class) -> name("profile");
    

    Route::get(
        "",
        [Users::class, "showProfile"]
    ) -> middleware(Logged::class);
    

    Route::get(
        "/delete", 
        [ Users::class, "delete" ] 
    ) -> middleware(Logged::class) -> name("deleteAccount");

});

