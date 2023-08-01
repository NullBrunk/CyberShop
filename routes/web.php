<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Redirect;
use App\Http\Middleware\Logged;

use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Contacts;
use App\Http\Controllers\Tmpimage;
use App\Http\Controllers\Users;
use App\Http\Controllers\Carts;
use App\Http\Controllers\Index;
use App\Http\Controllers\Likes;

/*
|---------------------------------------------
|  Others 
|
*/

# Show an error
Route::view("/todo", "static.todo");

# Index page
Route::get('/', [ Index::class , "show"]) -> name("root");



/*
|---------------------------------------------
|  Authentication 
|
*/

Route::get('/disconnect', function () {
    session_destroy();
    return redirect('/');
}) -> name("disconnect") -> middleware(Logged::class);


Route::get(
    '/signup', [ Users::class, "signup_form" ],
) -> middleware(Redirect::class) -> name("auth.signup");


Route::view(
    '/login', 
    'auth.login'
) -> middleware(Redirect::class) -> name("auth.login");


Route::post(
    '/login', 
    [ Users::class, "login" ] 
) -> middleware(Redirect::class);


Route::post(
    '/signup', 
    [ Users::class, "store" ] 
) -> middleware(Redirect::class);




/*
|---------------------------------------------
|  Cart management 
|
*/

Route::prefix('cart') -> name("cart.") -> group(function () {

    Route::view("show", "user.cart") -> middleware(Logged::class) -> name("display");

    Route::get(
        "",
        [ Carts::class, 'initialize' ]
    ) -> middleware(Logged::class) -> name("initialize");

    Route::get(
        "/delete/{id}", 
        [ Carts::class, 'remove' ] 
    ) -> middleware(Logged::class) -> name('remove');

    Route::post(
        "/add", 
        [ Carts::class, 'add' ] 
    ) -> middleware(Logged::class) -> name('add');

});



/*
|---------------------------------------------
|  Comments management 
|
*/

Route::prefix('comments') -> name("comment.") -> group(function () {
    
    Route::get(
        "/update/{comment}",
        [ Comments::class, "update_form" ]
    ) -> middleware(Logged::class) -> name("update_form");

    Route::get(
        "/delete/{comment}/{article}",
        [ Comments::class, "delete" ]
    ) -> middleware(Logged::class) -> name("delete");

    Route::post(
        "/store/{slug}",
        [ Comments::class, "store" ]
    ) -> middleware(Logged::class) -> name("store");

    Route::patch(
        "/edit",
        [ Comments::class, "edit" ]
    ) -> middleware(Logged::class) -> name("edit");
    
});



/*
|---------------------------------------------
|  Products management 
|
*/

Route::prefix('product') -> name("product.") -> group(function () {

    Route::view("/market", "product.market") -> middleware(Logged::class) -> name("sell");

    Route::get(
        "/edit/{product}",
        [ Products::class, "edit_form" ]
    ) -> middleware(Logged::class) -> name("edit_form");

    Route::post(
        "/market",
        [ Products::class, "store" ]
    ) -> middleware(Logged::class) -> name("store");

    Route::post(
        "/edit/{product}",
        [ Products::class, "edit" ]
    ) -> middleware(Logged::class) -> name("edit");
    
    Route::get(
        "/category/search/{category}/", 
        [Products::class, "search"]
    ) -> name("search");

});

Route::get(
    "/category/{slug}", 
    [ Products::class, "show" ]
) -> name("product.show");

Route::get(
    "/details/{product}", 
    [ Products::class, "get_details" ] 
) -> name("details");



/*
|---------------------------------------------
|  Settings management 
|
*/

Route::prefix('settings') -> name("profile.") -> group(function () {

    Route::post(
        "",
        [ Users::class, "settings"]
    ) -> middleware(Logged::class) -> name("settings");
        
    Route::get(
        "/delete", 
        [ Users::class, "delete" ] 
        ) -> middleware(Logged::class) -> name("delete");
        
    Route::get(
        "",
        [Users::class, "show_settings"]
    ) -> middleware(Logged::class);
    
});



/*
|---------------------------------------------
|  Chatbox management 
|
*/

Route::prefix('chatbox') -> name("contact.") -> group(function () {

    Route::get(
        "edit/{contact}",
        [ Contacts::class, "show_form"]
    ) -> middleware(Logged::class) -> name("edit_form");

    Route::get(
        "delete/{contact}",
        [ Contacts::class, "delete"]
    ) -> middleware(Logged::class) -> name("delete");

    Route::post(
        "",
        [ Contacts::class, "store"]
    ) -> middleware(Logged::class) -> name("store");

    Route::get(
        "",
        [ Contacts::class, "show"]
    ) -> middleware(Logged::class) -> name("show");

    Route::get(
        "/close/{user}",
        [ Contacts::class, "close" ]
    ) -> middleware(Logged::class);
    
    Route::patch(
        "edit/{contact}",
        [ Contacts::class, "edit"]
    ) -> middleware(Logged::class) -> name("edit");

    Route::get(
        "{slug}",
        [ Contacts::class, "show"]
    ) -> middleware(Logged::class) -> name("user");
});



/*
|---------------------------------------------
|  Liking comments management 
|
*/

Route::prefix('like') -> name("like.") -> group(function () {
    
    Route::get(
        "/toggle/{comment}",
        [ Likes::class, "toggle" ],
    ) -> name("toggle") -> middleware(Logged::class);

    Route::get(
        "/get/{comment}",
        [ Likes::class, "is_liked" ],
    ) -> name("get");
});



/*
|---------------------------------------------
|  File upload managment
|
*/

Route::prefix('/upload') -> name("tmp.") -> group(function () {

    Route::post(
        "store", 
        [ Tmpimage::class, "store"]
    ) -> name("store") -> middleware(Logged::class);

    Route::delete(
        "delete", 
        [ Tmpimage::class, "delete"]
    ) -> name("delete") -> middleware(Logged::class);

});
