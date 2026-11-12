<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * Display the administrator login form.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Authenticate an approved administrator.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'password' => ['required', 'string'],
        ], [
            'mobile.required' => 'شماره موبایل را وارد کنید.',
            'mobile.regex' => 'شماره موبایل معتبر نیست.',
            'password.required' => 'رمز عبور را وارد کنید.',
        ]);

        $mobile = $validated['mobile'];

        $throttleKey = Str::transliterate($mobile).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'mobile' => "تعداد تلاش‌های ناموفق بیش از حد مجاز است. {$seconds} ثانیه دیگر دوباره تلاش کنید.",
            ]);
        }

        $authenticated = Auth::attempt([
            'mobile' => $mobile,
            'password' => $validated['password'],
        ]);

        if (! $authenticated) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'mobile' => 'شماره موبایل یا رمز عبور صحیح نیست.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'mobile' => 'این حساب اجازه ورود به پنل مدیریت را ندارد.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Log the administrator out.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
