<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assistida;
use App\Models\Agressor;
use App\Models\Acompanhamento;
use App\Models\Viatura;

class MockupSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        // 5 Viaturas
        $viaturas = [];
        for ($i = 1; $i <= 5; $i++) {
            $viaturas[] = Viatura::firstOrCreate([
                'prefixo' => 'VTR-' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ],[
                'placa' => strtoupper($faker->bothify('???-####')),
            ]);
        }

        // 10 Vítimas
        $assistidas = [];
        for ($i = 0; $i < 10; $i++) {
            $assistidas[] = Assistida::create([
                'nome' => strtoupper($faker->name('female')),
                'telefone' => $faker->numerify('8698#######'),
                'endereco' => $faker->address(),
                'data_nascimento' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
                'zona_regiao' => $faker->randomElement(['NORTE', 'SUL', 'LESTE', 'SUDESTE', 'CENTRO']),
            ]);
        }

        // 10 Agressores
        $agressores = [];
        for ($i = 0; $i < 10; $i++) {
            $agressores[] = Agressor::create([
                'nome' => strtoupper($faker->name('male')),
                'preso' => $faker->boolean(20), // 20% preso
                'possui_arma_fogo' => $faker->boolean(40), // 40% com arma
            ]);
        }

        // 10 Prontuarios (Acompanhamentos)
        for ($i = 0; $i < 10; $i++) {
            Acompanhamento::create([
                'assistida_id' => $assistidas[$i]->id,
                'agressor_id' => $agressores[$i]->id,
                'numero_processo' => '000' . $faker->numerify('####-##.2026.8.18.0140'),
                'ano_processo' => '2026',
                'situacao' => 'ATIVA',
                'data_inicio' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'nivel_risco_fonar' => $faker->randomElement(['ALTO', 'MEDIO', 'BAIXO']),
            ]);
        }
    }
}
