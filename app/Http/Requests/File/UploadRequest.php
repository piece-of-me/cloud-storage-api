<?php

namespace App\Http\Requests\File;

use App\Rules\MaximumVolume;
use App\Rules\NotMimes;
use App\Rules\WithoutSubFolders;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadRequest extends FormRequest
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
            'file' => ['required', 'file', 'max:20971520', new NotMimes('php'), new MaximumVolume],
            'folder' => ['string', new WithoutSubFolders],
            'public_link' => 'boolean',
            'life' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Обязательно необходимо приложить файл',
            'file.file' => 'поле "file" должно быть файлом',
            'file.max' => 'Размер одного файла не должен превышать 20 Мб',
            'public_link.boolean' => 'Поле "public_link" должно иметь тип boolean',
            'life.integer' => 'Поле "life" должно иметь целочисленное значение',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
