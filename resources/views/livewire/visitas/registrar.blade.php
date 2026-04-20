<div class="max-w-3xl mx-auto space-y-6 pb-20">
    <!-- Cabeçalho Tático -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-[var(--primary-color)]"></div>
        <div class="flex items-center gap-4">
            <div class="bg-[var(--bg-app)] p-3 rounded-full shadow-inner border border-gray-100">
                <svg class="w-8 h-8 text-[var(--primary-color)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Ocorrências Despachadas</h1>
                <p class="text-sm font-medium text-gray-500">Terminal Móvel da Guarnição</p>
            </div>
        </div>
    </div>

    <!-- WIZARD STEP 1: Agendamentos Ativos da Escala -->
    @if($step == 1)
    <div class="bg-white shadow-md rounded-2xl border border-gray-100 p-6 sm:p-8 animate-fade-in-up">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Agendamentos em Aberto</h2>
        
        <div class="space-y-4">
            @forelse($agendamentos as $visita)
                <button wire:click="selecionarVisita({{ $visita->id }})" class="w-full text-left bg-white border border-gray-200 hover:border-purple-300 hover:bg-purple-50 active:bg-purple-100 p-5 rounded-2xl transition-all shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded uppercase">AGENDADA</span>
                            @if($visita->data_agendada)
                                <span class="text-xs text-gray-500 font-semibold">{{ $visita->data_agendada->format('H:i') }}</span>
                            @endif
                        </div>
                        <h3 class="text-xl font-black text-[var(--primary-color)] group-hover:text-purple-900">
                            {{ $visita->acompanhamento->assistida->nome }}
                        </h3>
                        <p class="text-sm font-medium text-gray-600 mt-1">Decisão Judicial: {{ $visita->acompanhamento->numero_processo }}</p>
                    </div>
                    
                    <div class="shrink-0 bg-white shadow-sm border border-gray-200 p-2 rounded-full group-hover:bg-[var(--primary-color)] group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            @empty
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <h3 class="text-lg font-bold text-gray-500 object-center">Tropa Pronta!</h3>
                    <p class="text-sm text-gray-400">Nenhuma visita pendente de despacho para esta guarnição no momento.</p>
                </div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- WIZARD STEP 2: Conscientização e Início -->
    @if($step == 2)
    <div class="space-y-6 animate-fade-in-up">
        <div class="bg-[var(--primary-color)] rounded-2xl shadow-lg border border-purple-900 overflow-hidden relative">
            <div class="absolute right-0 top-0 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            
            <div class="p-6 relative z-10 flex flex-col gap-2 border-b border-white/10">
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-blue-500 text-white text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-inner animate-pulse">
                        EM DESLOCAMENTO
                    </span>
                    <button wire:click="abortar" class="text-white/70 hover:text-white underline text-sm transition-colors">Voltar à Tropa</button>
                </div>
                
                <p class="text-purple-200 text-xs font-bold uppercase tracking-wider">Atenção Extrema: Vítima Protegida</p>
                <h2 class="text-3xl font-black text-white">{{ $visitaSelecionada->acompanhamento->assistida->nome }}</h2>
            </div>
            
            <div class="p-6 relative z-10 bg-black/20">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-purple-300 uppercase font-bold tracking-wider">Nome do Agressor Restrito</p>
                        <p class="text-lg text-white font-medium">{{ $visitaSelecionada->acompanhamento->agressor->nome }}</p>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-white/10">
                    <button wire:click="iniciarPreenchimento" class="w-full bg-white text-[var(--primary-color)] font-black text-lg py-4 rounded-xl shadow-xl hover:bg-gray-100 active:scale-95 transition-all text-center">
                        PREENCHER RELATÓRIO DO LOCAL
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- WIZARD STEP 3: Formulário de Conclusão (Com Abas) -->
    @if($step == 3)
    <div class="bg-white shadow-md rounded-2xl border border-gray-100 overflow-hidden animate-fade-in-up">
        
        <!-- Navegação Wizard Mobile -->
        <div class="flex border-b border-gray-200">
            <button wire:click="trocarAba('A')" class="flex-1 text-center py-4 text-sm font-bold border-b-2 transition-colors {{ $abaAtiva == 'A' ? 'border-[var(--primary-color)] text-[var(--primary-color)]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                <span>1. Tático</span>
            </button>
            <button wire:click="trocarAba('B')" class="flex-1 text-center py-4 text-sm font-bold border-b-2 transition-colors {{ $abaAtiva == 'B' ? 'border-[var(--primary-color)] text-[var(--primary-color)]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                <span>2. Percepções</span>
            </button>
            <button wire:click="trocarAba('C')" class="flex-1 text-center py-4 text-sm font-bold border-b-2 transition-colors {{ $abaAtiva == 'C' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                <span>3. Risco Judicial</span>
            </button>
        </div>

        <div class="p-6 sm:p-8">
            <!-- ABA A -->
            <div class="{{ $abaAtiva == 'A' ? 'block animate-fade-in-up' : 'hidden' }} space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Tipo de Abordagem MPU</label>
                    <select wire:model="form.tipo_monitoramento" class="bg-gray-50 border border-gray-200 text-gray-900 text-base rounded-xl focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-4">
                        <option value="">Selecione...</option>
                        <option value="visita">Visita Domiciliar (Presencial)</option>
                        <option value="ronda">Ronda Sem Contato Físico / Vizualização</option>
                        <option value="contato_telefonico">Contato e Diligência Telefônica</option>
                    </select>
                    @error('form.tipo_monitoramento') <span class="text-red-500 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Resumo Descritivo (Patrulha)</label>
                    <textarea wire:model="form.resumo_atendimento" rows="5" class="bg-gray-50 border border-gray-200 text-gray-900 text-base rounded-xl focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-4 resize-y" placeholder="Criptografia ativada. Descreva abertamente a dinâmica do encontro..."></textarea>
                    @error('form.resumo_atendimento') <span class="text-red-500 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- ABA B -->
            <div class="{{ $abaAtiva == 'B' ? 'block animate-fade-in-up' : 'hidden' }} space-y-6">
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Percepção Tática da Equipe Policial</label>
                    <textarea wire:model="form.avaliacao_equipe" rows="4" class="bg-gray-50 border border-gray-200 text-gray-900 text-base rounded-xl focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-4 resize-y" placeholder="Há tensão evidente? Portas arrombadas? Vizinhos contaram algo? Observações Policiais cruas..."></textarea>
                    @error('form.avaliacao_equipe') <span class="text-red-500 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Fala/Percepção de Segurança da Assistida</label>
                    <textarea wire:model="form.percepcao_assistida" rows="4" class="bg-gray-50 border border-gray-200 text-gray-900 text-base rounded-xl focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-4 resize-y" placeholder="O que a vítima narrou de mais grave? Ela relata proximidade do indivíduo? ..."></textarea>
                    @error('form.percepcao_assistida') <span class="text-red-500 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- ABA C -->
            <div class="{{ $abaAtiva == 'C' ? 'block animate-fade-in-up' : 'hidden' }} space-y-6">
                <div class="bg-yellow-50 p-5 rounded-xl border border-yellow-200 mb-6">
                    <p class="text-yellow-800 text-sm font-semibold">Toda a documentação final preenchida pelas abas anteriores passará por higienização cibernética via Regex e será salva com AES 256 bits nas chaves do Tribunal. Revise os textos antes de transmitir.</p>
                </div>
                
                <label class="relative inline-flex flex-col sm:flex-row items-start sm:items-center cursor-pointer bg-red-50 hover:bg-red-100 border-2 border-red-200 p-5 rounded-2xl w-full transition-colors group">
                    <div class="flex items-center w-full">
                        <input type="checkbox" wire:model="form.descumprimento_mpu" class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[24px] after:left-[24px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-600 flex-shrink-0"></div>
                        <div class="ml-4 flex flex-col">
                            <span class="text-base font-black text-red-800 uppercase">Atestar Quebra de M. P. U.</span>
                            <span class="text-xs text-red-600 font-medium">Aciona Gatilho Judicial (Crime em Andamento/Cometido)</span>
                        </div>
                    </div>
                </label>

                <div class="mt-8 flex flex-col gap-3">
                    <button wire:click="salvar" class="w-full px-8 py-4 bg-[var(--primary-color)] hover:bg-purple-800 text-white font-black text-lg rounded-xl shadow-xl transition-all active:scale-95 text-center flex justify-center items-center gap-2">
                        <span wire:loading.remove wire:target="salvar">ASSINAR E FECHAR RELATÓRIO</span>
                        <span wire:loading wire:target="salvar">Criptografando...</span>
                        <svg wire:loading.remove wire:target="salvar" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </button>
                    <button wire:click="abortar" class="w-full px-8 py-4 bg-gray-100 text-gray-500 hover:bg-gray-200 font-bold text-sm rounded-xl transition-colors">
                        Cancelar e Arquivar Modificações
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif
    
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>
