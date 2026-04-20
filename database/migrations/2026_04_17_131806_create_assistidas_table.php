<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assistidas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->string('zona_regiao')->nullable();

            // Text é obrigatório aqui pois o payload criptografado (base64) 
            // ultrapassa o limite padrão do varchar(255)
            $table->text('endereco')->nullable();
            $table->text('telefone')->nullable();

            $table->date('data_nascimento')->nullable();
            $table->string('raca_cor')->nullable()->comment('IBGE: Branca, Preta, Parda, Amarela, Indígena');
            $table->boolean('possui_deficiencia')->default(false);

            $table->timestamps();
            $table->softDeletes(); // Prevenção contra exclusão acidental (Auditoria)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistidas');
    }
};
