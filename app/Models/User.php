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
        "pass",
        "avatar"
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

    public function get_color(string $color) {

        if(!in_array($color, ["background", "color"])){
            return "#000000";
        }

        if(str_contains($this -> avatar, "&" . $color . "=")) {
            return substr(explode("&". $color . "=", $this -> avatar)[1], 0, 6);
        }

        return "#000000";
    }

}
