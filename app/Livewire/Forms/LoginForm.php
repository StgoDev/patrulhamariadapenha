<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $cpf = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('required', message: 'Você precisa preencher a validação visual acima.')]
    public string $captcha = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Validação Mews Captcha Manual para evitar conflito de Lifecycle do Livewire
        if (!captcha_check($this->captcha)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.captcha' => 'O código de verificação está incorreto ou expirou. Clique na imagem para gerar um novo.',
            ]);
        }

        $user = \App\Models\User::where('cpf', $this->cpf)->first();
        if ($user && $user->is_blocked) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.cpf' => 'Acesso negado: Seu usuário contido no CPF foi bloqueado pelo Comando Administrativo.',
            ]);
        }

        if (! Auth::attempt($this->only(['cpf', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.cpf' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.cpf' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->cpf).'|'.request()->ip());
    }
}
