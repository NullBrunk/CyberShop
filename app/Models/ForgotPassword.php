<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgotPassword extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "id_user", "reset_code",
    ];
}
