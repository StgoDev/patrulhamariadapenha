<div class="space-y-6">
    <div class="flex justify-between items-center relative z-10">
        <h2 class="text-2xl font-bold flex items-center gap-2 text-[var(--primary-color)]">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            Contas de Acesso (Usuários)
        </h2>
        <button wire:click="novo" class="px-4 py-2 bg-[var(--primary-color)] text-white rounded-md hover:bg-opacity-90 font-bold shadow-md transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Novo Acesso
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden relative z-10">
        <div class="p-4 border-b border-gray-100 flex gap-4">
            <input type="text" wire:model.live.debounce.300ms="busca" placeholder="Pesquisar por Nome ou CPF da Conta..." class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)]">
        </div>
        
        <div class="overflow-x-auto relative min-h-[300px]">
             <!-- Loading Overlay Seguro -->
             <div wire:loading.delay.class="flex" class="hidden absolute inset-0 bg-white/60 backdrop-blur-sm z-20 items-center justify-center">
                 <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[var(--primary-color)]"></div>
            </div>

            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100 uppercase text-xs text-gray-500 font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Credencial</th>
                        <th class="px-6 py-4">Vínculo Policial (CPF)</th>
                        <th class="px-6 py-4 text-center">Painel de Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 relative">
                    @forelse($usuarios as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 text-gray-500">#{{ $user->id }}</td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-[var(--text-main)] flex items-center gap-2">
                                {{ $user->name }}
                                @if($user->is_blocked)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-600 text-white uppercase">Bloqueado</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-medium text-gray-700">{{ $user->cpf ?: 'Não Declarado' }}</div>
                            @if($user->funcionario)
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-bold bg-green-100 text-green-800">
                                    Militar Válido: {{ $user->funcionario->matricula }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-bold bg-red-100 text-red-800">
                                    Base Legada: Não Encontrado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click.prevent="alternarBloqueio({{ $user->id }})" class="p-1.5 {{ $user->is_blocked ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-500 hover:bg-gray-600' }} text-white rounded-md transition-colors" title="{{ $user->is_blocked ? 'Desbloquear Acesso' : 'Bloquear Acesso' }}">
                                    @if($user->is_blocked)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    @endif
                                </button>
                                <button wire:click.prevent="editar({{ $user->id }})" class="p-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-md transition-colors" title="Modificar Acesso">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                </button>
                                <button wire:click.prevent="deletar({{ $user->id }})" wire:confirm="VOCÊ TEM CERTEZA? A exclusão deste acesso incapacita a viatura de Logar!" class="p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors" title="Revogar Credencial">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Nenhum registro vivo encontrado nos Servidores.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $usuarios->links() }}
        </div>
    </div>

    <!-- Modal Alpine + Livewire Entangle Architecture -->
    <div x-data="{ open: @entangle('modalEstado') }" 
         x-show="open" 
         x-cloak
         class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <!-- Overlay -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-[#000000a0] backdrop-blur-sm transition-opacity"></div>
        
        <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 scale-95" 
                     x-transition:enter-end="opacity-100 scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 scale-100" 
                     x-transition:leave-end="opacity-0 scale-95" 
                     @click.away="open = false"
                     class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-bold text-gray-900 border-l-4 border-[var(--primary-color)] pl-3" id="modal-title">
                                    {{ $userId ? 'Alterar Credencial do Agente' : 'Emitir Nova Credencial' }}
                                </h3>
                                
                                <div class="mt-6 space-y-5">
                                    <div>
                                        <x-input-label for="cpf" value="CPF Oficial (Chave)" class="font-bold text-gray-700" />
                                        <x-text-input wire:model="cpf" id="cpf" class="block w-full mt-1 bg-gray-50" type="text" maxlength="14" oninput="let v = this.value.replace(/\D/g, ''); this.value = v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');" placeholder="123.456.789-00" />
                                        <x-input-error :messages="$errors->get('cpf')" class="mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label for="name" value="Nome Completo do Policial" class="font-bold text-gray-700" />
                                        <x-text-input wire:model="name" id="name" class="block w-full mt-1 bg-gray-50" type="text" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label for="email" value="Endereço de E-mail Institucional" class="font-bold text-gray-700" />
                                        <x-text-input wire:model="email" id="email" class="block w-full mt-1 bg-gray-50" type="email" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                    </div>
                                    <div class="pt-2 border-t border-gray-100">
                                        <x-input-label for="password" value="{{ $userId ? 'Nova Senha (Deixe em branco para manter a atual)' : 'Senha de Acesso Inicial' }}" class="font-bold text-gray-700" />
                                        <x-text-input wire:model="password" id="password" class="block w-full mt-1" type="password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200">
                        <!-- Botão com loader do Livewire 3 -->
                        <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button" class="inline-flex w-full justify-center items-center rounded-md bg-[var(--primary-color)] px-4 py-2 text-sm font-bold text-white shadow hover:bg-opacity-90 sm:ml-3 sm:w-auto transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="salvar">Salvar Credencial</span>
                            <span wire:loading wire:target="salvar">Autenticando...</span>
                        </button>
                        <button @click="open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-100 sm:mt-0 sm:w-auto transition-colors">
                            Abortar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
