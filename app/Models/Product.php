<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "id",
        "id_user",
        "name",
        "price",
        "descr",
        "class",
        "image"
    ];

    public function comments(){
        return $this -> hasMany(Comment::class, "id_product");
    }

    public function user(){
        return $this -> belongsTo(User::class, "id_user"); 
    }

}
