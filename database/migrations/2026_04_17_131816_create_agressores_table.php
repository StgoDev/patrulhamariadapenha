<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agressores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->boolean('preso')->default(false);
            $table->boolean('possui_arma_fogo')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agressores');
    }
};
