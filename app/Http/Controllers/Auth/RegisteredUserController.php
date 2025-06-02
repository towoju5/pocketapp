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
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $name = explode(" ", $request->name);

        $user = User::create([
            'first_name' => $name[0],
            'last_name' => $name[1] ?? $name[0],
            'username' => $name[0] . rand(2201, 9999),
            'email' => $request->email,
            'config' => [
                "email_notifications" => "false",
                "manager_updates" => "false",
                "company_news" => "false",
                "company_promotions" => "false",
                "trading_analytics" => "false",
                "trading_statements" => "false",
                "education_emails" => "false",
                "sound_notifications" => "false",
                "default_language" => "en",
            ],
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
