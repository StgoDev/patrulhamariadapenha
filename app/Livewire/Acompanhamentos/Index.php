<?php

namespace App\Livewire\Acompanhamentos;

use App\Models\Acompanhamento;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'order')]
    public $sortField = 'created_at';

    #[Url(as: 'dir')]
    public $sortDirection = 'desc';

    #[Url(as: 'status')]
    public $status = '';

    // Variáveis da Modal e Edição
    public $isEditing = false;
    public $isViewing = false;
    public $prontuarioId;
    public array $prontuarioDetalhado = []; // Isolamento contra falhas de Synthesizer

    // Campos a serem editados
    public $editOrigem;
    public $editSituacao;
    public $editNivelRisco;

    public function openView($id)
    {
        $this->dispatch('console-log', 'CHEGOU NO PHP BEM-SUCEDIDO. Buscando Prontuário ID: ' . $id);

        $prontuario = Acompanhamento::with(['assistida', 'agressor'])->find($id);
        
        if (!$prontuario) {
            $this->dispatch('alerta', ['title' => 'Erro', 'text' => 'Prontuário não encontrado.', 'icon' => 'error']);
            return;
        }

        $this->dispatch('console-log', 'Prontuário Encontrado no Banco! Transmutando para Array e Setando isViewing para TRUE.');
        $this->prontuarioDetalhado = $prontuario->toArray();
        $this->isViewing = true;
    }

    public function openEdit($id)
    {
        $prontuario = Acompanhamento::find($id);

        if (!$prontuario) {
            $this->dispatch('alerta', ['title' => 'Erro', 'text' => 'Vínculo quebrado.', 'icon' => 'error']);
            return;
        }

        $this->prontuarioId = $prontuario->id;
        $this->editOrigem = $prontuario->origem_encaminhamento;
        $this->editSituacao = $prontuario->situacao;
        $this->editNivelRisco = $prontuario->nivel_risco_fonar;
        
        $this->isEditing = true;
    }

    private function validarEdicao(): bool|string
    {
        if (empty($this->editSituacao)) {
            return 'O Status (Situação) é obrigatório.';
        }
        
        if (empty($this->prontuarioId)) {
            return 'ID de Prontuário inválido.';
        }

        return true;
    }

    public function updateProntuario()
    {
        // Regra de Isolamento de Validação
        $validacao = $this->validarEdicao();

        if ($validacao !== true) {
            // Early Return e Bloqueio visual
            $this->dispatch('alerta', ['title' => 'Atenção', 'text' => $validacao, 'icon' => 'warning']);
            return;
        }

        $prontuario = Acompanhamento::find($this->prontuarioId);
        
        if (!$prontuario) {
            $this->dispatch('alerta', ['title' => 'Erro', 'text' => 'Registro inexistente no banco.', 'icon' => 'error']);
            return;
        }

        $prontuario->update([
            'origem_encaminhamento' => $this->editOrigem,
            'situacao' => $this->editSituacao,
            'nivel_risco_fonar' => $this->editNivelRisco,
        ]);

        $this->isEditing = false;
        
        $this->dispatch('alerta', [
            'title' => 'Sucesso!',
            'text' => 'Prontuário alterado com segurança.',
            'icon' => 'success'
        ]);
    }

    public function closeModals()
    {
        $this->isEditing = false;
        $this->isViewing = false;
        $this->reset(['prontuarioDetalhado', 'prontuarioId', 'editOrigem', 'editSituacao', 'editNivelRisco']);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Acompanhamento::with(['assistida', 'agressor']);

        if (!empty($this->status)) {
            $query->where('situacao', $this->status);
        }

        if (!empty($this->search)) {
            $termos = explode(' ', trim($this->search));

            $query->where(function ($topQ) use ($termos) {
                $topQ->where('numero_processo', 'like', '%' . $termos[0] . '%')
                     ->orWhereHas('assistida', function ($qAss) use ($termos) {
                         foreach ($termos as $palavra) {
                             $qAss->where('nome', 'like', '%' . $palavra . '%');
                         }
                     })
                     ->orWhereHas('agressor', function ($qAgr) use ($termos) {
                         foreach ($termos as $palavra) {
                             $qAgr->where('nome', 'like', '%' . $palavra . '%');
                         }
                     });
            });
        }

        $acompanhamentos = $query->orderBy($this->sortField, $this->sortDirection)
                                 ->paginate(12);

        return view('livewire.acompanhamentos.index', [
            'acompanhamentos' => $acompanhamentos
        ]);
    }
}
