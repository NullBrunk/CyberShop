<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        "id",
        "id_contactor",
        "id_contacted",
        "type",
        "content",
        "readed",
        "time"
    ];
}
