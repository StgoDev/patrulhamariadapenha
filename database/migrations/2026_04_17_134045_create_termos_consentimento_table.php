<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('termos_consentimento', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relacionamento restrito ao prontuário
            $table->foreignUuid('acompanhamento_id')->constrained('acompanhamentos')->cascadeOnDelete();

            $table->boolean('aceitou_programa')->comment('True: Aceitou. False: Recusou o atendimento');
            $table->date('data_assinatura');

            // Auditoria Militar e Segurança Jurídica
            $table->string('policial_matricula')->comment('Matrícula do operador que colheu o termo');
            $table->text('assinatura_hash')->nullable()->comment('Hash da assinatura digital/eletrônica colhida em tela');
            $table->text('motivo_recusa')->nullable()->comment('Preenchido apenas se aceitou_programa for false');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('termos_consentimento');
    }
};
