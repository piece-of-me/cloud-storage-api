<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password' => 'string|min:8',
            'email' => 'string|email|unique:users',
            'phone' => 'string',
            'name' => 'string',
            'surname' => 'string',
            'birthdate' => 'date|before:now-1day',
        ];
    }

    public function messages()
    {
        return [
            'password.string' => 'Поле "password" должно быть строкой',
            'password.min' => 'Поле "password" должно быть минимум 8 символов',
            'email.string' => 'Поле "email" должно быть строкой',
            'email.email' => 'Поле "email" должно содержать корректный e-mail адрес',
            'email.unique' => 'Данный e-mail уже используется',
            'phone.string' => 'Поле "phone" должно быть строкой',
            'name.string' => 'Поле "name" должно быть строкой',
            'surname.string' => 'Поле "surname" должно быть строкой',
            'birthdate.date' => 'Поле "birthdate" должно содержать корректную дату',
            'birthdate.before' => 'Дата в поле "birthdate" должна быть меньше сегодняшней даты',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
