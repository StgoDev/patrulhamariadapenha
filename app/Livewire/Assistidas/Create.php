<?php

namespace App\Livewire\Assistidas;

use App\Models\Assistida;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Create extends Component
{
    #[Validate('required|string|min:3')]
    public $nome = '';

    #[Validate('nullable|string')]
    public $zona_regiao = '';

    #[Validate('nullable|string')]
    public $endereco = '';

    #[Validate('nullable|string')]
    public $telefone = '';

    #[Validate('nullable|date')]
    public $data_nascimento = null;

    #[Validate('nullable|string')]
    public $raca_cor = '';

    #[Validate('boolean')]
    public $possui_deficiencia = false;

    public function save()
    {
        $validated = $this->validate();

        Assistida::create($validated);

        $this->dispatch('alerta', [
            'title' => 'Sucesso!',
            'text' => 'Vítima assistida registrada com sucesso.',
            'icon' => 'success'
        ]);

        return $this->redirectRoute('assistidas.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.assistidas.create');
    }
}
