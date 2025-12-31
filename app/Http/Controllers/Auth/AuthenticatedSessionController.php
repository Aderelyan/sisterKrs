<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    // 1. Jalankan autentikasi (cek NIDN & Password)
    $request->authenticate();

    // 2. Regenerasi Session ID (Keamanan)
    $request->session()->regenerate();

    // 3. (PENTING) Paksa simpan session sekarang juga
    $request->session()->save();

    // 4. Redirect Paksa ke Dashboard
    // Jangan pakai intended() dulu, kita tembak langsung
    return redirect('/dashboard');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
