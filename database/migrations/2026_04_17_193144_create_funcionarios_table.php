<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            
            // Boolean flags
            $table->boolean('sigilo')->default(false);
            $table->boolean('planejada')->default(false);
            $table->boolean('planejada_restricao_interna')->default(false);
            $table->boolean('gdi')->default(false);
            $table->boolean('gde')->default(false);
            $table->boolean('gde_certificado')->default(false);
            
            // Integers
            $table->integer('gde_horas')->default(0);
            $table->integer('gde_valor')->default(0);
            
            // Strings (varchars)
            $table->string('matricula', 20)->default('**')->nullable();
            $table->integer('origem')->default(0)->nullable();
            $table->string('rgpm', 255)->default('**')->nullable();
            $table->string('cpf', 14)->default('**')->nullable();
            $table->string('etnia', 55)->default('**')->nullable();
            $table->string('titulo', 14)->default('**')->nullable();
            $table->string('sexo', 1)->default('N')->nullable();
            
            $table->string('grupo_sangue', 2)->default('**');
            $table->string('fator_rh', 8)->default('**');
            $table->string('nacionalidade', 55)->default('****')->comment('RG digital');
            $table->string('naturalidade', 55)->default('****')->comment('RG digital');
            $table->string('naturalidade_uf', 2)->default('**')->comment('RG digital');
            $table->string('pis_pasep', 15)->default('****')->comment('RG Digital');
            
            $table->integer('posto_graduacao')->default(13);
            
            $table->string('classificacao', 255)->default('**')->nullable();
            $table->string('situacao', 155)->default('****');
            $table->string('qpm', 255)->default('**')->nullable();
            
            $table->string('nome', 555)->default('**')->nullable();
            $table->string('nome_pai', 255)->default('**');
            $table->string('nome_mae', 255)->default('**');
            
            $table->string('banco', 25)->default('**')->nullable();
            $table->string('agencia', 15)->default('**')->nullable();
            $table->string('conta', 15)->default('**')->nullable();
            
            $table->date('data_nascimento')->nullable();
            $table->date('data_inclusao')->nullable();
            $table->date('nascimento')->nullable();
            $table->date('inclusao')->nullable();
            $table->date('promocao')->nullable();
            
            $table->integer('anonascimento')->nullable();
            $table->integer('anoinclusao')->nullable();
            
            $table->string('cidade', 45)->default('**')->nullable();
            $table->string('bairro', 45)->default('**')->nullable();
            $table->text('obs')->nullable();
            
            $table->string('comportamento', 45)->default('**')->nullable();
            
            $table->string('cnh', 45)->default('**')->nullable();
            $table->string('cnh_numero', 15)->default('**')->nullable();
            $table->date('cnh_validade')->nullable();
            
            $table->string('endereco', 555)->default('**')->nullable();
            $table->string('email', 220)->default('**')->nullable();
            $table->string('cep', 45)->default('**')->nullable();
            $table->string('fone1', 45)->default('**')->nullable();
            $table->string('fone2', 45)->default('**')->nullable();
            
            // Fardamento
            $table->string('gandola', 2)->default('**');
            $table->string('calca', 2)->default('**')->comment('FARDAMENTO');
            $table->string('calca_comprimento', 15)->default('**')->nullable()->comment('FARDAMENTO');
            $table->string('coturno', 2)->nullable();
            $table->string('camisa', 2)->default('**');
            $table->string('gorro', 2)->default('**');
            $table->string('colete_tipo', 2)->default('**');
            $table->string('colete_tamanho', 2)->default('**');
            $table->string('cinto_guarnicao', 2)->default('**')->nullable();
            $table->string('canicula_passeio', 2)->default('**')->nullable();
            $table->string('calca_passeio', 2)->default('**')->nullable();
            $table->string('sapato', 2)->default('**')->nullable();
            
            $table->decimal('altura', 4, 2)->default(0.00)->nullable()->comment('FARDAMENTO');
            $table->decimal('peso', 5, 2)->default(0.00)->nullable()->comment('FARDAMENTO');
            
            $table->timestamp('created')->nullable();
            $table->timestamp('modified')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
