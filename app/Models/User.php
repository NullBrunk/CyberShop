<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        # Get the human readable date (3 weeks from now, 2 days ago ...)
        $date = Carbon::parse($this -> created_at) -> diffForHumans();

        # Convert the "from now" to "ago"
        return str_replace("from now", "ago", $date);
    }

    public function comments() {
        return $this -> hasMany(Comment::class, "id_user");
    }
}
