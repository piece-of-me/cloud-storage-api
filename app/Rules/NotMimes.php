<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Проверяемый файл не должен иметь тип MIME, передаваемый в конструкторе
 */
class NotMimes implements Rule
{
    private string $types;

    /**
     * Create a new rule instance.
     * @param string $types MIME запрещенных файлов
     *
     * @example 'js', 'php,js', 'php,js,html'
     *
     * @return void
     */
    public function __construct(string $types)
    {
        $this->types = $types;
    }

    /**
     * Determine if the validation rule passes.

     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return false === strpos($this->types, $value->extension());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $extensions = preg_replace('/(\w+)/', '*.$1', $this->types);
        return 'Запрещено загружать ' . $extensions . ' файлы';
    }
}
