<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SignupForm extends Component
{

    public $mail = "";
    public $pass = "";
    public $repass = "";

    protected $rules = [
        'mail' => 'required|email|unique:users|max:50',
        'pass' => 'required',
        'repass' => "required|same:pass",
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function render()
    {
        return view('livewire.signup-form');
    }
}
