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
        Schema::create('repuesto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');

            $table->string('marca')->nullable();

            $table->enum('categoria', [
                'motor',
                'transmision',
                'frenos',
                'electrico',
                'combustible',
                'suspension',
                'direccion',
                'ruedas',
                'accesorios',
            ]);

            $table->decimal('precio', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repuesto');
    }
};
