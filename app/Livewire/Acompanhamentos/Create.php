<?php

namespace App\Livewire\Acompanhamentos;

use App\Models\Acompanhamento;
use App\Models\Assistida;
use App\Models\Agressor;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    #[Validate('required|uuid|exists:assistidas,id')]
    public $assistida_id = '';

    #[Validate('required|uuid|exists:agressores,id')]
    public $agressor_id = '';

    #[Validate('required|string|unique:acompanhamentos,numero_processo')]
    public $numero_processo = '';

    #[Validate('required|integer|min:2000')]
    public $ano_processo;

    #[Validate('nullable|string')]
    public $origem_encaminhamento = '';

    #[Validate('required|string|in:ATIVA,ENCERRADA,RECUSOU,PAUSA,REATIVOU')]
    public $situacao = 'ATIVA';

    #[Validate('nullable|string')]
    public $grau_parentesco = '';

    public function mount()
    {
        $this->ano_processo = date('Y');
    }

    public function save()
    {
        $validated = $this->validate();

        Acompanhamento::create($validated);

        $this->dispatch('alerta', [
            'title' => 'Prontuário Criado!',
            'text' => 'Acompanhamento registrado e vinculado com sucesso.',
            'icon' => 'success'
        ]);

        return $this->redirectRoute('acompanhamentos.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.acompanhamentos.create', [
            'assistidas' => Assistida::orderBy('nome')->get(),
            'agressores' => Agressor::orderBy('nome')->get(),
        ]);
    }
}
