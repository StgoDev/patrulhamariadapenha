<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">E-mail Institucional Retido</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium pb-2">
            Obrigado por ingressar na rede operativa! Antes de iniciar, poderia confirmar seu endereço de e-mail clicando no link que acabamos de enviar com segurança? Caso não tenha recebido, emitiremos uma nova ordem de aprovação.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 font-bold text-sm text-green-700 shadow-sm">
            Um novo enlace de ativação foi deferido e despachado para a caixa de correio da sua Patrulha!
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
        <button wire:click="sendVerification" class="inline-flex justify-center items-center px-6 py-3 bg-[var(--primary-color)] border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-md active:scale-95 disabled:opacity-50">
            Reenviar Link de Controle
        </button>

        <button wire:click="logout" type="submit" class="text-sm font-semibold text-gray-500 hover:text-red-600 transition-colors">
            Cancelar e Deslogar
        </button>
    </div>
</div>
