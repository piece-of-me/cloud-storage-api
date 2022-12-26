<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;

/**
 * Максимальный размер файлов поле загрузки не должен превышать 100 Мб
 */
class MaximumVolume implements InvokableRule
{
    private const MAX_SIZE = 104857600;

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
        $fileSize = $value->getSize();
        $user = User::find(auth()->user()->id);
        $totalSize = $user->files->sum('size');
        if ($fileSize + $totalSize >= self::MAX_SIZE) {
            $fail('Объем всех файлов на диске для одного пользователя не может превышать 100 Мб');
        }
    }
}
