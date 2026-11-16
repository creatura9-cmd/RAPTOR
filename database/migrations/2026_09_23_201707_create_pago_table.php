<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_preorden')
                ->nullable()
                ->constrained('preorden');

            $table->foreignId('id_ordentrabajo')
                ->nullable()
                ->constrained('orden_trabajo');

            $table->enum('tipo_pago', [
                'efectivo',
                'transferencia',
                'tarjeta'
            ]);

            $table->date('fecha_pago');

            $table->decimal('valor', 10, 2);

            $table->enum('estado', [
                'pendiente',
                'confirmado',
                'rechazado'
            ])->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index('id_ordentrabajo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};