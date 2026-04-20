<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('escalas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viatura_id')->constrained('viaturas')->restrictOnDelete();
            
            $table->date('data_escala');
            $table->time('inicio');
            $table->time('termino');
            $table->integer('turno')->comment('6, 8, 12, 18, 24 horas');
            $table->string('status')->default('ATIVA')->comment('ATIVA, ENCERRADA');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escalas');
    }
};
