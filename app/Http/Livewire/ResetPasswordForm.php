<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ResetPasswordForm extends Component
{
    public $code = "";
    public $mail = "";
    public $pass = "";
    public $repass = "";

    protected $rules = [
        'mail' => 'required|email',
        'pass' => 'required',
        'repass' => "required|same:pass"
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.reset-password-form');
    }
}
