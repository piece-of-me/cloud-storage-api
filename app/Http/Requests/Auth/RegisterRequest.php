<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{

    protected $stopOnFirstFailure = false;
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
            'login' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'email' => 'string|email|unique:users',
            'phone' => 'string',
            'name' => 'string',
            'surname' => 'string',
            'birthdate' => 'date',
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Поле "login" обязательно',
            'login.string' => 'Поле "login" должно быть строкой',
            'login.unique' => 'Данный логин уже используется',
            'password.required' => 'Поле "password" обязательно',
            'password.string' => 'Поле "password" должно быть строкой',
            'password.min' => 'Поле "password" должно быть минимум 8 символов',
            'email.email' => 'Поле "email" должно содержать корректный e-mail адрес',
            'email.unique' => 'Данный e-mail уже используется',
            'birthdate.date' => 'Поле "birthdate" должно содержать корректную дату',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
