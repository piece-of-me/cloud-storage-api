<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

/**
 * Нельзя создавать подпапки
 */
class WithoutSubFolders implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if(false !== strpos($value, '/')) {
            $fail('Должен быть только один уровень вложенности');
        }
    }
}
