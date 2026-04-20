<?php

use App\Models\User;
use App\Models\Funcionario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $cpf = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $this->validate([
            'cpf' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'cpf.unique' => 'Esta conta já foi ativada anteriormente. Faça o Login.'
        ]);

        // 1. Limpa o que veio do front-end (mesmo que com sujeira) pra ter só os 11 números.
        $cpfSoNumeros = preg_replace('/[^0-9]/', '', $this->cpf);
        
        if (strlen($cpfSoNumeros) !== 11) {
            $this->addError('cpf', 'O CPF digitado não possui 11 números consistentes.');
            return;
        }

        // 2. Reconstrói matematicamente garantindo que o Backend TENHA A MÁSCARA de forma vitalícia
        $cpfMascarado = preg_replace("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", "$1.$2.$3-$4", $cpfSoNumeros);
        
        // 3. Sobrescreve a propriedade ($this->cpf). Agora até a gravação no model User (abaixo) herdará a máscara perfeitamente!
        $this->cpf = $cpfMascarado;

        // 4. Busca DIRETA E RESTRITA na base legada de Funcionários EXIGINDO a máscara.
        $funcionario = Funcionario::where('cpf', $this->cpf)
            ->first();

        if (!$funcionario) {
            $this->addError('cpf', 'Bloqueio de Segurança: Este CPF não consta na base ativa de Policiais Militares.');
            return;
        }

        // Criando a conta herdando os dados invioláveis do Legado
        $user = User::create([
            'name' => $funcionario->nome ?? 'Militar M.P.',
            'email' => $funcionario->email ?? null,
            'cpf' => $this->cpf,
            'funcionario_id' => $funcionario->id,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Ativação de Credencial</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">Forneça sua chave Cédula Militar (CPF) para cruzar com nossa base legada de Policiais.</p>
    </div>

    <form wire:submit="register" class="space-y-6">
        <!-- Chave Criptográfica / CPF -->
        <div>
            <x-input-label for="cpf" value="Cédula de Pessoa Física (CPF)" class="text-gray-700 font-bold" />
            <x-text-input 
                wire:model="cpf" 
                id="cpf" 
                class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3" 
                type="text" 
                name="cpf" 
                required 
                autofocus 
                placeholder="000.000.000-00" 
                maxlength="14"
                oninput="let v = this.value.replace(/\D/g, ''); this.value = v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');"
            />
            <x-input-error :messages="$errors->get('cpf')" class="mt-2 text-red-500 font-bold" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Criar Senha Operacional" class="text-gray-700 font-bold" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                            type="password"
                            name="password"
                            placeholder="Mínimo de 8 caracteres"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Senha Operacional" class="text-gray-700 font-bold" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full bg-gray-50 border-gray-300 focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] px-4 py-3"
                            type="password"
                            name="password_confirmation" 
                            placeholder="Repita a senha"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm font-semibold text-[var(--primary-color)] hover:text-purple-800 transition-colors" href="{{ route('login') }}" wire:navigate>
                Voltar ao Login
            </a>

            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-[var(--primary-color)] border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-opacity-90 transition-all shadow-md active:scale-95 disabled:opacity-50">
                Ativar Conta
                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </button>
        </div>
    </form>
</div>
