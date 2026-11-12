<?php

namespace App\Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanionProfileController extends Controller
{
    public function seeker(Request $request): View
    {
        abort_unless($request->user()->can_be_companion_seeker, 403);

        return view('companion-seeker.profile.seeker', [
            'user' => $request->user(),
        ]);
    }

    public function helper(Request $request): View
    {
        abort_unless($request->user()->can_be_companion_helper, 403);

        return view('companion-seeker.profile.helper', [
            'user' => $request->user(),
        ]);
    }
}
