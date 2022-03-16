<?php

namespace App\Http\Requests;

use App\Actions\PasswordRules;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'between:2,100'],
            'last_name' => ['required', 'string', 'between:2,100'],
            'is_admin' => ['nullable', 'boolean'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRules::getRules()],
        ];
    }
}
