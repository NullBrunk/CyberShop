<?php

namespace App\Http\Controllers;

use App\Models\User;

class Profile extends Controller
{
    public function show(User $user){
        return view("user.profile", [
            "user" => $user,
        ]);
    }
}
