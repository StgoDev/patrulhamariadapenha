<div class="space-y-6">
    <!-- Header e Controles (Search) -->
    <div class="flex flex-col md:flex-row justify-between md:items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[var(--primary-color)]">Base de Agressores</h2>
            <p class="text-sm text-gray-500 mt-1">Datatable assíncrono para gestão de autores com alerta FONAR.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Barra de Busca Assíncrona -->
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
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Buscar agressor por nome..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-hidden focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent text-sm transition-all shadow-sm">
            </div>

            <a href="{{ route('agressores.create') }}" wire:navigate class="shrink-0 inline-flex justify-center bg-[var(--primary-color)] text-white px-4 py-2 rounded-md hover:opacity-90 font-medium transition-opacity shadow-sm w-full sm:w-auto">
                Novo Agressor
            </a>
        </div>
    </div>

    <!-- Tabela Dinâmica -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden relative">
        <!-- Esmaecimento na Tabela Inteira durante Loading Genérico -->
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-nowrap text-left text-sm text-gray-500">
                    <thead class="bg-gray-50/75 border-b border-gray-200 text-gray-900 font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <!-- Sortable: ID -->
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('id')">
                                <span class="flex items-center gap-1">
                                    Cód
                                    @if($sortField === 'id')
                                        <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                    @endif
                                </span>
                            </th>
                            
                            <!-- Sortable: Nome -->
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('nome')">
                                <span class="flex items-center gap-1">
                                    Nome Completo
                                    @if($sortField === 'nome')
                                        <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                    @endif
                                </span>
                            </th>

                            <!-- Não Sortable: Alert FONAR -->
                            <th scope="col" class="px-6 py-4">Alertas de Risco</th>

                            <!-- Sortable: Created At -->
                            <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('created_at')">
                                <span class="flex items-center gap-1">
                                    Data de Entrada
                                    @if($sortField === 'created_at')
                                        <span>{!! $sortDirection === 'asc' ? '↑' : '↓' !!}</span>
                                    @endif
                                </span>
                            </th>

                            <!-- Controles -->
                            <th scope="col" class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($agressores as $agressor)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                                    #{{ substr($agressor->id, 0, 8) }}
                                </td>
                                
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $agressor->nome }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if($agressor->possui_arma_fogo)
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            ⚠️ Arma de Fogo
                                        </span>
                                    @elseif($agressor->preso)
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                            🔗 Encarcerado
                                        </span>
                                    @else
                                        <span class="text-gray-400 font-medium italic text-xs">Apurando</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $agressor->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-md hover:bg-blue-700 shadow-sm transition-colors">Detalhes</button>
                                    <button class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-md hover:bg-orange-600 shadow-sm transition-colors">Editar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="font-medium">Nenhum agressor mapeado na base atual.</p>
                                        @if(!empty($search))
                                            <p class="text-sm mt-1 text-gray-400">Não encontramos resultados para "{{ $search }}".</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginação Nativa com Tracking de URL -->
    @if($agressores->hasPages())
    <div class="mt-4 pb-4">
        {{ $agressores->links() }}
    </div>
    @endif
</div>
