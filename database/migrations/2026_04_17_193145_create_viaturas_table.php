<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('viaturas', function (Blueprint $table) {
            $table->id();
            $table->string('prefixo')->unique()->comment('Ex: VTR-8001');
            $table->string('placa')->unique();
            $table->string('status')->default('OPERACIONAL')->comment('OPERACIONAL, MANUTENCAO, BAIXADA');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viaturas');
    }
};
