<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('id_sesion')
                  ->constrained('sesiones', 'id_sesion')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->unsignedBigInteger('id_alumno');
            $table->foreign('id_alumno')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->unsignedTinyInteger('est_asistencia')->comment('1=Presente, 2=Ausente, 3=Justificada');
            $table->dateTime('hora_registro')->nullable()->default(null);
            $table->timestamps();

            $table->unique(['id_sesion', 'id_alumno']);
        });

        DB::statement('ALTER TABLE asistencias ADD CONSTRAINT chk_est_asistencia CHECK (est_asistencia IN (1, 2, 3))');
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};