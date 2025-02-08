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
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
    /**
     * Determine the appropriate field (email, username, or phone) for login.
     */
    protected function getLoginField(): string
    {
        $login = $this->input('login');
    
        // Check if the login is an email address
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
    
        // Check if the login is a phone number (assuming it's numeric)
        if (is_numeric($login)) {
            return 'phone';
        }
    
        // Assume it's a username
        return 'username';
    }
    
    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function authenticate(): void
    // {
    //     $this->ensureIsNotRateLimited();
    
    //     $loginField = $this->getLoginField();
    
    //     if (! Auth::attempt([$loginField => $this->input('login'), 'password' => $this->input('password')], $this->boolean('remember'))) {
    //         RateLimiter::hit($this->throttleKey());
    
    //         throw ValidationException::withMessages([
    //             'login' => trans('auth.failed'),
    //         ]);
    //     }
    
    //     RateLimiter::clear($this->throttleKey());
    // }
    public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    $loginField = $this->getLoginField();

    // Attempt to authenticate the user
    if (! Auth::attempt([$loginField => $this->input('login'), 'password' => $this->input('password')], $this->boolean('remember'))) {
        // If authentication fails, hit the rate limiter
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);
    }

    // Check if the authenticated user is active
    $user = Auth::user();

    if ($user->status!='active') {
        Auth::logout();
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('User is Inactive! Contact With Admin'), // Custom message for inactive users
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}

    
    

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
