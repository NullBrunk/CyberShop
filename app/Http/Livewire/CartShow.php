<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Controllers\Carts;

class CartShow extends Component
{

    public function rm(int $id) {
        session_start();
        Carts::remove($id);
    }

    public function render()
    {
        return view('livewire.cart-show');
    }
}
