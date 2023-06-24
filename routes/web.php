<?php

use App\Http\Middleware\Logged;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Details;
use App\Http\Controllers\Contact;
use App\Http\Controllers\Users;
use App\Http\Controllers\Index;


/*
|---------------------------------------------
|  Render statics views 
|
*/

Route::view('/about', 'static.about') -> name("about");
Route::view("/sell", "sell") -> middleware(Logged::class) -> name("sell"); 



/*
|---------------------------------------------
|  Others 
|
*/

# Show an error
Route::view("/todo", "static.todo");

# Index page
Route::get('/', Index::class ) -> name("root");

# SearchBar
Route::view("/articles", "articles") -> name("articles");

# Details
Route::get('/details/{product_id}', Details::class ) -> name("details");




/*
|---------------------------------------------
|  Authentication 
|
*/

Route::view('/signup', 'login.signup') -> name("signup");
Route::view('/login', 'login.login') -> name("login");

Route::post('/login',  [ Users::class, "show" ] );
Route::post('/signup', [ Users::class, "store" ] );


Route::get('/disconnect', function () {

    session_destroy();
    return redirect('/');

}) -> name("disconnect") -> middleware(Logged::class);



/*
|---------------------------------------------
|  Cart management 
|
*/

Route::prefix('cart') -> group(function () {
    Route::post(
        "/add", 
        [ Products::class, 'addProductToCart' ] 
    ) -> middleware(Logged::class) -> name('cart.add');

    Route::get(
        "/delete/{id}", 
        [ Products::class, 'deleteProductFromCart' ] 
    ) -> middleware(Logged::class) -> name('cart.remove');

});



/*
|---------------------------------------------
|  Comments management 
|
*/

Route::prefix('comments') -> group(function () {

    Route::post(
        "",
        [ Comments::class, "store" ]
    ) -> middleware(Logged::class) -> name("comment.add");


    Route::get(
        "/delete/{article}/{id}",
        [ Comments::class, "delete" ]
    ) -> middleware(Logged::class) -> name("comment.delete");
    
});



/*
|---------------------------------------------
|  Products management 
|
*/

Route::prefix('product') -> group(function () {

    Route::post(
        "/sell",
        [ Products::class, "store" ]
    ) -> middleware(Logged::class) -> name("product.sell");


    Route::delete(
        "/delete/{slug}",
        [ Products::class, "delete" ]
    ) -> middleware(Logged::class) -> name("product.delete");


    Route::get(
        "/update/{id}",
        [ Products::class, "show_update_form" ]
    ) -> middleware(Logged::class) -> name("product.updateform");


    Route::post(
        "/update/{id}",
        [ Products::class, "update" ]
    ) -> middleware(Logged::class) -> name("product.update");
    
});



/*
|---------------------------------------------
|  Profile management 
|
*/

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



/*
|---------------------------------------------
|  Contact management 
|
*/

Route::prefix('contact') -> group(function () {

    Route::get(
        "",
        [ Contact::class, "show"]
    ) -> middleware(Logged::class) -> name("contact");


    Route::get(
        "{slug}",
        [ Contact::class, "show"]
    ) -> middleware(Logged::class) -> name("contactuser");


    Route::post(
        "",
        [ Contact::class, "send"]
    ) -> middleware(Logged::class);

    
    Route::get(
        "delete/{slug}",
        [ Contact::class, "delete"]
    ) -> middleware(Logged::class) -> name("delete");

});
