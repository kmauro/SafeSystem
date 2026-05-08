<?php

namespace App\Domains\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserController extends Controller
{
    public function showLogin(): InertiaResponse
    {
        return Inertia::render('Auth/Login', [
            'title' => 'Log In',
        ]);
    }

    public function login(Request $request, UserService $userService): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        if (! $userService->authenticate($credentials['email'], $credentials['password'], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function showSignup(): InertiaResponse
    {
        return Inertia::render('Auth/Signup', [
            'title' => 'Sign Up',
        ]);
    }

    public function signup(UserRequest $request, UserService $userService): RedirectResponse
    {
        $user = $userService->register($request->validatedUserData());

        Auth::login($user);

        $request->session()->regenerate();

        return to_route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
