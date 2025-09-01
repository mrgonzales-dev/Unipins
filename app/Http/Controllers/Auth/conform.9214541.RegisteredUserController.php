<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // return view('auth.register');
        return view('auth.registerAs');
    }

    /**
     * Display the seller registration view.
     */
    public function createSeller(): View
    {
        return view('auth.register-seller');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]
        );

        $user = User::create(
            [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]
        );

        $user->assignRole('buyer');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Handle an incoming seller registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeSeller(Request $request): RedirectResponse
    {
        $request->validate(
            [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'number' => ['required', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'type_of_business' => ['required', 'string', 'max:255'],
            'way_of_payment' => ['required', 'string', 'max:255'],
            ]
        );

        $user = User::create(
            [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'number' => $request->number,
            'organization' => $request->organization,
            'type_of_business' => $request->type_of_business,
            'way_of_payment' => $request->way_of_payment,
            ]
        );

        $user->assignRole('seller');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
