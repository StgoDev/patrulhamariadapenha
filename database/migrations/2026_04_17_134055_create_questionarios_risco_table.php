<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questionarios_risco', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('acompanhamento_id')->constrained('acompanhamentos')->cascadeOnDelete();

            // Dados da Aplicação
            $table->date('data_aplicacao');
            $table->string('policial_matricula')->comment('Quem aplicou o FONAR');
            $table->enum('fase_aplicacao', ['INICIAL', 'REVISAO', 'DESLIGAMENTO'])->default('INICIAL');

            // =========================================================================
            // ARQUITETURA ESCALÁVEL: O campo JSON armazenará o formulário inteiro.
            // Exemplo de Payload: {"possui_arma": true, "ameaca_morte": false, ...}
            // =========================================================================
            $table->json('respostas_brutas')->comment('Payload estruturado com todas as respostas do Anexo II');

            // Metadados Extraídos (Para Dashboards e Data Visualization do Comando)
            $table->integer('pontuacao_total')->nullable();
            $table->enum('nivel_risco_calculado', ['BAIXO', 'MEDIO', 'ALTO', 'EXTREMO']);

            // Gatilhos Críticos Extraídos do JSON para facilitar consultas SQL rápidas
            $table->boolean('alerta_arma_fogo')->default(false);
            $table->boolean('alerta_risco_feminicidio')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questionarios_risco');
    }
};
