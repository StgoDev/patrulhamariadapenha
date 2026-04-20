<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('assistidas.index') }}" wire:navigate class="text-gray-400 hover:text-[var(--primary-color)] transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-[var(--text-main)]">Cadastrar Nova Vítima</h2>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Nome Completo <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nome" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Nome da vítima" required>
                    @error('nome') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Zona/Região -->
                <div>
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Zona/Região</label>
                    <input type="text" wire:model="zona_regiao" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Ex: Zona Norte, Centro">
                    @error('zona_regiao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Telefone -->
                <div>
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Telefone de Contato (Seguro)</label>
                    <input type="text" wire:model="telefone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="(00) 00000-0000">
                    <p class="text-xs text-gray-400 mt-1">Este dado será criptografado no banco.</p>
                    @error('telefone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Endereco -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Endereço Completo</label>
                    <input type="text" wire:model="endereco" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Rua, Número, Bairro, Cidade">
                    <p class="text-xs text-gray-400 mt-1">Este dado será criptografado no banco de dados para segurança da vítima.</p>
                    @error('endereco') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Data Nascimento -->
                <div>
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Data de Nascimento</label>
                    <input type="date" wire:model="data_nascimento" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm">
                    @error('data_nascimento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Raca/Cor -->
                <div>
                    <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Raça/Cor (IBGE)</label>
                    <select wire:model="raca_cor" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm">
                        <option value="">Selecione...</option>
                        <option value="Branca">Branca</option>
                        <option value="Preta">Preta</option>
                        <option value="Parda">Parda</option>
                        <option value="Amarela">Amarela</option>
                        <option value="Indígena">Indígena</option>
                        <option value="Não Informado">Não Informado</option>
                    </select>
                    @error('raca_cor') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Deficiencia -->
                <div class="col-span-1 md:col-span-2 flex items-center">
                    <input type="checkbox" wire:model="possui_deficiencia" id="deficiencia" class="rounded border-gray-300 text-[var(--primary-color)] shadow-sm focus:ring-[var(--primary-color)]">
                    <label for="deficiencia" class="ml-2 block text-sm text-[var(--text-main)]">A vítima possui alguma deficiência (PCD)?</label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[var(--primary-color)] text-white px-6 py-2 rounded-md hover:opacity-90 font-medium transition-opacity flex items-center gap-2">
                    <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Salvar Registro
                </button>
            </div>
        </form>
    </div>
</div>
