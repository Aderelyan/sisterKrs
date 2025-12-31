<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // KITA UBAH VALIDASI DARI EMAIL KE NIDN
            'nidn' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // LOGIKA LOGIN UTAMA
        // Kita gunakan only('nidn', 'password')
        if (! Auth::attempt($this->only('nidn', 'password'), $this->boolean('remember'))) {
            
            // Jika gagal login, hitung limit (biar ga di-bruteforce)
            RateLimiter::hit($this->throttleKey());

            // Lempar error agar muncul di halaman login
            throw ValidationException::withMessages([
                'nidn' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nidn' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        // Ubah throttle key jadi berdasarkan NIDN
        return Str::transliterate(Str::lower($this->input('nidn')).'|'.$this->ip());
    }
}