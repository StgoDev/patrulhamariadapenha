<?php

namespace App\Livewire\Visitas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Visita;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $busca = '';
    public $filtroStatus = '';

    // Modal Agendamento Properties
    public $isScheduling = false;
    public $draftData = '';
    public $draftHora = '';
    public $draftAcompanhamentoId = '';
    public $draftEscalaId = '';

    public $escalasDiaSelecionado = [];

    public function mount()
    {
        $this->draftData = \Carbon\Carbon::now()->format('Y-m-d');
    }

    public function updatingBusca()
    {
        $this->resetPage();
    }

    public function updatingFiltroStatus()
    {
        $this->resetPage();
    }

    public function updatedDraftData($value)
    {
        if ($value) {
            $this->escalasDiaSelecionado = \App\Models\Escala::with('viatura')->where('data_escala', $value)->where('status', 'ATIVA')->get();
        } else {
            $this->escalasDiaSelecionado = [];
        }
    }

    public function openScheduleModal()
    {
        $this->resetValidation();
        $this->draftData = \Carbon\Carbon::now()->format('Y-m-d');
        $this->draftHora = \Carbon\Carbon::now()->addHours(1)->format('H:i');
        $this->draftAcompanhamentoId = '';
        $this->draftEscalaId = '';
        $this->updatedDraftData($this->draftData);
        $this->isScheduling = true;
    }

    public function closeModals()
    {
        $this->isScheduling = false;
    }

    public function salvarAgendamento()
    {
        $this->validate([
            'draftData' => 'required|date',
            'draftHora' => 'required',
            'draftAcompanhamentoId' => 'required|exists:acompanhamentos,id',
            'draftEscalaId' => 'required|exists:escalas,id',
        ]);

        $dateTime = \Carbon\Carbon::parse($this->draftData . ' ' . $this->draftHora);

        Visita::create([
            'acompanhamento_id' => $this->draftAcompanhamentoId,
            'escala_id' => $this->draftEscalaId,
            'data_agendada' => $dateTime,
            'status_visita' => 'agendada'
        ]);

        $this->closeModals();
        $this->dispatch('alerta', [
            'title' => 'Despacho Tático Confirmado',
            'text' => 'A Visita foi inserida no roteiro da Viatura para a data agendada.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        $query = Visita::with(['acompanhamento.assistida', 'escala.viatura']);

        if (!empty($this->filtroStatus)) {
            $query->where('status_visita', $this->filtroStatus);
        }

        if (!empty($this->busca)) {
            $query->where(function ($q) {
                $q->whereHas('acompanhamento.assistida', function ($subQ) {
                    $subQ->where('nome', 'like', '%' . $this->busca . '%');
                })
                ->orWhereHas('escala.viatura', function ($subQ) {
                    $subQ->where('prefixo', 'like', '%' . $this->busca . '%');
                });
            });
        }

        $visitas = $query->orderBy('data_agendada', 'asc')->paginate(10);
        $prontuariosAtivos = \App\Models\Acompanhamento::with('assistida')->where('situacao', 'ATIVA')->get();

        return view('livewire.visitas.index', [
            'visitas' => $visitas,
            'prontuariosAtivos' => $prontuariosAtivos
        ]);
    }
}
