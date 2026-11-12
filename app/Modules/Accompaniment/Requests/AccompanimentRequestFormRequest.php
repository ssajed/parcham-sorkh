<?php

namespace App\Modules\Accompaniment\Requests;

use App\Modules\Accompaniment\Models\AccompanimentRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccompanimentRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->isCompanionSeeker();
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        foreach (['passenger_count', 'departure_at', 'return_at'] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $data[$field] = $this->normalizeDigits(trim($value));
            }
        }

        foreach ([
            'origin_city',
            'origin_location',
            'destination_city',
            'destination_location',
            'description',
        ] as $field) {
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
        $isRoundTrip = $this->input('type')
            === AccompanimentRequest::TYPE_ROUND_TRIP;

        return [
            'origin_city' => ['required', 'string', 'max:100'],
            'origin_location' => ['required', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:100'],
            'destination_location' => ['required', 'string', 'max:255'],
            'type' => [
                'required',
                'string',
                Rule::in([
                    AccompanimentRequest::TYPE_ONE_WAY,
                    AccompanimentRequest::TYPE_ROUND_TRIP,
                ]),
            ],
            'departure_at' => ['required', 'date', 'after:now'],
            'return_at' => [
                Rule::requiredIf($isRoundTrip),
                Rule::prohibitedIf(! $isRoundTrip),
                'nullable',
                'date',
                'after:departure_at',
            ],
            'passenger_count' => ['required', 'integer', 'between:1,4'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'origin_city.required' => 'وارد کردن شهر مبدأ الزامی است.',
            'origin_city.max' => 'نام شهر مبدأ نباید بیشتر از ۱۰۰ نویسه باشد.',
            'origin_location.required' => 'وارد کردن محل مبدأ الزامی است.',
            'origin_location.max' => 'محل مبدأ نباید بیشتر از ۲۵۵ نویسه باشد.',
            'destination_city.required' => 'وارد کردن شهر مقصد الزامی است.',
            'destination_city.max' => 'نام شهر مقصد نباید بیشتر از ۱۰۰ نویسه باشد.',
            'destination_location.required' => 'وارد کردن محل مقصد الزامی است.',
            'destination_location.max' => 'محل مقصد نباید بیشتر از ۲۵۵ نویسه باشد.',
            'type.required' => 'انتخاب نوع همراهی الزامی است.',
            'type.in' => 'نوع همراهی انتخاب‌شده معتبر نیست.',
            'departure_at.required' => 'تعیین زمان حرکت الزامی است.',
            'departure_at.date' => 'زمان حرکت معتبر نیست.',
            'departure_at.after' => 'زمان حرکت باید در آینده باشد.',
            'return_at.required' => 'برای همراهی رفت‌وبرگشت، تعیین زمان برگشت الزامی است.',
            'return_at.prohibited' => 'برای همراهی یک‌طرفه نباید زمان برگشت ارسال شود.',
            'return_at.date' => 'زمان برگشت معتبر نیست.',
            'return_at.after' => 'زمان برگشت باید بعد از زمان حرکت باشد.',
            'passenger_count.required' => 'تعداد افراد الزامی است.',
            'passenger_count.integer' => 'تعداد افراد باید عدد صحیح باشد.',
            'passenger_count.between' => 'تعداد افراد باید بین ۱ تا ۴ نفر باشد.',
            'description.max' => 'توضیحات نباید بیشتر از ۲۰۰۰ نویسه باشد.',
        ];
    }

    private function normalizeDigits(string $value): string
    {
        return strtr($value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }
}
