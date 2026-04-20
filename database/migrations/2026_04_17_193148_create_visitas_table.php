<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('escala_id')->constrained('escalas')->restrictOnDelete();
            $table->foreignUuid('acompanhamento_id')->constrained('acompanhamentos')->cascadeOnDelete();

            $table->dateTime('data_agendada')->nullable();
            
            // ENUMs literais via restrição do MySQL
            $table->enum('status_visita', ['agendada', 'em_deslocamento', 'realizada', 'frustrada'])->default('agendada');
            $table->enum('tipo_monitoramento', ['visita', 'ronda', 'contato_telefonico'])->nullable();
            
            // Campos descritivos que receberão Cast Encriptado (Text Open)
            $table->text('resumo_atendimento')->nullable();
            $table->text('avaliacao_equipe')->nullable();
            $table->text('percepcao_assistida')->nullable();
            
            // Trigger Assíncrona de Risco Judicial
            $table->boolean('descumprimento_mpu')->default(false);

            $table->boolean('visto_comandante')->default(false)->comment('Validação superior da visita');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
