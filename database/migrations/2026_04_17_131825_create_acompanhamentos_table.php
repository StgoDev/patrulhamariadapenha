<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acompanhamentos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign Keys Restritivas (Garante a integridade do banco)
            $table->foreignUuid('assistida_id')->constrained('assistidas')->restrictOnDelete();
            $table->foreignUuid('agressor_id')->constrained('agressores')->restrictOnDelete();

            $table->string('numero_processo')->unique();
            $table->year('ano_processo');
            $table->string('origem_encaminhamento')->nullable();

            // Trava de Máquina de Estados no SGBD
            $table->enum('situacao', ['ATIVA', 'ENCERRADA', 'RECUSOU', 'PAUSA', 'REATIVOU'])->default('ATIVA');

            $table->string('grau_parentesco')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_encerramento')->nullable();

            $table->boolean('notificado')->default(false);
            $table->date('data_notificacao')->nullable();
            
            $table->enum('nivel_risco_fonar', ['BAIXO', 'MEDIO', 'ALTO', 'EXTREMO'])->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acompanhamentos');
    }
};
