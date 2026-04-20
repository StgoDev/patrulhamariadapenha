<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Zona de Risco Operacional</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">
            Você está tentando acessar uma rota restrita ou realizar uma manobra de infraestrutura severa. Por favor, confirme sua Senha Operativa de Autenticação para prosseguir com segurança.
        </p>
    </div>

    <form wire:submit="confirmPassword" class="space-y-6 border-t border-gray-100 pt-6">
        <!-- Password -->
        <div>
            <x-input-label for="password" value="Senha Operacional" class="text-gray-700 font-bold" />

            <x-text-input wire:model="password"
                          id="password"
                          class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                          type="password"
                          name="password"
                          placeholder="Confirme sua senha e tecle ENTER"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 font-semibold" />
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex justify-center items-center px-8 py-3 bg-[var(--primary-color)] border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-md active:scale-95 disabled:opacity-50">
                Confirmar Identidade
                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </button>
        </div>
    </form>
</div>
