<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

require_once __DIR__ . "/../Utils/Style.php";


class ProfilePageComments extends Component
{
    public string $mail;

    public function render()
    {
        return view('livewire.profile-page-comments', [
            "comments" => User::where("mail", "=", $this -> mail) -> first() -> comments,
        ]);
    }
}
