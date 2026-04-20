<div class="space-y-6">
    <div class="flex justify-between items-center relative z-10">
        <h2 class="text-2xl font-bold flex items-center gap-2 text-[var(--primary-color)]">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
            </svg>
            Efetivo Tático e Policial (Base de Funcionários)
        </h2>
        <button wire:click="novo" class="px-4 py-2 bg-[var(--primary-color)] text-white rounded-md hover:bg-opacity-90 font-bold shadow-md transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Novo Oficial
        </button>
    </div>

    <!-- Interface Padrão SPA: Loading Feedback e Input Flexível -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden relative z-10">
        <div class="p-4 border-b border-gray-100 flex gap-4">
            <input type="text" wire:model.live.debounce.300ms="busca" placeholder="Filtrar por Matrícula, CPF ou Nome do Agente..." class="w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)]">
        </div>
        
        <div class="overflow-x-auto relative min-h-[300px]">
             <!-- Skeleton Loading Block -->
             <div wire:loading.delay.class="flex" class="hidden absolute inset-0 bg-white/60 backdrop-blur-sm z-20 items-center justify-center">
                 <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[var(--primary-color)]"></div>
            </div>

            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-100 uppercase text-xs text-gray-500 font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Matrícula (Siape/PM)</th>
                        <th class="px-6 py-4">Patente & Identidade</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Intervenções</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 relative">
                    @forelse($funcionarios as $func)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-semibold text-gray-700">
                            {{ $func->matricula ?: 'S/N' }}
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-[var(--text-main)]">{{ $func->nome }}</div>
                            <div class="text-xs text-gray-500">CPF: {{ $func->cpf }} | RGPM: {{ $func->rgpm ?: '***' }}</div>
                        </td>
                        <td class="px-6 py-3">
                            @if(str_contains(strtoupper($func->situacao), 'ATIVO') || $func->situacao === '')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                    {{ $func->situacao ?: 'ATIVO' }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                    {{ $func->situacao }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click.prevent="editar({{ $func->id }})" class="p-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-md transition-colors" title="Retificar Expediente">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                </button>
                                <button wire:click.prevent="deletar({{ $func->id }})" wire:confirm="ALERTA: Isso exclui o Policial e as contas vinculadas à ele. Prosseguir?" class="p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors" title="Expulsar da Base Legada">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Nenhum servidor corresponde à pesquisa nesta patente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $funcionarios->links() }}
        </div>
    </div>

    <!-- Wizard Executive Modal para Bypass das 60 colunas brutas -->
    <div x-data="{ open: @entangle('modalEstado') }" 
         x-show="open" 
         x-cloak
         class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <!-- Deep Z-Index Overlay -->
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
                     class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 border-l-4 border-[var(--primary-color)] pl-3 mb-6" id="modal-title">
                            {{ $funcionarioId ? 'Ficha Funcional do Oficial' : 'Integração de Oficial Avulso' }}
                        </h3>
                        
                        <!-- Grid Mobile-First de Execução Tática (2 items Desktop / 1 item Mobile) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Bloco Matrícula -->
                            <div>
                                <x-input-label for="matricula" value="Código de Matrícula" class="font-bold text-gray-700" />
                                <x-text-input wire:model="matricula" id="matricula" class="block w-full mt-1 bg-gray-50 border-gray-300" type="text" placeholder="00000-0" />
                                <x-input-error :messages="$errors->get('matricula')" class="mt-1" />
                            </div>

                            <!-- Bloco CPF Rigido -->
                            <div>
                                <x-input-label for="cpf" value="CPF Oficial" class="font-bold text-gray-700" />
                                <x-text-input wire:model="cpf" id="cpf" class="block w-full mt-1 bg-gray-50 border-gray-300" type="text" maxlength="14" oninput="let v = this.value.replace(/\D/g, ''); this.value = v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');" placeholder="123.456.789-00" />
                                <x-input-error :messages="$errors->get('cpf')" class="mt-1" />
                            </div>

                            <!-- Bloco Nome Expandido (Ocupa as duas colunas inteiras) -->
                            <div class="md:col-span-2">
                                <x-input-label for="nome" value="Nome Completo do Policial" class="font-bold text-gray-700" />
                                <x-text-input wire:model="nome" id="nome" class="block w-full mt-1 bg-gray-50 border-gray-300" type="text" />
                                <x-input-error :messages="$errors->get('nome')" class="mt-1" />
                            </div>

                            <!-- Bloco RGPM -->
                            <div>
                                <x-input-label for="rgpm" value="Registro Geral da Polícia (RGPM)" class="font-bold text-gray-700" />
                                <x-text-input wire:model="rgpm" id="rgpm" class="block w-full mt-1 bg-gray-50 border-gray-300" type="text" placeholder="10.8006-87" />
                                <x-input-error :messages="$errors->get('rgpm')" class="mt-1" />
                            </div>

                            <!-- Bloco Situação -->
                            <div>
                                <x-input-label for="situacao" value="Termo de Ocupação (Situação)" class="font-bold text-gray-700" />
                                <x-text-input wire:model="situacao" id="situacao" class="block w-full mt-1 bg-gray-50 border-gray-300 uppercase" type="text" placeholder="ATIVO" />
                                <x-input-error :messages="$errors->get('situacao')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Feedback de Higienização de Tabela Oculta -->
                        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 font-medium">
                                        Ficha Resumida. Outras 56 colunas (Fardamento, Sangue) são mantidas congeladas sob preenchimento automático.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200">
                        <!-- Dispatch Assíncrono -->
                        <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button" class="inline-flex w-full justify-center items-center rounded-md bg-[var(--primary-color)] px-4 py-2 text-sm font-bold text-white shadow hover:bg-opacity-90 sm:ml-3 sm:w-auto transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="salvar">Registrar Oficial</span>
                            <span wire:loading wire:target="salvar">Validando Assinatura...</span>
                        </button>
                        <button @click="open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-100 sm:mt-0 sm:w-auto transition-colors">
                            Dispensar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
