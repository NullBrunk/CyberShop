<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Redirect;
use App\Http\Middleware\Logged;

use App\Http\Controllers\Products;
use App\Http\Controllers\Comments;
use App\Http\Controllers\Contacts;
use App\Http\Controllers\Details;
use App\Http\Controllers\Users;
use App\Http\Controllers\Index;
use App\Http\Controllers\Carts;



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
Route::view("/search", "product.search") -> name("articles");

# Details
Route::get('/details/{product_id}', [ Details::class, "get_details" ] ) -> name("details");


/*
|---------------------------------------------
|  Authentication 
|
*/

Route::view(
    '/signup', 
    'login.signup'
) -> middleware(Redirect::class) -> name("signup");

Route::view(
    '/login', 
    'login.login'
) -> middleware(Redirect::class) -> name("login");

Route::post(
    '/login', 
    [ Users::class, "show" ] 
) -> middleware(Redirect::class);

Route::post(
    '/signup', 
    [ Users::class, "store" ] 
) -> middleware(Redirect::class);

Route::get('/disconnect', function () {

    session_destroy();
    return redirect('/');

}) -> name("disconnect") -> middleware(Logged::class);



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

    Route::post(
        "/add", 
        [ Carts::class, 'add' ] 
    ) -> middleware(Logged::class) -> name('add');

    Route::get(
        "/delete/{id}", 
        [ Carts::class, 'remove' ] 
    ) -> middleware(Logged::class) -> name('remove');

});



/*
|---------------------------------------------
|  Comments management 
|
*/

Route::prefix('comments') -> name("comment.") -> group(function () {

    Route::post(
        "/store/{slug}",
        [ Comments::class, "store" ]
    ) -> middleware(Logged::class) -> name("add");


    Route::get(
        "/delete/{comment}/{article}",
        [ Comments::class, "delete" ]
    ) -> middleware(Logged::class) -> name("delete");

    Route::get(
        "/update/{comment}",
        [ Comments::class, "get_update_form" ]
    ) -> middleware(Logged::class) -> name("update_form");

    Route::post(
        "/update",
        [ Comments::class, "update" ]
    ) -> middleware(Logged::class) -> name("update");
    
});



/*
|---------------------------------------------
|  Products management 
|
*/

Route::prefix('product') -> name("product.") -> group(function () {

    Route::view("/market", "product.market") -> middleware(Logged::class) -> name("sell");

    Route::post(
        "/market",
        [ Products::class, "store" ]
    ) -> middleware(Logged::class) -> name("sell");


    Route::get(
        "/update/{id}",
        [ Products::class, "edit_form" ]
    ) -> middleware(Logged::class) -> name("edit_form");


    Route::post(
        "/update/{id}",
        [ Products::class, "edit" ]
    ) -> middleware(Logged::class) -> name("update");
    
});



/*
|---------------------------------------------
|  Profile management 
|
*/

Route::prefix('profile') -> name("profile.") -> group(function () {

    Route::post(
        "",
        [ Users::class, "profile"]
    ) -> middleware(Logged::class) -> name("profile");
    

    Route::get(
        "",
        [Users::class, "show_profile"]
    ) -> middleware(Logged::class);
    

    Route::get(
        "/delete", 
        [ Users::class, "delete" ] 
    ) -> middleware(Logged::class) -> name("delete");

});



/*
|---------------------------------------------
|  Contact management 
|
*/

Route::prefix('contact') -> name("contact.") -> group(function () {

    Route::get(
        "",
        [ Contacts::class, "show"]
    ) -> middleware(Logged::class) -> name("show");


    Route::get(
        "{slug}",
        [ Contacts::class, "show"]
    ) -> middleware(Logged::class) -> name("user");


    Route::post(
        "",
        [ Contacts::class, "store"]
    ) -> middleware(Logged::class);

    
    Route::get(
        "delete/{contact}",
        [ Contacts::class, "delete"]
    ) -> middleware(Logged::class) -> name("delete");


    Route::get(
        "edit/{contact}",
        [ Contacts::class, "show_form"]
    ) -> middleware(Logged::class) -> name("edit_form");


    Route::patch(
        "edit/{contact}",
        [ Contacts::class, "edit"]
    ) -> middleware(Logged::class) -> name("edit");

});


