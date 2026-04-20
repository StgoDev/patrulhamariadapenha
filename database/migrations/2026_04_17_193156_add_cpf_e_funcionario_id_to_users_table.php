<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Torna o email opcional
            $table->string('email')->nullable()->change();
            
            // Adiciona CPF e Relacionamento com Funcionário
            $table->string('cpf', 20)->unique()->after('name')->nullable();
            $table->foreignId('funcionario_id')->nullable()->after('cpf')->constrained('funcionarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['funcionario_id']);
            $table->dropColumn(['cpf', 'funcionario_id']);
            
            $table->string('email')->nullable(false)->change();
        });
    }
};
