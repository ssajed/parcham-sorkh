<?php

namespace App\\Modules\\Accompaniment\\Http\\Controllers;

use App\\Http\\Controllers\\Controller;
use App\\Modules\\Accompaniment\\Models\\AccompanimentRequest;
use App\\Modules\\Accompaniment\\Requests\\AccompanimentRequestFormRequest;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\View\\View;

class AccompanimentRequestController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can_be_companion_seeker, 403);

        $requests = $request->user()
            ->accompanimentRequests()
            ->latest('departure_at')
            ->paginate(10);

        return view('companion-seeker.requests.index', [
            'requests' => $requests,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->can_be_companion_seeker, 403);

        return view('companion-seeker.requests.create');
    }

    public function store(AccompanimentRequestFormRequest $request): RedirectResponse
    {
        $accompanimentRequest = new AccompanimentRequest();
        $accompanimentRequest->fill($request->validated());
        $accompanimentRequest->status = AccompanimentRequest::STATUS_OPEN;

        $request->user()->accompanimentRequests()->save($accompanimentRequest);

        return redirect()
            ->route('companion-seeker.requests.index')
            ->with('status', 'درخواست همراهی شما ثبت شد و در انتظار همراه‌یار است.');
    }
}
