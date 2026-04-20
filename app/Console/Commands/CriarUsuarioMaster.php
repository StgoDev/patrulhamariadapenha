<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CriarUsuarioMaster extends Command
{
    /**
     * O nome e a assinatura do comando via Artisan.
     *
     * @var string
     */
    protected $signature = 'usuario:master';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Cria a patente Funcionario MASTER e gera o Usuário (admin) automaticamente com a senha forçada.';

    /**
     * Executa a automação.
     */
    public function handle()
    {
        $cpf = '958.490.333-00';
        $nome = 'ANTONIO FELIPE SANTIAGO NETO';
        $email = 'comando@pmpi.gov.br';
        $senha_plana = 'Fd0q1e5@';

        // 1. Cria a base do Funcionario Legado
        $funcionario = Funcionario::updateOrCreate(
            ['cpf' => $cpf],
            [
                'nome' => $nome,
                'situacao' => 'ATIVO',
                'matricula' => 'MASTER-01',
                // Preenchendo campos textuais obrigatórios ou necessários
                'email' => $email
            ]
        );

        // 2. Cria a base de Acesso de Sistema (User) associado ao Funcionario
        $usuario = User::updateOrCreate(
            ['cpf' => $cpf],
            [
                'name' => $nome,
                'email' => $email,
                'password' => Hash::make($senha_plana),
                'funcionario_id' => $funcionario->id
            ]
        );

        $this->info("Funcionario Master e Usuário (Login) INJETADOS com sucesso!");
        $this->table(
            ['Patente / Nome', 'CPF (Login)', 'Senha Autogerada', 'Status'],
            [[$funcionario->nome, $usuario->cpf, $senha_plana, $funcionario->situacao]]
        );
        
        $this->line('');
        $this->info("PRONTO! O usuário master foi criado e não precisa mais passar pela tela de /register. Você já pode acessar a Rota de Login (/login) e autenticar diretamente com este CPF e a senha 'Fd0q1e5@'.");
    }
}
