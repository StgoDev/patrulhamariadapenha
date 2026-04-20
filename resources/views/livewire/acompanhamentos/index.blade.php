<div class="space-y-6">
    <!-- Header e Controles -->
    <div class="flex flex-col md:flex-row justify-between md:items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[var(--primary-color)]">Prontuários Ativos</h2>
            <p class="text-sm text-gray-500 mt-1">Gestão de MPU e acompanhamentos dinâmicos.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Filtro de Status -->
            <div class="relative w-full sm:w-40">
                <select wire:model.live="status" class="w-full py-2 pl-3 pr-8 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] text-gray-700 focus:border-transparent text-sm transition-all shadow-sm font-medium">
                    <option value="">Status: Todos</option>
                    <option value="ATIVA">Ativos</option>
                    <option value="PAUSA">Suspensos (Pausa)</option>
                    <option value="ENCERRADA">Encerrados</option>
                    <option value="RECUSOU">Recusados</option>
                    <option value="REATIVOU">Reativados</option>
                </select>
            </div>
            
            <!-- Barra de Busca -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg wire:loading.remove wire:target="search" class="h-5 w-5 text-gray-400 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-[var(--primary-color)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Nº Processo, Vítima ou Agressor..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent text-sm transition-all shadow-sm">
            </div>

            <a href="{{ route('acompanhamentos.create') }}" wire:navigate class="shrink-0 inline-flex justify-center bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:opacity-90 font-medium transition-opacity shadow-sm w-full sm:w-auto">
                Novo Prontuário
            </a>
        </div>
    </div>

    <!-- Tabela Dinâmica -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden relative">
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-nowrap text-left text-sm text-gray-500">
                    <thead class="bg-gray-50/75 border-b border-gray-200 text-gray-900 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100" wire:click="sortBy('numero_processo')">
                                <span class="flex items-center gap-1">Processo Judicial @if($sortField === 'numero_processo')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@endif</span>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100" wire:click="sortBy('assistida_id')">
                                Vítima (MPU)
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100" wire:click="sortBy('origem_encaminhamento')">
                                <span class="flex items-center gap-1">Origem @if($sortField === 'origem_encaminhamento')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@endif</span>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100" wire:click="sortBy('data_inicio')">
                                <span class="flex items-center gap-1">Data Início @if($sortField === 'data_inicio')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@endif</span>
                            </th>
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100" wire:click="sortBy('situacao')">
                                <span class="flex items-center gap-1">Status @if($sortField === 'situacao')<span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>@endif</span>
                            </th>
                            <th scope="col" class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($acompanhamentos as $pront)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-gray-900 truncate max-w-40" title="{{ $pront->numero_processo }}">
                                    {{ $pront->numero_processo }}
                                </td>
                                <td class="px-6 py-4 font-bold text-[var(--primary-color)]">
                                    {{ $pront->assistida->nome ?? 'N/E' }}
                                    <div class="text-[10px] text-gray-400 mt-0.5 tracking-widest font-mono">
                                        AGRESSOR: {{ $pront->agressor->nome ?? 'N/E' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ $pront->origem_encaminhamento ?? 'Não Registrada' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $pront->data_inicio ? \Carbon\Carbon::parse($pront->data_inicio)->format('d/m/Y') : 'N/I' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($pront->situacao === 'ATIVA')
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-[var(--status-success)]/10 text-[var(--status-success)] font-bold text-[10px]">● ATIVA</span>
                                    @elseif($pront->situacao === 'PAUSA')
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-[var(--status-warning)]/10 text-[var(--status-warning)] font-bold text-[10px]">SUSPENSO</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-600 font-bold text-[10px]">{{ $pront->situacao }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button type="button" wire:click.prevent="openView('{{ $pront->id }}')" class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-md hover:bg-blue-700 shadow-sm transition-colors">Detalhes</button>
                                    <button type="button" wire:click.prevent="openEdit('{{ $pront->id }}')" class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-md hover:bg-orange-600 shadow-sm transition-colors">Editar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <p class="font-medium">Nenhum prontuário registrado ainda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($acompanhamentos->hasPages())
    <div class="mt-4 pb-4">
        {{ $acompanhamentos->links() }}
    </div>
    @endif

    <!-- ÁREA DE MODAIS (GERENCIADAS PELO ALPINE JS INTEGRADO AO LIVEWIRE) -->
    <div x-data="{ 
            openView: @entangle('isViewing'), 
            openEdit: @entangle('isEditing') 
         }" 
         x-init="
            console.log('🎯 AlpineJS Modals Mount OK'); 
            $watch('openView', val => console.log('🔄 Estado openView migrou para:', val));
            $watch('openEdit', val => console.log('🔄 Estado openEdit migrou para:', val));
         "
         x-on:console-log.window="console.log('⚙️ BACKEND DIZ:', $event.detail)"
    >
        
        <!-- MODAL DE VISUALIZAÇÃO -->
        <div x-show="openView" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openView" x-transition.opacity class="fixed inset-0 bg-modal-overlay transition-opacity" wire:click="closeModals" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="openView" x-transition.scale.origin.bottom class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full mt-20">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-t-4 border-blue-600">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Detalhes do Prontuário</h3>
                                <div class="mt-4 space-y-3">
                                    <p class="text-sm"><strong>Vítima:</strong> {{ $prontuarioDetalhado['assistida']['nome'] ?? 'N/A' }}</p>
                                    <p class="text-sm"><strong>Agressor:</strong> {{ $prontuarioDetalhado['agressor']['nome'] ?? 'N/A' }}</p>
                                    <p class="text-sm"><strong>Nº Processo:</strong> {{ $prontuarioDetalhado['numero_processo'] ?? 'N/A' }}</p>
                                    <p class="text-sm"><strong>Origem do MPU:</strong> {{ $prontuarioDetalhado['origem_encaminhamento'] ?? 'Não Registrada' }}</p>
                                    <p class="text-sm">
                                        <strong>Status:</strong> 
                                        <span class="px-2 py-1 text-xs font-bold bg-gray-100 rounded">{{ $prontuarioDetalhado['situacao'] ?? 'N/A' }}</span>
                                    </p>
                                    <p class="text-sm"><strong>Risco FONAR:</strong> {{ $prontuarioDetalhado['nivel_risco_fonar'] ?? 'Desconhecido' }}</p>
                                    <p class="text-sm"><strong>Avisada:</strong> {{ !empty($prontuarioDetalhado['notificado']) && $prontuarioDetalhado['notificado'] ? 'Sim' : 'Não' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="button" wire:click="closeModals" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm outline outline-1 outline-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm relative z-50">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DE EDIÇÃO -->
        <div x-show="openEdit" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openEdit" x-transition.opacity class="fixed inset-0 bg-modal-overlay transition-opacity" wire:click="closeModals" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="openEdit" x-transition.scale.origin.bottom class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full mt-20">
                    <form wire:submit.prevent="updateProntuario">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-t-4 border-orange-500">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Editar Procedimento do Processo</h3>
                                <div class="mt-6 space-y-4 text-left relative z-50">
                                    <!-- ORIGEM -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Origem do Encaminhamento</label>
                                        <input type="text" wire:model="editOrigem" class="relative z-50 w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]">
                                    </div>
                                    
                                    <!-- STATUS -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Situação / Status</label>
                                        <select wire:model="editSituacao" class="relative z-50 w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]">
                                            <option value="ATIVA">ATIVA</option>
                                            <option value="PAUSA">PAUSA</option>
                                            <option value="ENCERRADA">ENCERRADA</option>
                                            <option value="RECUSOU">RECUSOU</option>
                                            <option value="REATIVOU">REATIVOU</option>
                                        </select>
                                    </div>

                                    <!-- FONAR -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nível de Risco (FONAR)</label>
                                        <select wire:model="editNivelRisco" class="relative z-50 w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]">
                                            <option value="">Desconhecido</option>
                                            <option value="BAIXO">BAIXO</option>
                                            <option value="MEDIO">MÉDIO</option>
                                            <option value="ALTO">ALTO</option>
                                            <option value="EXTREMO">EXTREMO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 sm:ml-3 sm:w-auto sm:text-sm relative z-50">
                                Salvar Alterações
                            </button>
                            <button type="button" wire:click="closeModals" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm outline outline-1 outline-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm relative z-50">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
