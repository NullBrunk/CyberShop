<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Controllers\Carts;
use App\Models\Product;

if(!isset($_SESSION)) session_start();

class CartShow extends Component
{

    public function rm(int $id) {
        
        Carts::remove($id);
    }

    public function plus(int $id) {
        Carts::add($id);
    }

    public function render()
    {
        return view('livewire.cart-show');
    }
}
