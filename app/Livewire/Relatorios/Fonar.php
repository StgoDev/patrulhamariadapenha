<?php

namespace App\Livewire\Relatorios;

use Livewire\Component;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Fonar extends Component
{
    public function render()
    {
        return view('livewire.relatorios.fonar');
    }
}
