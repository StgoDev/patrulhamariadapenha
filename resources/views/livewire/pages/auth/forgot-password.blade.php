<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Recuperação de Credencial</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">
            Esqueceu sua senha operativa? Sem problemas. Informe seu e-mail institucional e enviaremos um link seguro
            para a redefinição da sua chave de acesso.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 text-green-700"
        :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Endereço de E-mail Institucional" class="text-gray-700 font-bold" />
            <x-text-input wire:model="email" id="email"
                class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                type="email" name="email" required autofocus placeholder="nome@corporacao.gov.br" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 font-semibold" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm font-semibold text-[var(--primary-color)] hover:text-purple-800 transition-colors"
                href="{{ route('login') }}" wire:navigate>
                Retornar ao Portal
            </a>
            <button type="submit"
                class="inline-flex justify-center items-center px-6 py-3 bg-[var(--primary-color)] border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-md active:scale-95 disabled:opacity-50">
                Enviar
                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
        </div>
    </form>
</div>