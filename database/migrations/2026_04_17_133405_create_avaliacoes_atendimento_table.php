<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes_atendimento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relacionamento com o prontuário geral
            $table->foreignUuid('acompanhamento_id')->constrained('acompanhamentos')->cascadeOnDelete();
            
            // Dados da aplicação da pesquisa
            $table->date('data_pesquisa');
            
            // Notas de 1 a 5 (Péssimo, Ruim, Regular, Bom, Excelente)
            $table->tinyInteger('nota_quantidade_visitas')->nullable();
            $table->tinyInteger('nota_tempo_duracao')->nullable();
            $table->tinyInteger('nota_qualidade_informacoes')->nullable();
            $table->tinyInteger('nota_qualidade_atendimento')->nullable();
            $table->tinyInteger('nota_postura_agentes')->nullable();
            
            // Respostas Binárias (Sim/Não mapeadas para Boolean)
            $table->boolean('gerou_transtorno')->nullable()->comment('Visita gerou transtorno no local?');
            $table->boolean('sentiu_mais_segura')->nullable();
            $table->boolean('agressor_voltou_importunar')->nullable();
            $table->boolean('recomendaria_pmp')->nullable();
            
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_atendimento');
    }
};
