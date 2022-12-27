<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

/**
 * В папке, в которую загружается файл, не должно быть файла с таким же именем
 */
class RewritingOnUpload implements Rule, DataAwareRule
{
    protected array $data;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $filePath = '/user_' . auth()->user()->id . '/' . (isset($this->data['folder']) ? $this->data['folder'] . '/' :  '') . $value->getClientOriginalName();
        return Storage::disk('public')->missing($filePath);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'В этой папке уже существую такой файл';
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
