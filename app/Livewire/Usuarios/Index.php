<?php

namespace App\Livewire\Usuarios;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $busca = '';

    // Alpine Entangle State
    public $modalEstado = false;

    // Campos do Formulário
    public $userId = null;
    public $name = '';
    public $email = '';
    public $cpf = '';
    public $password = ''; // Opcional no edit

    public function render()
    {
        $query = User::with('funcionario')->orderBy('id', 'desc');

        if ($this->busca) {
            $query->where('name', 'like', '%' . $this->busca . '%')
                ->orWhere('cpf', 'like', '%' . $this->busca . '%');
        }

        return view('livewire.usuarios.index', [
            'usuarios' => $query->paginate(10)
        ])->layout('layouts.app');
    }

    public function updatedBusca()
    {
        $this->resetPage();
    }

    public function novo()
    {
        $this->reset(['userId', 'name', 'email', 'cpf', 'password']);
        $this->resetValidation();
        $this->modalEstado = true;
    }

    public function editar($id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->cpf = $user->cpf;
        $this->password = '';

        $this->modalEstado = true;
    }

    public function salvar()
    {
        $regras = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('users')->ignore($this->userId)],
        ];

        if (!$this->userId || !empty($this->password)) {
            $regras['password'] = 'required|min:8';
        }

        $this->validate($regras);

        $dados = [
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
        ];

        if (!empty($this->password)) {
            $dados['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(
            ['id' => $this->userId],
            $dados
        );

        $this->modalEstado = false;

        $this->dispatch('alerta', [
            'title' => 'Sucesso',
            'text' => 'Conta salva perfeitamente na base.',
            'icon' => 'success'
        ]);
    }

    public function deletar($id)
    {
        User::findOrFail($id)->delete();

        $this->dispatch('alerta', [
            'title' => 'Deletado',
            'text' => 'Acesso revogado e credencial excluída.',
            'icon' => 'success'
        ]);
    }
}
