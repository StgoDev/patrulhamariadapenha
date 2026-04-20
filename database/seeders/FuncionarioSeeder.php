<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Funcionario;

class FuncionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 20; $i++) {
            Funcionario::create([
                'nome' => strtoupper($faker->name()),
                'situacao' => 'ATIVO',
                'posto_graduacao' => rand(8, 13), // Sub Ten to Sd
                'matricula' => '100' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'cpf' => str_pad(mt_rand(1, 99999999999), 11, '0', STR_PAD_LEFT),
                'classificacao' => 'PATRULHA MARIA DA PENHA',
            ]);
        }
    }
}
