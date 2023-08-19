<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\User;
use Livewire\Component;

class ProfilePageProduct extends Component
{
    public $products, $mail, $product_number; 
    public $limit = true;
    private $id_user;

    public function toggle_limit(){
        $this -> limit = !$this -> limit;
        self::show_products();
    }

    public function show_products(){

        $this -> id_user = User::where("mail", "=", $this -> mail) -> first()["id"];  
        $query = Product::where("id_user", "=", $this -> id_user);

        if($this -> limit === false){
            $this -> products = $query -> get();
        }
        else {
            $this -> products = $query -> limit(6) -> get();
        }
    }

    public function mount() {
        $this -> id_user = User::where("mail", "=", $this -> mail) -> first()["id"];  
        $this -> product_number =  Product::where("id_user", "=", $this -> id_user) -> count();
    }

    public function render()
    {
        return view('livewire.profile-page-product');
    }
}
