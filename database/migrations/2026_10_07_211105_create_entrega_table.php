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
        Schema::create('entrega', function (Blueprint $table) {
            $table->id('id_entrega');

            $table->foreignId('id_ordentrabajo')
                ->unique()
                ->constrained('orden_trabajo');

            $table->date('fecha_entrega');

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega');
    }
};