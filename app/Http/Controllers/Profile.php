<?php

namespace App\Http\Controllers;

use App\Models\User;

class Profile extends Controller
{
    public function show(User $user){
        
        # If the user is not verified he has no profile
        if($user -> verified === false) return abort(404); 

        return view("users.profile", [
            "user" => $user,
        ]);
    }
}
