<?php

namespace App\Livewire\Agressores;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use App\Models\Agressor;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Propriedades expostas na URL (Comportamento AJAX-style)
    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'order')]
    public $sortField = 'created_at';

    #[Url(as: 'dir')]
    public $sortDirection = 'desc';

    // Disparador genérico para ordernações
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Resetar paginações sempre que a busca mudar
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Agressor::query();

        if (!empty($this->search)) {
            $termos = explode(' ', trim($this->search));
            
            $query->where(function ($q) use ($termos) {
                // Para o ID, tenta match exato ou numérico simples no primeiro item
                $q->where('id', 'like', '%' . $termos[0] . '%')
                  ->orWhere(function ($subQ) use ($termos) {
                      foreach ($termos as $palavra) {
                          $subQ->where('nome', 'like', '%' . $palavra . '%');
                      }
                  });
            });
        }

        $agressores = $query->orderBy($this->sortField, $this->sortDirection)
                            ->paginate(12);

        return view('livewire.agressores.index', [
            'agressores' => $agressores
        ]);
    }
}
