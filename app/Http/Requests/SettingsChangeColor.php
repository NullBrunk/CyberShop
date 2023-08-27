<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsChangeColor extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'bgcolor' => "required|regex:/#[a-zA-Z0-9]{6}/u",
            'fgcolor' => "required|regex:/#[a-zA-Z0-9]{6}/u",
        ];
    }
}
