<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

#[Signature('app:create-admin-user')]
#[Description('Create the first system administrator')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $this->info('ایجاد مدیر سامانه پرچم سرخ');

        if (User::query()->where('role', User::ROLE_ADMIN)->exists()) {
            $this->error('حساب مدیر سامانه از قبل وجود دارد.');

            return self::FAILURE;
        }

        $name = trim((string) $this->ask('نام مدیر'));

        $mobile = trim((string) $this->ask('شماره موبایل'));

        $password = (string) $this->secret('رمز عبور');

        $passwordConfirmation = (string) $this->secret('تکرار رمز عبور');

        try {
            $validated = Validator::make(
                [
                    'name' => $name,
                    'mobile' => $mobile,
                    'password' => $password,
                    'password_confirmation' => $passwordConfirmation,
                ],
                [
                    'name' => ['required', 'string', 'max:255'],
                    'mobile' => [
                        'required',
                        'string',
                        'regex:/^09[0-9]{9}$/',
                        'unique:users,mobile',
                    ],
                    'password' => [
                        'required',
                        'string',
                        'confirmed',
                        Password::min(8),
                    ],
                ],
                [
                    'name.required' => 'وارد کردن نام مدیر الزامی است.',
                    'mobile.required' => 'شماره موبایل الزامی است.',
                    'mobile.regex' => 'شماره موبایل باید با 09 شروع شود و ۱۱ رقم باشد.',
                    'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
                    'password.required' => 'رمز عبور الزامی است.',
                    'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
                    'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
                ],
            )->validate();
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $errors) {
                foreach ($errors as $error) {
                    $this->error($error);
                }
            }

            return self::FAILURE;
        }

        $user = new User();

        $user->name = $validated['name'];
        $user->mobile = $validated['mobile'];
        $user->password = $validated['password'];

        $user->role = User::ROLE_ADMIN;
        $user->account_status = User::ACCOUNT_STATUS_APPROVED;
        $user->account_approved_at = now();

        $user->save();

        $this->newLine();
        $this->info('حساب مدیر سامانه با موفقیت ایجاد شد.');
        $this->line('نام: '.$user->name);
        $this->line('شماره موبایل: '.$user->mobile);
        $this->line('وضعیت: تأییدشده');

        return self::SUCCESS;
    }
}
