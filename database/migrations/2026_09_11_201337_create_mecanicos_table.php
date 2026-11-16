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
        Schema::create('mecanicos', function (Blueprint $table) {
            $table->id('id_mecanico');
            $table->string('nombre');
            $table->string('apellido');
            $table->enum('tipo_documento', ['CC', 'CE', 'TI', 'PAS']);
            $table->string('numero_documento')->unique();
            $table->string('telefono');
            $table->string('correo_electronico')->unique();
            $table->string('especialidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mecanicos');
    }
};
