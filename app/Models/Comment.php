<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        "id",
        "id_user",
        "id_product",
        "title",
        "content",
        "rating",
        "writed_at"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, "id_product");
    }

    public function user(){
        return $this -> belongsTo(User::class, "id_user"); 
    }

    public function like(){
        return $this -> hasMany(Like::class, "id_comment");
    }
}
