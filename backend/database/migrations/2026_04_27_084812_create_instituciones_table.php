<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituciones', function (Blueprint $table) {
            $table->id('id_institucion');
            $table->foreignId('id_docente')
                  ->constrained('usuarios', 'id_usuario')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('nombre', 150);
            $table->string('logo', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instituciones');
    }
};