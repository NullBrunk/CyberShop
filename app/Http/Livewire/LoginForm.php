<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LoginForm extends Component
{
    
    public $email = "";
    public $pass = "";

    protected $rules = [
        'email' => 'required|email|max:50',
        'pass' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.login-form');
    }
}
