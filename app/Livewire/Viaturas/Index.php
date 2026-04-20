<?php

namespace App\Livewire\Viaturas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Viatura;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filtroStatus = '';

    // Modal
    public $isEditing = false;
    public $viaturaId = null;
    public $prefixo = '';
    public $placa = '';
    public $status = 'OPERACIONAL';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFiltroStatus() { $this->resetPage(); }

    public function rules()
    {
        return [
            'prefixo' => 'required|string|max:255|unique:viaturas,prefixo,' . $this->viaturaId,
            'placa' => 'required|string|max:255|unique:viaturas,placa,' . $this->viaturaId,
            'status' => 'required|in:OPERACIONAL,MANUTENCAO,BAIXADA',
        ];
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['viaturaId', 'prefixo', 'placa']);
        $this->status = 'OPERACIONAL';
        $this->isEditing = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $viatura = Viatura::findOrFail($id);
        $this->viaturaId = $viatura->id;
        $this->prefixo = $viatura->prefixo;
        $this->placa = $viatura->placa;
        $this->status = $viatura->status;
        $this->isEditing = true;
    }

    public function closeModals()
    {
        $this->isEditing = false;
    }

    public function salvar()
    {
        $this->validate();

        // Regra Estrita: Apenas viaturas marcadas como OPERACIONAL estarão Ativas para Escalas.
        $is_ativo = ($this->status === 'OPERACIONAL') ? true : false;

        Viatura::updateOrCreate(
            ['id' => $this->viaturaId],
            [
                'prefixo' => mb_strtoupper($this->prefixo),
                'placa' => mb_strtoupper($this->placa),
                'status' => $this->status,
                'is_ativo' => $is_ativo
            ]
        );

        $this->closeModals();
        $this->dispatch('alerta', [
            'title' => 'Frota Atualizada',
            'text' => 'Dados da Viatura gravados com sucesso.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        $query = Viatura::query();

        if ($this->filtroStatus) {
            $query->where('status', $this->filtroStatus);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('prefixo', 'like', '%'.$this->search.'%')
                  ->orWhere('placa', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.viaturas.index', [
            'viaturas' => $query->orderBy('status', 'asc')->orderBy('prefixo', 'asc')->paginate(10)
        ]);
    }
}
