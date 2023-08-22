<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Product;
use App\Models\User;
use Livewire\Component;

class ProfilePageInformations extends Component
{

    public int $like_comments, $comment_posted, $sail_product;
    public float $rating;
    public string $mail;

    private int $id_user;
    private $comments;


    # Put all the comments of the user in a variable
    private function set_user_comments() {
        $this -> comments = Comment::select("id", "rating") -> where("id_user", $this -> id_user) -> get();
    }

    
    private function set_rating(): void {
        
        # Take the average rating value and round she to 1 after the ,
        $avg = round($this -> comments -> avg("rating"), 1) ;
        
        $this -> rating = $avg;
    }


    # Get all the likes under the comments posted by the user
    private function set_like_comments() {

        $this -> like_comments = 0;

        foreach($this -> comments -> toArray() as $comment) {
            $this -> like_comments += Like::where("id_comment", $comment["id"]) -> count();
        }
    }


    public function mount() {
        $this -> id_user = User::where("mail", "=", $this -> mail) -> first()["id"]; 
        
        self::set_user_comments();
    
        $this -> comment_posted = Comment::where("id_user", $this -> id_user) -> count();
        $this -> sail_product = Product::where("id_user", $this -> id_user) -> count();
        
        self::set_like_comments();
        self::set_rating();
    }


    public function render()
    {
        return view('livewire.profile-page-informations');
    }
}
