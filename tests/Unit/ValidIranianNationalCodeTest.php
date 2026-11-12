<?php

namespace Tests\Unit;

use App\Rules\ValidIranianNationalCode;
use PHPUnit\Framework\TestCase;

class ValidIranianNationalCodeTest extends TestCase
{
    private function validateNationalCode(mixed $value): ?string
    {
        $rule = new ValidIranianNationalCode();

        $error = null;

        $rule->validate(
            'national_code',
            $value,
            function (string $message) use (&$error): void {
                $error = $message;
            }
        );

        return $error;
    }

    public function test_valid_national_code_is_accepted(): void
    {
        $this->assertNull(
            $this->validateNationalCode('0012345679')
        );
    }

    public function test_invalid_control_digit_is_rejected(): void
    {
        $this->assertNotNull(
            $this->validateNationalCode('0012345678')
        );
    }

    public function test_repeated_digits_are_rejected(): void
    {
        $this->assertNotNull(
            $this->validateNationalCode('1111111111')
        );
    }

    public function test_persian_digits_are_accepted(): void
    {
        $this->assertNull(
            $this->validateNationalCode('۰۰۱۲۳۴۵۶۷۹')
        );
    }

    public function test_arabic_digits_are_accepted(): void
    {
        $this->assertNull(
            $this->validateNationalCode('٠٠١٢٣٤٥٦٧٩')
        );
    }

    public function test_codes_with_invalid_length_are_rejected(): void
    {
        $this->assertNotNull(
            $this->validateNationalCode('123456789')
        );

        $this->assertNotNull(
            $this->validateNationalCode('00123456790')
        );
    }

    public function test_non_string_values_are_rejected(): void
    {
        $this->assertNotNull(
            $this->validateNationalCode(1234567890)
        );
    }
}
