<?php

namespace App\Livewire\Agressores;

use App\Models\Agressor;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    #[Validate('required|string|min:3')]
    public $nome = '';

    #[Validate('boolean')]
    public $preso = false;

    #[Validate('boolean')]
    public $possui_arma_fogo = false;

    public function save()
    {
        $validated = $this->validate();

        Agressor::create($validated);

        $this->dispatch('alerta', [
            'title' => 'Salvo!',
            'text' => 'Registro de Agressor concluído com sucesso.',
            'icon' => 'success'
        ]);

        return $this->redirectRoute('agressores.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.agressores.create');
    }
}
