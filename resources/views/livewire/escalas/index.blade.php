<div class="space-y-6">
    <!-- Header e Controles -->
    <div
        class="flex flex-col md:flex-row justify-between md:items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[var(--primary-color)]">Comando Tático: Escalas</h2>
            <p class="text-sm text-gray-500 mt-1">Gestão de viaturas, turnos e guarnições despachadas.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Barra de Busca -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg wire:loading.remove wire:target="search" class="h-5 w-5 text-gray-400 transition-opacity"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-[var(--primary-color)]"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Buscar VTR (Prefixo)..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent text-sm transition-all shadow-sm">
            </div>

            <button type="button" wire:click="openCreateModal"
                class="shrink-0 inline-flex items-center justify-center bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:bg-purple-800 font-medium transition-all shadow-sm w-full sm:w-auto gap-2">
                Adicionar Escala
            </button>
        </div>
    </div>

    <!-- Tabela Dinâmica -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden relative min-h-[400px]">
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-nowrap text-left text-sm text-gray-500">
                    <thead
                        class="bg-[var(--bg-app)] border-b border-gray-200 text-gray-900 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-4">Data Oficial</th>
                            <th class="px-6 py-4">Horário / Turno</th>
                            <th class="px-6 py-4">Viatura (VTR)</th>
                            <th class="px-6 py-4">Guarnição Alocada</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($escalas as $escala)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $escala->data_escala ? $escala->data_escala->format('d/m/Y') : '' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-gray-100 text-gray-800 font-bold px-2 py-1 rounded text-xs">{{ $escala->turno }}h ({{ $escala->inicio ? \Carbon\Carbon::parse($escala->inicio)->format('H:i') : '' }} às {{ $escala->termino ? \Carbon\Carbon::parse($escala->termino)->format('H:i') : '' }})</span>
                                </td>
                                <td class="px-6 py-4 font-black">
                                    {{ optional($escala->viatura)->prefixo }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 text-xs">
                                        @foreach($escala->funcionarios as $func)
                                            <div>
                                                <span
                                                    class="font-bold text-[var(--primary-color)]">{{ $func->pivot->papel_patrulha }}:</span>
                                                {{ $func->nome_guerra }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($escala->status === 'ATIVA')
                                        <span class="bg-[var(--status-success)]/10 text-[var(--status-success)] font-bold px-2 py-1 rounded text-xs text-center border mx-auto">ATIVO</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 font-bold px-2 py-1 rounded text-xs text-center mx-auto">ENCERRADO</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="openEditModal({{ $escala->id }})" class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-md hover:bg-orange-600 shadow-sm transition-colors uppercase tracking-wider">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                    Nenhuma escala militar ativa neste dia ou com esse prefixo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($escalas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $escalas->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ALPINE ENGINE MODAL -->
    <div x-data="{ open: @entangle('isEditing') }">
        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-modal-overlay transition-opacity"
                    wire:click="closeModals" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition.scale.origin.bottom
                    class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full mt-10">
                    <form wire:submit.prevent="salvar">
                        <div
                            class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-t-4 border-[var(--primary-color)] rounded-t-lg">
                            <h3 class="text-xl font-bold text-gray-900 mb-4" id="modal-title">Designar Serviço
                                Operacional</h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Data da Escala</label>
                                        <input type="date" wire:model="data_escala"
                                            class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] relative z-50 bg-white shadow-sm"
                                            required>
                                        @error('data_escala') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Início do Turno</label>
                                        <input type="time" wire:model="inicio"
                                            class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] relative z-50 bg-white shadow-sm"
                                            required>
                                        @error('inicio') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Duração (Turno)</label>
                                        <select wire:model="turno"
                                            class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] relative z-50 bg-white shadow-sm"
                                            required>
                                            <option value="6">6 Horas</option>
                                            <option value="8">8 Horas</option>
                                            <option value="12">12 Horas</option>
                                            <option value="18">18 Horas</option>
                                            <option value="24">24 Horas</option>
                                        </select>
                                        @error('turno') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="relative z-50">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Viatura Designada
                                        (VTR)</label>
                                    <select wire:model="viatura_id"
                                        class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] relative z-50 bg-white shadow-sm"
                                        required>
                                        <option value="">Selecione a VTR</option>
                                        @foreach($viaturas as $vtr)
                                            <option value="{{ $vtr->id }}">{{ $vtr->prefixo }} - {{ $vtr->placa }}</option>
                                        @endforeach
                                    </select>
                                    @error('viatura_id') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr class="my-4 border-gray-100">

                                <div class="bg-gray-50 p-4 rounded-lg space-y-3 border border-gray-100">
                                    <h4 class="text-sm font-black text-[var(--primary-color)] uppercase tracking-wide">
                                        Formação da Guarnição</h4>

                                    <div class="relative z-[60]" x-data="{
                                        open: false,
                                        search: '',
                                        selectedId: @entangle('comandante_id'),
                                        options: [
                                            @foreach($policiais as $p)
                                            { id: {{ $p->id }}, text: `{{ $p->nome }} (CPF: {{ $p->cpf }})` },
                                            @endforeach
                                        ],
                                        get filtered() {
                                            if (this.search === '') return this.options;
                                            return this.options.filter(i => i.text.toLowerCase().includes(this.search.toLowerCase()));
                                        },
                                        get selectedText() {
                                            let f = this.options.find(i => i.id == this.selectedId);
                                            return f ? f.text : 'Selecionar Comandante (Busca Inteligente)';
                                        }
                                    }" @click.away="open = false; search = ''">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Comandante da VTR</label>
                                        <button type="button" @click="open = !open" class="w-full text-left px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-1 focus:ring-[var(--primary-color)] text-sm shadow-sm bg-white flex justify-between items-center transition-all">
                                            <span x-text="selectedText" :class="selectedId ? 'text-[var(--primary-color)] font-bold' : 'text-gray-500'"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="open" style="display: none;" class="absolute w-full mt-1 bg-white border border-gray-200 rounded-md shadow-2xl z-[100] flex flex-col">
                                            <div class="p-2 border-b border-gray-100 flex-shrink-0 bg-gray-50 rounded-t-md">
                                                <input type="text" x-model="search" placeholder="Buscar por Patente, Nome ou Matrícula..." class="w-full px-3 py-1.5 border border-gray-300 rounded text-xs focus:ring-[var(--primary-color)] font-medium text-gray-700">
                                            </div>
                                            <ul class="overflow-y-auto flex-1 p-1 max-h-48">
                                                <template x-for="opt in filtered" :key="opt.id">
                                                    <li @click="selectedId = opt.id; open = false; search = ''" class="px-3 py-2 hover:bg-[var(--primary-color)] hover:text-white cursor-pointer text-xs font-bold rounded-md transition-colors" :class="selectedId === opt.id ? 'bg-purple-100 text-[var(--primary-color)]' : 'text-gray-700'">
                                                        <span x-text="opt.text"></span>
                                                    </li>
                                                </template>
                                                <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-500 text-center">Nenhum oficial encontrado.</li>
                                            </ul>
                                        </div>
                                        @error('comandante_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="relative z-[50]" x-data="{
                                        open: false,
                                        search: '',
                                        selectedId: @entangle('motorista_id'),
                                        options: [
                                            @foreach($policiais as $p)
                                            { id: {{ $p->id }}, text: `{{ $p->nome }} (CPF: {{ $p->cpf }})` },
                                            @endforeach
                                        ],
                                        get filtered() {
                                            if (this.search === '') return this.options;
                                            return this.options.filter(i => i.text.toLowerCase().includes(this.search.toLowerCase()));
                                        },
                                        get selectedText() {
                                            let f = this.options.find(i => i.id == this.selectedId);
                                            return f ? f.text : 'Selecionar Motorista (Busca Inteligente)';
                                        }
                                    }" @click.away="open = false; search = ''">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Motorista da VTR</label>
                                        <button type="button" @click="open = !open" class="w-full text-left px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-1 focus:ring-[var(--primary-color)] text-sm shadow-sm bg-white flex justify-between items-center transition-all">
                                            <span x-text="selectedText" :class="selectedId ? 'text-[var(--primary-color)] font-bold' : 'text-gray-500'"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="open" style="display: none;" class="absolute w-full mt-1 bg-white border border-gray-200 rounded-md shadow-2xl z-[100] flex flex-col">
                                            <div class="p-2 border-b border-gray-100 flex-shrink-0 bg-gray-50 rounded-t-md">
                                                <input type="text" x-model="search" placeholder="Buscar por Patente, Nome ou Matrícula..." class="w-full px-3 py-1.5 border border-gray-300 rounded text-xs focus:ring-[var(--primary-color)] font-medium text-gray-700">
                                            </div>
                                            <ul class="overflow-y-auto flex-1 p-1 max-h-48">
                                                <template x-for="opt in filtered" :key="opt.id">
                                                    <li @click="selectedId = opt.id; open = false; search = ''" class="px-3 py-2 hover:bg-[var(--primary-color)] hover:text-white cursor-pointer text-xs font-bold rounded-md transition-colors" :class="selectedId === opt.id ? 'bg-purple-100 text-[var(--primary-color)]' : 'text-gray-700'">
                                                        <span x-text="opt.text"></span>
                                                    </li>
                                                </template>
                                                <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-500 text-center">Nenhum oficial encontrado.</li>
                                            </ul>
                                        </div>
                                        @error('motorista_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="relative z-[40]" x-data="{
                                        open: false,
                                        search: '',
                                        selectedId: @entangle('patrulheiro_id'),
                                        options: [
                                            @foreach($policiais as $p)
                                            { id: {{ $p->id }}, text: `{{ $p->nome }} (CPF: {{ $p->cpf }})` },
                                            @endforeach
                                        ],
                                        get filtered() {
                                            if (this.search === '') return this.options;
                                            return this.options.filter(i => i.text.toLowerCase().includes(this.search.toLowerCase()));
                                        },
                                        get selectedText() {
                                            let f = this.options.find(i => i.id == this.selectedId);
                                            return f ? f.text : 'Sem Patrulheiro (Vazio)';
                                        }
                                    }" @click.away="open = false; search = ''">
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Patrulheiro 3 (Opcional)</label>
                                        <button type="button" @click="open = !open" class="w-full text-left px-3 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-1 focus:ring-[var(--primary-color)] text-sm shadow-sm bg-white flex justify-between items-center transition-all">
                                            <span x-text="selectedText" :class="selectedId ? 'text-[var(--primary-color)] font-bold' : 'text-gray-500'"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="open" style="display: none;" class="absolute w-full mt-1 bg-white border border-gray-200 rounded-md shadow-2xl z-[100] flex flex-col">
                                            <div class="p-2 border-b border-gray-100 flex-shrink-0 bg-gray-50 rounded-t-md">
                                                <input type="text" x-model="search" placeholder="Buscar por Patente, Nome ou Matrícula..." class="w-full px-3 py-1.5 border border-gray-300 rounded text-xs focus:ring-[var(--primary-color)] font-medium text-gray-700">
                                            </div>
                                            <ul class="overflow-y-auto flex-1 p-1 max-h-48">
                                                <li @click="selectedId = ''; open = false; search = ''" class="px-3 py-2 hover:bg-[var(--primary-color)] hover:text-white cursor-pointer text-xs font-bold rounded-md transition-colors text-red-500 pb-2 border-b border-gray-100 mb-1">
                                                    (Limpar) Sem Patrulheiro
                                                </li>
                                                <template x-for="opt in filtered" :key="opt.id">
                                                    <li @click="selectedId = opt.id; open = false; search = ''" class="px-3 py-2 hover:bg-[var(--primary-color)] hover:text-white cursor-pointer text-xs font-bold rounded-md transition-colors" :class="selectedId === opt.id ? 'bg-purple-100 text-[var(--primary-color)]' : 'text-gray-700'">
                                                        <span x-text="opt.text"></span>
                                                    </li>
                                                </template>
                                                <li x-show="filtered.length === 0" class="px-3 py-2 text-xs text-gray-500 text-center">Nenhum oficial encontrado.</li>
                                            </ul>
                                        </div>
                                        @error('patrulheiro_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div
                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg border-t border-gray-100">
                            <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--primary-color)] text-base font-medium text-white hover:bg-purple-800 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)] sm:ml-3 sm:w-auto sm:text-sm">
                                Confirmar Escala
                            </button>
                            <button type="button" wire:click="closeModals"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>