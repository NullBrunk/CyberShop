<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ForgotForm extends Component
{
    public $email = "";

    protected $rules = [
        'email' => 'required|email|max:50',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.forgot-form');
    }
}
