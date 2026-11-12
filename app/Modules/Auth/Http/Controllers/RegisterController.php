<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Auth\Requests\RegisterRequest;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = new User();

        $user->name = $validated['name'];
        $user->national_code = $validated['national_code'];
        $user->mobile = $validated['mobile'];
        $user->password = $validated['password'];
        $user->role = $validated['role'];

        // Initial access is determined only by the validated registration role.
        // Additional access can be granted later only after admin review.
        $user->can_be_companion_seeker =
            $validated['role'] === User::ROLE_COMPANION_SEEKER;

        $user->can_be_companion_helper =
            $validated['role'] === User::ROLE_COMPANION_HELPER;

        if ($validated['role'] === User::ROLE_COMPANION_HELPER) {
            $user->vehicle_model = $validated['vehicle_model'];

            // ترتیب ذخیره پلاک: کد ایران - سه رقم + حرف + دو رقم
            $user->vehicle_plate = sprintf(
                '%s-%s%s%s',
                $validated['vehicle_plate_region'],
                $validated['vehicle_plate_three'],
                $validated['vehicle_plate_letter'],
                $validated['vehicle_plate_last']
            );
        } else {
            $user->vehicle_model = null;
            $user->vehicle_plate = null;
        }

        $user->account_status = User::ACCOUNT_STATUS_PENDING;
        $user->account_approved_at = null;
        $user->mobile_verified_at = null;

        $user->save();

        return redirect()->back()->with(
            'status',
            'اطلاعات ثبت‌نام شما با موفقیت ثبت شد. حساب شما پس از ارسال و بررسی مدارک توسط مدیریت تأیید و فعال خواهد شد.'
        );
    }
}
