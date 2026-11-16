<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orden_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_inventario')
                 ->unique()
                ->constrained('inventario');
            $table->foreignId('id_preorden')
                ->unique()
                ->constrained('preorden');
            $table->foreignId('id_mecanico')
                ->constrained('mecanicos', 'id_mecanico');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', [
                'abierta',
                'en_proceso',
                'finalizada'
            ])->default('abierta');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajo');
    }
};
