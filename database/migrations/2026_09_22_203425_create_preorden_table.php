<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preorden', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_diagnostico')
                ->unique()
                ->constrained('diagnostico');

            $table->text('descripcion')->nullable();

            $table->decimal('mano_obra', 10, 2)
                ->default(0.00);

            $table->decimal('total', 10, 2)
                ->default(0.00);

            $table->enum('estado', [
                'pendiente',
                'aprobada',
                'rechazada'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preorden');
    }
};
