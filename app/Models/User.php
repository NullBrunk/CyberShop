<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{

    use HasFactory, Notifiable;
    
    protected $fillable = [
        "id",
        "mail",
        "pass"
    ];

    protected $casts = [
        "verified" => "bool"
    ];

    public function format_date(){
        return  date('d/m/Y', strtotime($this -> created_at));
    }

}
