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
        Schema::table('users', function (Blueprint $table) {
            $table->string('rol', 30)->default('administrador');

            $table->foreignId('id_cliente')
                ->nullable()
                ->unique()
                ->constrained('cliente', 'id')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropUnique(['id_cliente']);
            $table->dropColumn(['id_cliente', 'rol']);
        });
    }
};
