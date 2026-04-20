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
        <div x-data="{ show: false }">
            <div class="flex justify-between items-center pb-1">
                <x-input-label for="password" value="Senha" class="text-gray-700 font-bold" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-[var(--primary-color)] hover:text-purple-800 transition-colors"
                        href="{{ route('password.request') }}" wire:navigate>
                        Esqueceu sua senha?
                    </a>
                @endif
            </div>

            <div class="relative">
                <x-text-input wire:model="form.password" id="password"
                    class="block w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3 pr-12"
                    x-bind:type="show ? 'text' : 'password'" name="password" placeholder="••••••••" required
                    autocomplete="current-password" />

                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-[var(--primary-color)] focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-500" />
        </div>

        <!-- Captcha Security -->
        <div class="space-y-3">
            <x-input-label for="captcha" value="Validação de Segurança" class="text-gray-700 font-bold mb-1 block text-center uppercase tracking-wider text-xs" />

            <div class="flex flex-col items-center gap-2">
                <div class="inline-flex justify-center" title="Foque para destacar">
                    {!! captcha_img('flat', ['class' => 'captcha-discreto']) !!}
                </div>
                <button type="button" onclick="let cap = document.querySelector('.captcha-discreto'); cap.src = cap.src.split('?')[0] + '?' + Math.random();"
                    class="text-xs text-gray-500 hover:text-[var(--primary-color)] font-bold flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Trocar Imagem
                </button>
            </div>

            <div>
                <x-text-input wire:model="form.captcha" id="captcha"
                    class="block w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3 text-center"
                    type="text" name="captcha" required placeholder="Digite o código aqui" />
            </div>

            <x-input-error :messages="$errors->get('form.captcha')" class="mt-2 text-red-500" />
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

    </form>
</div>