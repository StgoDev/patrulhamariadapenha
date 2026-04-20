<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Sistema Administrativo</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">Autentique-se com seu CPF para acessar o sistema.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- CPF Address -->
        <div>
            <x-input-label for="cpf" value="CPF" class="text-gray-700 font-bold" />
            <x-text-input wire:model="form.cpf" id="cpf"
                class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                type="text" name="cpf" required autofocus autocomplete="username"
                placeholder="Digite apenas os números..." maxlength="14"
                oninput="let v = this.value.replace(/\D/g, ''); this.value = v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');" />
            <x-input-error :messages="$errors->get('form.cpf')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center">
                <x-input-label for="password" value="Senha" class="text-gray-700 font-bold" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-[var(--primary-color)] hover:text-purple-800 transition-colors"
                        href="{{ route('password.request') }}" wire:navigate>
                        Esqueceu sua senha?
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password"
                class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me & Submit -->
        <div class="flex items-center justify-between pt-2">
            <label for="remember" class="flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-[var(--primary-color)] shadow-sm focus:ring-[var(--primary-color)] w-5 h-5 transition-colors cursor-pointer"
                    name="remember">
                <span class="ms-3 text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">Manter
                    conectado</span>
            </label>

            <button type="submit"
                class="inline-flex justify-center items-center px-6 py-3 bg-[var(--primary-color)] border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-md active:scale-95 disabled:opacity-50">
                Acessar
                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

        <div class="mt-8 text-center text-sm font-medium text-gray-500 border-t border-gray-100 pt-6">
            Não possui acesso tático?
            <a href="{{ route('register') }}" wire:navigate
                class="text-[var(--primary-color)] hover:text-purple-800 font-bold transition-colors">
                Requisitar Credencial
            </a>
        </div>
    </form>
</div>