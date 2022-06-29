<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|alpha_dash|unique:users,username',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     */
    public function messages()
    {
        return [
            'username.required' => 'Username is required',
            'username.unique' => 'Username already exists',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password cannot be less than 8 characters',
            'password.confirmed' => 'Password confirmation is not the same'
        ];
    }
}
