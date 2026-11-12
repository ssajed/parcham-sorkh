<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CompanionSeekerLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.companion-login');
    }

    /**
     * Shared authentication for approved companion seeker/helper accounts.
     */
    public function store(Request $request): RedirectResponse
    {
        $mobile = $request->input('mobile');

        if (is_string($mobile)) {
            $mobile = $this->normalizeDigits(trim($mobile));
        }

        $request->merge(['mobile' => $mobile]);

        $validated = $request->validate([
            'mobile' => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
            'password' => ['required', 'string'],
        ], [
            'mobile.required' => 'شماره موبایل را وارد کنید.',
            'mobile.regex' => 'شماره موبایل معتبر نیست.',
            'password.required' => 'رمز عبور را وارد کنید.',
        ]);

        $throttleKey = 'companion|'
            .Str::transliterate($validated['mobile'])
            .'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'mobile' => "تعداد تلاش‌های ناموفق بیش از حد مجاز است. {$seconds} ثانیه دیگر دوباره تلاش کنید.",
            ]);
        }

        // Check credentials and access before creating an authenticated session.
        $user = User::query()
            ->where('mobile', $validated['mobile'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'mobile' => 'شماره موبایل یا رمز عبور صحیح نیست.',
            ]);
        }

        if ($user->role === User::ROLE_ADMIN) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'mobile' => 'برای ورود مدیر سامانه از صفحه ورود مدیریت استفاده کنید.',
            ]);
        }

        if ($user->isAccountPending()) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'mobile' => 'حساب شما هنوز توسط مدیریت تأیید نشده است.',
            ]);
        }

        if ($user->isAccountRejected()) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'mobile' => 'حساب کاربری شما توسط مدیریت تأیید نشده است. برای پیگیری با مدیریت تماس بگیرید.',
            ]);
        }

        if (! $user->isAccountApproved()
            || (! $user->can_be_companion_seeker && ! $user->can_be_companion_helper)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'mobile' => 'برای حساب شما دسترسی فعال همراه‌جو یا همراه‌یار وجود ندارد.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('companion-seeker.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('companion-seeker.login');
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
