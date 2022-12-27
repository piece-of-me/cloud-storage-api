<?php

namespace App\Http\Requests\File;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRequest extends FormRequest
{
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
            'name' => 'string|required_without:public_link,folder',
            'public_link' => 'boolean|required_without:name,folder',
            'folder' => 'string|required_without:name,public_link',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Поле "name" должно быть строкой',
            'public_link.boolean' => 'Поле "public_link" должно иметь тип boolean',
            'folder.string' => 'Поле "folder" должно быть строкой',
            'name.required_without' => 'Необходимо указать хотя бы один атрибут',
            'public_link.required_without' => 'Необходимо указать хотя бы один атрибут',
            'folder.required_without' => 'Необходимо указать хотя бы один атрибут',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
