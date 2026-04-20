<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Definir Nova Senha</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">
            Sua identidade foi validada. Preencha os campos abaixo para registrar sua nova credencial de acesso tático.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Endereço de E-mail" class="text-gray-700 font-bold" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Nova Senha Operacional" class="text-gray-700 font-bold" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo de 8 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirme sua Nova Senha" class="text-gray-700 font-bold" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" placeholder="Repita a senha" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="flex items-center justify-end pt-2">
            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-[var(--primary-color)] border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-md active:scale-95 disabled:opacity-50">
                Confirmar Redefinição
                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            </button>
        </div>
    </form>
</div>
