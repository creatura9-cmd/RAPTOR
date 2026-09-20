<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_moto')
                ->constrained('moto');

            $table->text('descripcion')->nullable();

            $table->timestamp('fecha_registro')
                ->useCurrent();

            $table->enum('condicion_general', [
                'bueno',
                'regular',
                'malo',
            ]);

            $table->enum('estado_inventario', [
                'pendiente',
                'en_proceso',
                'finalizado',
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
