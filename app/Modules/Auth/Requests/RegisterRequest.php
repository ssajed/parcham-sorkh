<?php

namespace App\Modules\Auth\Requests;

use App\Models\User;
use App\Rules\ValidIranianNationalCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        foreach ([
            'national_code',
            'mobile',
            'vehicle_plate_region',
            'vehicle_plate_three',
            'vehicle_plate_last',
        ] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $data[$field] = $this->normalizeDigits(trim($value));
            }
        }

        foreach (['vehicle_model', 'vehicle_plate_letter'] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $data[$field] = trim($value);
            }
        }

        if ($data !== []) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        $isCompanionHelper = $this->input('role') === 'companion_helper';

        $plateLetters = [
            'الف', 'ب', 'پ', 'ت', 'ث', 'ج', 'د',
            'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط',
            'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ',
            'ل', 'م', 'ن', 'و', 'ه', 'ی',
        ];

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:150',
            ],

            'national_code' => [
                'required',
                'string',
                'size:10',
                new ValidIranianNationalCode(),
                Rule::unique('users', 'national_code'),
            ],

            'mobile' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
                Rule::unique('users', 'mobile'),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'string',
                Rule::in([
                    User::ROLE_COMPANION_SEEKER,
                    User::ROLE_COMPANION_HELPER,
                ]),
            ],

            'vehicle_model' => [
                Rule::requiredIf($isCompanionHelper),
                Rule::prohibitedIf(!$isCompanionHelper),
                'string',
                'max:100',
            ],

            // کد ایران؛ دو رقم اول پلاک
            'vehicle_plate_region' => [
                Rule::requiredIf($isCompanionHelper),
                Rule::prohibitedIf(!$isCompanionHelper),
                'digits:2',
            ],

            // شماره سه‌رقمی پلاک
            'vehicle_plate_three' => [
                Rule::requiredIf($isCompanionHelper),
                Rule::prohibitedIf(!$isCompanionHelper),
                'digits:3',
            ],

            // حرف پلاک
            'vehicle_plate_letter' => [
                Rule::requiredIf($isCompanionHelper),
                Rule::prohibitedIf(!$isCompanionHelper),
                Rule::in($plateLetters),
            ],

            // دو رقم انتهایی پلاک
            'vehicle_plate_last' => [
                Rule::requiredIf($isCompanionHelper),
                Rule::prohibitedIf(!$isCompanionHelper),
                'digits:2',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'وارد کردن نام و نام خانوادگی الزامی است.',
            'name.min' => 'نام باید حداقل ۳ نویسه باشد.',
            'name.max' => 'نام نمی‌تواند بیشتر از ۱۵۰ نویسه باشد.',

            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.size' => 'کد ملی باید ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',

            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'شماره موبایل باید با 09 و به‌صورت ۱۱ رقمی وارد شود.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',

            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۸ نویسه باشد.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',

            'role.required' => 'انتخاب نوع حساب الزامی است.',
            'role.in' => 'نوع حساب انتخاب‌شده معتبر نیست.',

            'vehicle_model.required' => 'وارد کردن مدل خودرو الزامی است.',
            'vehicle_model.max' => 'مدل خودرو نمی‌تواند بیشتر از ۱۰۰ نویسه باشد.',

            'vehicle_plate_region.required' => 'دو رقم کد ایران را وارد کنید.',
            'vehicle_plate_region.digits' => 'کد ایران باید دقیقاً ۲ رقم باشد.',

            'vehicle_plate_three.required' => 'سه رقم پلاک را وارد کنید.',
            'vehicle_plate_three.digits' => 'بخش سه‌رقمی پلاک باید دقیقاً ۳ رقم باشد.',

            'vehicle_plate_letter.required' => 'حرف پلاک را انتخاب کنید.',
            'vehicle_plate_letter.in' => 'حرف انتخاب‌شده برای پلاک معتبر نیست.',

            'vehicle_plate_last.required' => 'دو رقم انتهایی پلاک را وارد کنید.',
            'vehicle_plate_last.digits' => 'بخش انتهایی پلاک باید دقیقاً ۲ رقم باشد.',

            'vehicle_model.prohibited' => 'اطلاعات خودرو برای همراه‌جو نباید ارسال شود.',
            'vehicle_plate_region.prohibited' => 'اطلاعات پلاک برای همراه‌جو نباید ارسال شود.',
            'vehicle_plate_three.prohibited' => 'اطلاعات پلاک برای همراه‌جو نباید ارسال شود.',
            'vehicle_plate_letter.prohibited' => 'اطلاعات پلاک برای همراه‌جو نباید ارسال شود.',
            'vehicle_plate_last.prohibited' => 'اطلاعات پلاک برای همراه‌جو نباید ارسال شود.',
        ];
    }

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
