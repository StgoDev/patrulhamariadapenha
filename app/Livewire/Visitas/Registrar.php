<?php

namespace App\Livewire\Visitas;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Visita;
use App\Models\Acompanhamento;
use App\Models\Escala;
use App\Models\Viatura;
use App\Services\VisitaService;
use App\Livewire\Forms\VisitaForm;

#[Layout('layouts.app')]
class Registrar extends Component
{
    public VisitaForm $form;
    
    public $step = 1; // 1: Lista de Agendamentos, 2: Inicio Ocorrencia, 3: Form Wizard
    public $abaAtiva = 'A'; // A, B, C

    public $agendamentos = [];
    public $visitaSelecionada = null;

    public $escalaAtivaId;

    public function mount()
    {
        // 1. Modo Tático Isolado: Injeta a escala da VTR automaticamente
        $viatura = Viatura::firstOrCreate(['prefixo' => 'VTR-MARIA-01'], ['placa' => 'PMP-190']);
        $escala = Escala::firstOrCreate(['data_escala' => now()->toDateString(), 'turno' => 'MANHA'], ['viatura_id' => $viatura->id]);
        $this->escalaAtivaId = $escala->id;

        // 2. Falso Despacho: Cria agendamentos de teste se a viatura estiver sub-utilizada
        if (Visita::where('escala_id', $this->escalaAtivaId)->count() === 0) {
            $acompanhamento = Acompanhamento::first();
            if ($acompanhamento) {
                Visita::create([
                    'escala_id' => $this->escalaAtivaId,
                    'acompanhamento_id' => $acompanhamento->id,
                    'status_visita' => 'agendada',
                    'data_agendada' => now()->addMinutes(30)
                ]);
            }
        }

        $this->carregarAgendamentos();
    }

    public function carregarAgendamentos()
    {
        // Traz apenas visitas atreladas a tropa atual que não foram marcadas como finalizadas
        $this->agendamentos = Visita::with(['acompanhamento.assistida', 'acompanhamento.agressor'])
            ->where('escala_id', $this->escalaAtivaId)
            ->whereIn('status_visita', ['agendada', 'em_deslocamento'])
            ->orderBy('data_agendada', 'asc')
            ->get();
    }

    public function selecionarVisita($id, VisitaService $service)
    {
        $this->visitaSelecionada = Visita::with(['acompanhamento.assistida', 'acompanhamento.agressor'])->find($id);
        
        // Operacional: Aciona rastreamento/status de deslocamento ao clicar.
        if ($this->visitaSelecionada->status_visita === 'agendada') {
            $service->iniciarDeslocamento($this->visitaSelecionada);
        }

        $this->step = 2; // Tela de Prontidão da Ocorrência
    }

    public function iniciarPreenchimento()
    {
        $this->step = 3;
        $this->abaAtiva = 'A';
    }

    public function trocarAba($aba)
    {
        $this->abaAtiva = $aba;
    }

    public function abortar()
    {
        $this->step = 1;
        $this->visitaSelecionada = null;
        $this->form->reset();
        $this->carregarAgendamentos();
    }

    public function salvar(VisitaService $service)
    {
        // Purificador Semântico HTML
        $this->form->sanitize(); 
        
        // Dispara Requests Rules e Exceções Form Request (Livewire)
        $this->form->validate();

        try {
            $service->registrarRetornoPatrulha($this->visitaSelecionada, [
                'tipo_monitoramento' => $this->form->tipo_monitoramento,
                'resumo_atendimento' => $this->form->resumo_atendimento,
                'avaliacao_equipe' => $this->form->avaliacao_equipe,
                'percepcao_assistida' => $this->form->percepcao_assistida,
                'descumprimento_mpu' => $this->form->descumprimento_mpu,
            ]);

            $this->dispatch('alerta', [
                'title' => 'Criptografia Finalizada!',
                'text' => 'O Anexo III foi fechado rigidamente e transmitido de forma encriptada.',
                'icon' => 'success'
            ]);

            $this->abortar();
            
        } catch (\Exception $e) {
            $this->dispatch('alerta', [
                'title' => 'Erro de Auditoria',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.visitas.registrar');
    }
}
