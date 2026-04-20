<div class="space-y-6">
    <!-- Header e Controles -->
    <div
        class="flex flex-col md:flex-row justify-between md:items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[var(--primary-color)]">Gestão da Frota de Viaturas</h2>
            <p class="text-sm text-gray-500 mt-1">Cadastro, manutenção e ativação operacional da frota tática.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Filtro de Status -->
            <div class="relative w-full sm:w-48">
                <select wire:model.live="filtroStatus"
                    class="w-full py-2 pl-3 pr-8 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] text-gray-700 focus:border-transparent text-sm transition-all shadow-sm font-medium">
                    <option value="">Status: Todas</option>
                    <option value="OPERACIONAL">Operacionais</option>
                    <option value="MANUTENCAO">Em Manutenção</option>
                    <option value="BAIXADA">Baixadas (Inativas)</option>
                </select>
            </div>

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
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Prefixo ou Placa..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent text-sm transition-all shadow-sm">
            </div>

            <button type="button" wire:click="openCreateModal"
                class="shrink-0 inline-flex items-center justify-center bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:bg-purple-800 font-medium transition-all shadow-sm w-full sm:w-auto gap-2">
                Adicionar Viatura
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
                            <th class="px-6 py-4">Prefixo / Codinome</th>
                            <th class="px-6 py-4">Placa Oficial</th>
                            <th class="px-6 py-4">Sinal Técnico</th>
                            <th class="px-6 py-4 text-center">Disponibilidade</th>
                            <th class="px-6 py-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($viaturas as $viatura)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-black text-gray-900 text-lg">
                                    {{ $viatura->prefixo }}
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-[var(--primary-color)] tracking-widest">
                                    {{ $viatura->placa }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($viatura->status === 'OPERACIONAL')
                                        <span
                                            class="bg-[var(--status-success)] text-white px-2 py-1 rounded text-xs font-bold shadow-sm">OPERACIONAL</span>
                                    @elseif($viatura->status === 'MANUTENCAO')
                                        <span
                                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold shadow-sm">MANUTENÇÃO</span>
                                    @else
                                        <span
                                            class="bg-gray-500 text-white px-2 py-1 rounded text-xs font-bold shadow-sm">BAIXADA</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($viatura->is_ativo)
                                        <span
                                            class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2 py-1 rounded-md text-xs font-bold border border-green-200">
                                            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            ATIVO
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2 py-1 rounded-md text-xs font-bold border border-red-200">
                                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            DESATIVADO
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="openEditModal({{ $viatura->id }})"
                                        class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-md hover:bg-orange-600 shadow-sm transition-colors">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                                    Nenhuma viatura localizada na frota.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($viaturas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $viaturas->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ALPINE ENGINE MODAL -->
    <div x-data="{ open: @entangle('isEditing') }">
        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition.opacity
                    class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" wire:click="closeModals"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition.scale.origin.bottom
                    class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full mt-10">
                    <form wire:submit.prevent="salvar">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-t-4 border-[var(--primary-color)]">
                            <h3 class="text-xl font-bold text-gray-900 mb-4" id="modal-title">
                                {{ $viaturaId ? 'Editar Viatura' : 'Nova Viatura Tática' }}
                            </h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Prefixo (Ex:
                                            VTR-12)</label>
                                        <input type="text" wire:model="prefixo"
                                            class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] uppercase bg-white shadow-sm"
                                            required>
                                        @error('prefixo') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="relative z-50">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Cód. Placa</label>
                                        <input type="text" wire:model="placa"
                                            class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] uppercase font-mono bg-white shadow-sm"
                                            required>
                                        @error('placa') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="relative z-50">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Situação / Status
                                        Atual</label>
                                    <select wire:model="status"
                                        class="w-full border-gray-300 rounded-md focus:ring-[var(--primary-color)] bg-white shadow-sm"
                                        required>
                                        <option value="OPERACIONAL">OPERACIONAL (Pronta para Rota)</option>
                                        <option value="MANUTENCAO">MANUTENÇÃO (Oficina)</option>
                                        <option value="BAIXADA">BAIXADA (Inutilizada/Leilão)</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--primary-color)] text-base font-medium text-white hover:bg-purple-800 transition-colors sm:ml-3 sm:w-auto sm:text-sm">
                                Registrar Viatura
                            </button>
                            <button type="button" wire:click="closeModals"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>