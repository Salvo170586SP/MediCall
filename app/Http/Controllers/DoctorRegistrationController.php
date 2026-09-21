<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewDoctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DoctorRegistrationController extends Controller
{
    public function create(): View
    {
        return view('pages::auth.doctor-register');
    }

    public function store(Request $request, CreateNewDoctor $createNewDoctor): RedirectResponse
    {
        $user = $createNewDoctor->create($request->all());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
