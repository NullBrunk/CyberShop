<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Contacts;
use App\Http\Controllers\Tmpimage;
use App\Http\Controllers\Profile;
use App\Http\Controllers\Users;
use App\Http\Controllers\Carts;
use App\Http\Controllers\Index;
use App\Http\Controllers\Likes;


/*
|---------------------------------------------
|  Others 
*/

# Show an error
Route::view("/todo", "static.todo");

# Index page
Route::get('/', [ Index::class , "show"]) -> name("root");

# Profile page
Route::get("/p/{user:mail}", [ Profile::class, "show"] ) -> name("profile");

/*
|---------------------------------------------
|  Authentication 
*/

Route::get('/logout', function () {

    session_destroy();
    return redirect('/');

}) -> name("logout") -> middleware("logged");


Route::name("auth.") -> controller(Users::class) -> middleware("guest") -> group(function (){

    Route::view('/signup', "auth.signup") -> name("signup");

    Route::view('/login', 'auth.login') -> name("login");

    Route::post('/login', "login") ;

    Route::post('/signup', "store");

});
Route::get("/mail/verify/{slug}", 
    [ Users::class, "confirm_mail" ]
) -> name("auth.confirm_mail");



/*
|---------------------------------------------
|  Cart management 
*/

Route::prefix('cart') -> controller(Carts::class) -> middleware("logged") -> name("cart.") -> group(function () {
    
    Route::view("show", "static. todo") -> name("display");

    Route::get("" , 'initialize') -> name("initialize");

    Route::get("/delete/{id}",  'remove') -> name('remove');

    Route::get("/add/{product}", 'add') -> name('add');

});



/*
|---------------------------------------------
|  Comments management 
*/

Route::prefix('comments') -> controller(Comments::class) -> middleware("logged") -> name("comment.")  -> group(function () {
    
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
    
    Route::get(
        "/category/search/{category}/", "search"
    ) -> name("search");
    Route::view("/market", "product.market") -> middleware("logged") -> name("sell");
    
    Route::post(
        "/market", "store" 
    ) -> middleware("logged") -> name("store");
    
    Route::get(
        "/edit/{product}", "edit_form" 
    ) -> middleware("logged") -> name("edit_form");

    Route::post(
        "/edit/{product}", "edit"
    ) -> middleware("logged") -> name("edit");
    
    Route::delete(
        "/edit/image/{image}", "remove_image"
    ) -> middleware("logged") -> name("image_delete");

    Route::get(
        "/edit/image/{image}", "change_main"
    ) -> middleware("logged") -> name("change_main");

});

Route::get("/category/{slug}", [ Products::class, "show" ]) -> name("product.show");

Route::get("/details/{product}", [ Products::class, "get_details" ]) -> name("details");



/*
|---------------------------------------------
|  Settings management 
*/

Route::prefix('settings') -> controller(Users::class) -> middleware("logged") -> name("profile.") -> group(function () {
    
    Route::post("", "settings") -> name("settings");

    Route::delete("/delete", "delete") -> name("delete");

    Route::get("", "show_settings");

});



/*
|---------------------------------------------
|  Chatbox management 
*/

Route::prefix('chatbox') -> controller(Contacts::class) -> middleware("logged") -> name("contact.") -> group(function () {

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
    ) -> name("toggle") -> middleware("logged");

    Route::get(
        "/get/{comment}", "is_liked"
    ) -> name("get");
});



/*
|---------------------------------------------
|  File upload managment
*/

Route::prefix('/upload') -> controller(Tmpimage::class) -> middleware("logged") -> name("tmp.") -> group(function () {
    
    Route::post("store", "store") -> name("store") ;
    Route::delete("delete", "delete") -> name("delete") -> middleware("logged");

});

