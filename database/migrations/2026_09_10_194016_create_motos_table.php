<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_marca')
                ->constrained('marca_moto');

            $table->foreignId('id_cliente')
                ->constrained('cliente');

            $table->string('modelo');
            $table->integer('anio');
            $table->string('placa')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moto');
    }
};
