<?php

namespace App\Livewire\Assistidas;

use App\Models\Assistida;
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

    public function render()
    {
        $query = Assistida::query();

        if (!empty($this->search)) {
            $termos = explode(' ', trim($this->search));
            
            $query->where(function ($q) use ($termos) {
                $q->where('id', 'like', '%' . $termos[0] . '%')
                  ->orWhere(function ($subQ) use ($termos) {
                      foreach ($termos as $palavra) {
                          $subQ->where('nome', 'like', '%' . $palavra . '%');
                      }
                  });
            });
        }

        $assistidas = $query->orderBy($this->sortField, $this->sortDirection)
                            ->paginate(12);

        return view('livewire.assistidas.index', [
            'assistidas' => $assistidas
        ]);
    }

    public function delete($id)
    {
        $assistida = Assistida::findOrFail($id);
        $assistida->delete();
        
        $this->dispatch('alerta', [
            'title' => 'Excluída', 
            'text' => 'Registro de assistida removido com sucesso!', 
            'icon' => 'success'
        ]);
    }
}
