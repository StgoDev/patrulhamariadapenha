<?php

namespace App\Livewire\Funcionarios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Funcionario;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $busca = '';
    public $modalEstado = false;

    // Campos Chaves para a Modal Executiva (Evita carregar 60 inputs na tela)
    public $funcionarioId = null;
    public $nome = '';
    public $cpf = '';
    public $rgpm = '';
    public $matricula = '';
    public $posto_graduacao = 0;
    public $situacao = 'ATIVO';

    public function render()
    {
        $query = Funcionario::orderBy('id', 'desc');

        if ($this->busca) {
            $query->where('nome', 'like', '%' . $this->busca . '%')
                ->orWhere('cpf', 'like', '%' . $this->busca . '%')
                ->orWhere('matricula', 'like', '%' . $this->busca . '%');
        }

        return view('livewire.funcionarios.index', [
            'funcionarios' => $query->paginate(10)
        ])->layout('layouts.app');
    }

    public function updatedBusca()
    {
        $this->resetPage();
    }

    public function novo()
    {
        $this->reset(['funcionarioId', 'nome', 'cpf', 'rgpm', 'matricula']);
        $this->posto_graduacao = 0;
        $this->situacao = 'ATIVO';
        $this->resetValidation();
        $this->modalEstado = true;
    }

    public function editar($id)
    {
        $this->resetValidation();
        $func = Funcionario::findOrFail($id);

        $this->funcionarioId = $func->id;
        $this->nome = $func->nome;
        $this->cpf = $func->cpf;
        $this->rgpm = $func->rgpm;
        $this->matricula = $func->matricula;
        $this->posto_graduacao = $func->posto_graduacao;
        $this->situacao = $func->situacao;

        $this->modalEstado = true;
    }

    public function salvar()
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14',
            'matricula' => 'required|string|max:20',
            'situacao' => 'required|string|max:255',
        ]);

        // Se for um registro novo, as outras 56 colunas omitidas vão puxar do schema MySQL DEFAULT '**.
        Funcionario::updateOrCreate(
            ['id' => $this->funcionarioId],
            [
                'nome' => $this->nome,
                'cpf' => $this->cpf,
                'rgpm' => $this->rgpm ?: '**',
                'matricula' => $this->matricula,
                'posto_graduacao' => (int) $this->posto_graduacao ?: 0,
                'situacao' => $this->situacao,
            ]
        );

        $this->modalEstado = false;

        $this->dispatch('alerta', [
            'title' => 'Registro Salvo',
            'text' => 'Registro do Efetivo modificado com sucesso.',
            'icon' => 'success'
        ]);
    }

    public function deletar($id)
    {
        Funcionario::findOrFail($id)->delete();

        $this->dispatch('alerta', [
            'title' => 'Apagar Registro',
            'text' => 'O registro foi apagado.',
            'icon' => 'success'
        ]);
    }
}
