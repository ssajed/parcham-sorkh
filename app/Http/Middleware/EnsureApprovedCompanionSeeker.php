<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedCompanionSeeker
{
    /**
     * Allow approved accounts with at least one companion access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('companion-seeker.login');
        }

        if (! $user instanceof User) {
            abort(403, 'دسترسی به این بخش مجاز نیست.');
        }

        if (! $user->isAccountApproved()) {
            abort(403, 'حساب کاربری شما هنوز تأیید نشده یا دسترسی آن غیرفعال است.');
        }

        if (! $user->can_be_companion_seeker && ! $user->can_be_companion_helper) {
            abort(403, 'برای حساب شما دسترسی همراه‌جو یا همراه‌یار فعال نیست.');
        }

        return $next($request);
    }
}
