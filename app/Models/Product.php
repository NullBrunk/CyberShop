<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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


    public function format_price() : string
    {
        return number_format($this -> price, 2, '.', ' ');
    }

    public function comments(){
        return $this -> hasMany(Comment::class, "id_product");
    }

    public function user(){
        return $this -> belongsTo(User::class, "id_user"); 
    }

    public function product_images(){
        return $this -> hasMany(Product_images::class, "id_product");
    }

    public function scopeDesc(Builder $builder){
        return $builder -> orderBy("id", "desc");
    }
}
