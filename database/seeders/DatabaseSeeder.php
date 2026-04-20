<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Gera 20 Funcionários Oficiais
        $this->call([
            FuncionarioSeeder::class,
        ]);

        // 2. Gera a Base de Teste: (Viaturas, Vítimas, Agressores, Processos)
        $this->call([
            MockupSeeder::class,
        ]);
    }
}
