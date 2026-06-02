<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_alumnos', function (Blueprint $table) {
            $table->id('id_grupo_alumno');
            $table->foreignId('id_grupo')
                  ->constrained('grupos', 'id_grupo')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->unsignedBigInteger('id_alumno');
            $table->foreign('id_alumno')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->date('fec_inscripcion');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE grupo_alumnos ALTER fec_inscripcion SET DEFAULT (CURRENT_DATE)');
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_alumnos');
    }
};