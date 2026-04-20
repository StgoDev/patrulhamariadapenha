<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('acompanhamentos.index') }}" wire:navigate class="text-gray-400 hover:text-[var(--primary-color)] transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-[var(--text-main)]">Geração de Prontuário Administrativo</h2>
        </div>

        <form wire:submit="save" class="space-y-6">
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                <h3 class="font-bold text-[var(--primary-color)] mb-4">Vínculos Restritos (Requeridos)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Selecao da Vítima -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Vítima (Assistida) vinculada <span class="text-red-500">*</span></label>
                        <select wire:model="assistida_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" required>
                            <option value="">Selecione a Vítima...</option>
                            @foreach($assistidas as $as)
                                <option value="{{ $as->id }}">{{ $as->nome }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Apenas vítimas pré-cadastradas (<a href="{{ route('assistidas.create') }}" class="text-[var(--primary-color)] hover:underline" wire:navigate>Clique aqui para cadastrar</a>)
                        </p>
                        @error('assistida_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Selecao do Autor -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Autor (Agressor) vinculado <span class="text-red-500">*</span></label>
                        <select wire:model="agressor_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" required>
                            <option value="">Selecione o Autor...</option>
                            @foreach($agressores as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->nome }} ({{ $ag->possui_arma_fogo ? 'Armado' : 'Sem restrição de arma' }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Apenas autores pré-cadastrados (<a href="{{ route('agressores.create') }}" class="text-[var(--primary-color)] hover:underline" wire:navigate>Clique aqui para cadastrar</a>)
                        </p>
                        @error('agressor_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Numero de Processo -->
                <div class="col-span-1 lg:col-span-2">
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Nº do Processo / B.O. / MPU <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="numero_processo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Ex: 0001234-56.2024.8.XX.XXXX" required>
                    @error('numero_processo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Ano -->
                <div>
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Ano Base</label>
                    <input type="number" wire:model="ano_processo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm">
                    @error('ano_processo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Origem Encaminhamento -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Origem do Encaminhamento</label>
                    <input type="text" wire:model="origem_encaminhamento" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Ex: Delegacia da Mulher, Fórum, Patrulha Maria da Penha">
                    @error('origem_encaminhamento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Grau de Parentesco -->
                <div>
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Grau de Relacionamento</label>
                    <input type="text" wire:model="grau_parentesco" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Ex: Ex-marido, Vizinho, Padrasto">
                    @error('grau_parentesco') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-5 border-t border-gray-100">
                <button type="submit" class="bg-[var(--primary-color)] text-white px-6 py-2 rounded-md hover:opacity-90 font-medium transition-opacity flex items-center gap-2">
                    <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Aprovar Geração de Prontuário
                </button>
            </div>
        </form>
    </div>
</div>
