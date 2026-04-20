<div class="space-y-6">
    <!-- Header e Controles -->
    <div
        class="flex flex-col md:flex-row justify-between md:items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[var(--primary-color)]">Gestão de Visitas</h2>
            <p class="text-sm text-gray-500 mt-1">Supervisão dos Agendamentos e Relatórios</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Filtro de Status -->
            <div class="relative w-full sm:w-48">
                <select wire:model.live="filtroStatus"
                    class="w-full py-2 pl-3 pr-8 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] text-gray-700 focus:border-transparent text-sm transition-all shadow-sm font-medium">
                    <option value="">Status: Todos</option>
                    <option value="agendada">Pendentes (Agendada)</option>
                    <option value="em_deslocamento">Em Deslocamento</option>
                    <option value="realizada">Realizada (Finalizada)</option>
                    <option value="frustrada">Frustrada</option>
                </select>
            </div>

            <!-- Barra de Busca -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg wire:loading.remove wire:target="busca" class="h-5 w-5 text-gray-400 transition-opacity"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg wire:loading wire:target="busca" class="animate-spin h-5 w-5 text-[var(--primary-color)]"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="busca" type="search" placeholder="Assistida ou Viatura..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent text-sm transition-all shadow-sm">
            </div>

            <button type="button" wire:click="openScheduleModal"
                class="shrink-0 inline-flex items-center gap-2 justify-center bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:bg-purple-800 font-medium transition-all shadow-sm w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Agendar Visita
            </button>
        </div>
    </div>

    <!-- Tabela Dinâmica -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative min-h-[400px]">
        <!-- Loader Overlays -->
        <div wire:loading class="absolute inset-0 bg-white/80 z-20 flex items-center justify-center backdrop-blur-sm">
            <div class="flex items-center gap-2 text-[var(--primary-color)] font-bold">
                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Sincronizando...
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[var(--bg-app)]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Data &
                            Hora Agendada</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Vítima
                            (Assistida)</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">
                            Viatura</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider">
                            Acões</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($visitas as $visita)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="bg-blue-50 text-blue-800 p-2 rounded-lg font-bold">
                                        {{ $visita->data_agendada ? $visita->data_agendada->format('d/m/Y') : 'Sem data' }}
                                    </div>
                                    <div
                                        class="bg-purple-50 text-[var(--primary-color)] p-2 rounded-lg font-black tracking-widest">
                                        {{ $visita->data_agendada ? $visita->data_agendada->format('H:i') : '' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">
                                    {{ optional($visita->acompanhamento->assistida)->nome }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">MPU:
                                    {{ optional($visita->acompanhamento)->numero_processo }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-700 bg-gray-100 inline-block px-2 py-1 rounded-md">
                                    {{ optional($visita->escala->viatura)->prefixo ?? 'Nenhuma' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($visita->status_visita == 'realizada')
                                    <span
                                        class="bg-[var(--status-success)] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                        Realizada
                                    </span>
                                @elseif($visita->status_visita == 'agendada')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-yellow-200">
                                        Agendada
                                    </span>
                                @elseif($visita->status_visita == 'em_deslocamento')
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-blue-200">
                                        Deslocamento
                                    </span>
                                @else
                                    <span
                                        class="bg-[var(--status-danger)] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                        Frustrada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-bold tracking-wide transition-colors">
                                    DETALHES
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                                Nenhum agendamento ou visita encontrado para os filtros atuais.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visitas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $visitas->links() }}
            </div>
        @endif
    </div>

    <!-- CALENDAR E AGENDAMENTO MODAL -->
    <div x-data="{ open: @entangle('isScheduling') }">
        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" wire:click="closeModals" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="open" x-transition.scale.origin.bottom class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full mt-10">
                    <form wire:submit.prevent="salvarAgendamento">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-t-4 border-[var(--primary-color)] rounded-t-lg">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2" id="modal-title">
                                <svg class="w-6 h-6 text-[var(--primary-color)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Despacho Tático (Calendário)
                            </h3>
                            
                            <div class="space-y-5">
                                <!-- Vítima (Prontuário) -->
                                <div class="relative z-50">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Qual Vítima será atendida?</label>
                                    <select wire:model="draftAcompanhamentoId" class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] shadow-sm font-medium" required>
                                        <option value="">Selecione um Prontuário Ativo...</option>
                                        @foreach($prontuariosAtivos as $pront)
                                            <option value="{{ $pront->id }}">{{ $pront->numero_processo }} - {{ optional($pront->assistida)->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('draftAcompanhamentoId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <!-- Dia (Calendario Nativo) -->
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Dia do Agendamento</label>
                                        <input type="date" wire:model.live="draftData" class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] text-lg font-black text-gray-700 shadow-sm transition-colors focus:bg-purple-50 hover:bg-white" required>
                                        @error('draftData') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <!-- Hora -->
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Hora Estimada</label>
                                        <input type="time" wire:model="draftHora" class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] text-lg font-black text-gray-700 shadow-sm" required>
                                        @error('draftHora') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Escalas Livres (Seletor Inteligente Atrelado à Data) -->
                                <div class="relative z-50">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">
                                        Selecione a Viatura / Turno Despachado
                                        <div wire:loading wire:target="draftData" class="inline-flex items-center text-xs text-[var(--primary-color)] ml-2">
                                            <svg class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procurando Escalas...
                                        </div>
                                    </label>
                                    
                                    @if(count($escalasDiaSelecionado) > 0)
                                        <select wire:model="draftEscalaId" class="w-full border-purple-300 bg-purple-50 rounded-md focus:ring-[var(--primary-color)] focus:border-purple-500 shadow-sm text-[var(--primary-color)] font-bold text-lg" required>
                                            <option value="">-- Escolha a Escala Ativa no Calendário --</option>
                                            @foreach($escalasDiaSelecionado as $escala)
                                                <option value="{{ $escala->id }}">
                                                    {{ $escala->turno }} | VTR {{ optional($escala->viatura)->prefixo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm font-medium">
                                            Não há nenhuma Escala Policial mapeada para este dia no Calendário. Cadastre uma escala no Painel Oficial.
                                        </div>
                                    @endif
                                    @error('draftEscalaId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 rounded-b-lg">
                            <button type="submit" @if(count($escalasDiaSelecionado) == 0) disabled class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white cursor-not-allowed sm:ml-3 sm:w-auto sm:text-sm" @else class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--primary-color)] text-base font-medium text-white hover:bg-purple-800 transition-colors sm:ml-3 sm:w-auto sm:text-sm" @endif>
                                Confirmar Agendamento Tático
                            </button>
                            <button type="button" wire:click="closeModals" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Fechar Calendário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>