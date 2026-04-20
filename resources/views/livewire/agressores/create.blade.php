<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('agressores.index') }}" wire:navigate class="text-gray-400 hover:text-[var(--primary-color)] transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-[var(--text-main)]">Cadastrar Novo Agressor</h2>
        </div>

        <form wire:submit="save" class="space-y-6">
            <!-- Nome -->
            <div>
                <label class="block text-sm font-medium text-[var(--text-main)] mb-1">Nome Completo <span class="text-red-500">*</span></label>
                <input type="text" wire:model="nome" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] text-sm" placeholder="Nome do autor" required>
                @error('nome') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Checks de Risco -->
            <div class="bg-red-50 p-4 rounded border border-red-100 space-y-3">
                <h4 class="font-semibold text-red-800 text-sm mb-2">Sinalizadores de Risco:</h4>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="preso" id="preso" class="rounded border-gray-300 text-[var(--primary-color)] shadow-sm focus:ring-[var(--primary-color)]">
                    <label for="preso" class="ml-2 block text-sm text-[var(--text-main)] font-medium">O autor já se encontra preso (Sistema Prisional)?</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="possui_arma_fogo" id="arma" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                    <label for="arma" class="ml-2 block text-sm text-red-800 font-bold">ALERTA FONAR: Possui arma de fogo ou registro armamentista ativo?</label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[var(--primary-color)] text-white px-6 py-2 rounded-md hover:opacity-90 font-medium transition-opacity flex items-center gap-2">
                    <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Cadastrar
                </button>
            </div>
        </form>
    </div>
</div>
