<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReq extends FormRequest
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
            "mainimg" => "required",
            "category" => "required|max:20",
            "price" => "required|required|regex:/(^[0-9]{0,12}(\.){0,1}[0-9]{1,2}$)/u",
            "description" => "required",
            "name" => "required|max:29"
        ];
    }
}
