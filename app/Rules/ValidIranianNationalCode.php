<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidIranianNationalCode implements ValidationRule
{
    /**
     * Validate an Iranian national code.
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (! is_string($value)) {
            $fail('کد ملی باید به‌صورت رشته وارد شود.');
            return;
        }

        $nationalCode = $this->normalizeDigits(trim($value));

        if (! preg_match('/^[0-9]{10}$/D', $nationalCode)) {
            $fail('کد ملی باید دقیقاً شامل ۱۰ رقم باشد.');
            return;
        }

        if (preg_match('/^([0-9])\1{9}$/D', $nationalCode)) {
            $fail('کد ملی واردشده معتبر نیست.');
            return;
        }

        $sum = 0;

        for ($index = 0; $index < 9; $index++) {
            $digit = (int) $nationalCode[$index];
            $weight = 10 - $index;

            $sum += $digit * $weight;
        }

        $remainder = $sum % 11;

        $expectedDigit = $remainder < 2
            ? $remainder
            : 11 - $remainder;

        $controlDigit = (int) $nationalCode[9];

        if ($controlDigit !== $expectedDigit) {
            $fail('کد ملی واردشده معتبر نیست.');
        }
    }

    /**
     * Convert Persian and Arabic digits to English digits.
     */
    private function normalizeDigits(string $value): string
    {
        return strtr($value, [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',

            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
        ]);
    }
}
