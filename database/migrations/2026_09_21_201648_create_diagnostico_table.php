<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnostico', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_inventario')
                ->constrained('inventario');

            $table->foreignId('id_mecanico')
                ->constrained('mecanicos', 'id_mecanico');

            $table->text('descripcion');

            $table->date('fecha_diagnostico');

            $table->enum('estado', [
                'pendiente',
                'en_proceso',
                'finalizado',
                'cancelado'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnostico');
    }
};

