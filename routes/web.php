<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Contacts;
use App\Http\Controllers\Tmpimage;
use App\Http\Controllers\Users;
use App\Http\Controllers\Carts;
use App\Http\Controllers\Index;
use App\Http\Controllers\Likes;

use App\Http\Middleware\Redirect;
use App\Http\Middleware\Logged;


/*
|---------------------------------------------
|  Others 
*/

# Show an error
Route::view("/todo", "static.todo");

# Index page
Route::get('/', [ Index::class , "show"]) -> name("root");



/*
|---------------------------------------------
|  Authentication 
*/

Route::get('/logout', function () {
    session_destroy();
    return redirect('/');
}) -> name("logout") -> middleware(Logged::class);


Route::name("auth.") -> controller(Users::class) -> middleware(Redirect::class) -> group(function (){

    Route::get('/signup', "signup_form") -> name("signup");

    Route::view('/login', 'auth.login') -> name("login");

    Route::post('/login', "login") ;

    Route::post('/signup', "store");

});



/*
|---------------------------------------------
|  Cart management 
*/

Route::prefix('cart') -> controller(Carts::class) -> middleware(Logged::class) -> name("cart.") -> group(function () {
    
    Route::view("show", "user.cart") -> name("display");

    Route::get("" , 'initialize') -> name("initialize");

    Route::get("/delete/{id}",  'remove') -> name('remove');

    Route::get("/add/{product}", 'add') -> name('add');

});



/*
|---------------------------------------------
|  Comments management 
*/

Route::prefix('comments') -> controller(Comments::class) -> middleware(Logged::class) -> name("comment.")  -> group(function () {
    
    Route::get("/update/{comment}", "update_form") -> name("update_form");

    Route::post("/store/{slug}", "store") -> name("store");
    
    Route::patch("/edit", "edit") -> name("edit");
    
    Route::delete("/delete/{comment}/{article}", "delete") -> name("delete");

});



/*
|---------------------------------------------
|  Products management 
*/

Route::prefix('product') -> controller(Products::class) -> name("product.") -> group(function () {

    Route::view("/market", "product.market") -> middleware(Logged::class) -> name("sell");

    Route::get(
        "/edit/{product}", "edit_form" 
    ) -> middleware(Logged::class) -> name("edit_form");

    Route::post(
        "/market", "store" 
    ) -> middleware(Logged::class) -> name("store");

    Route::post(
        "/edit/{product}", "edit"
    ) -> middleware(Logged::class) -> name("edit");
    
    Route::get(
        "/category/search/{category}/", "search"
    ) -> name("search");

});

Route::get("/category/{slug}", [ Products::class, "show" ]) -> name("product.show");

Route::get("/details/{product}", [ Products::class, "get_details" ]) -> name("details");



/*
|---------------------------------------------
|  Settings management 
*/

Route::prefix('settings') -> controller(Users::class) -> middleware(Logged::class) -> name("profile.") -> group(function () {
    
    Route::post("", "settings") -> name("settings");

    Route::delete("/delete", "delete") -> name("delete");

    Route::get("", "show_settings");

});



/*
|---------------------------------------------
|  Chatbox management 
*/

Route::prefix('chatbox') -> controller(Contacts::class) -> middleware(Logged::class) -> name("contact.") -> group(function () {

    Route::get("edit/{contact}","show_form") -> name("edit_form");

    Route::patch("edit/{contact}", "edit") -> name("edit");
    

    Route::delete("delete/{contact}", "delete") -> name("delete");


    Route::post("", "store") -> name("store");

    Route::get("", "show") -> name("show");


    Route::get("/close/{user}", "close");

    Route::get("{slug}", "show") -> name("user");

});



/*
|---------------------------------------------
|  Liking comments management 
*/

Route::prefix('like') -> controller(Likes::class,) -> name("like.") -> group(function () {
    
    Route::get(
        "/toggle/{comment}", "toggle"
    ) -> name("toggle") -> middleware(Logged::class);

    Route::get(
        "/get/{comment}", "is_liked"
    ) -> name("get");
});



/*
|---------------------------------------------
|  File upload managment
*/

Route::prefix('/upload') -> controller(Tmpimage::class) -> middleware(Logged::class) -> name("tmp.") -> group(function () {
    
    Route::post("store", "store") -> name("store") ;
    Route::delete("delete", "delete") -> name("delete") -> middleware(Logged::class);

});
