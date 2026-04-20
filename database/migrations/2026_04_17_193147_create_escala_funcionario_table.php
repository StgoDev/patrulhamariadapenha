<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('escala_funcionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escala_id')->constrained('escalas')->cascadeOnDelete();
            $table->foreignId('funcionario_id')->constrained('funcionarios')->restrictOnDelete();
            
            $table->string('papel_patrulha')->comment('COMANDANTE, MOTORISTA, PATRULHEIRO');
            
            $table->timestamps();
            
            // Um funcionário só pode estar uma vez na mesma escala
            $table->unique(['escala_id', 'funcionario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escala_funcionario');
    }
};
