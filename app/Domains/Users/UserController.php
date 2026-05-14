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

    public function showProfile(UserService $userService): InertiaResponse
    {
        return Inertia::render('User/Profile', [
            'title' => 'Mi Perfil',
            'user' => Auth::user()->load('role'),
            'isCurrentUser' => true,
            'roles' => $userService->getAllRoles(),
        ]);
    }

    public function showUsers(UserService $userService): InertiaResponse
    {
        return Inertia::render('User/Index', [
            'title' => 'Usuarios',
            'users' => $userService->getAllUsers(),
        ]);
    }

    public function showUser(int $id, UserService $userService): InertiaResponse
    {
        $user = $userService->getUserById($id);

        if (! $user) {
            abort(404);
        }

        return Inertia::render('User/Profile', [
            'title' => 'Perfil de ' . $user->name . ' ' . $user->surname,
            'user' => $user->load('role'),
            'isCurrentUser' => Auth::id() === $user->id,
            'roles' => $userService->getAllRoles(),
        ]);
    }

    public function updateProfile(Request $request, UserService $userService): RedirectResponse
    {
        $user = $userService->updateProfile($request->id, $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]));
        if ($user) {
            return back()->with('success', 'Perfil actualizado correctamente.');
        }else{
            return back()->with(['error' => 'Error al actualizar el perfil.']);
        }

    }

    public function updatePassword(Request $request, UserService $userService): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! $userService->updatePassword(Auth::id(), $data['current_password'], $data['new_password'])) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        return to_route('profile')->with('success', 'Contraseña actualizada correctamente.');
    }


    public function login(Request $request, UserService $userService): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        if (! $userService->authenticate($credentials['username'], $credentials['password'], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
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
