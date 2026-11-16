<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preorden_repuesto', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_preorden')
                ->constrained('preorden');

            $table->foreignId('id_repuesto')
                ->constrained('repuesto');

            $table->integer('cantidad');

            $table->decimal('precio_unitario', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preorden_repuesto');
    }
};
