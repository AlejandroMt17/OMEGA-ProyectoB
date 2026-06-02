<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id('id_grupo');
            $table->foreignId('id_institucion')
                  ->constrained('instituciones', 'id_institucion')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->unsignedBigInteger('id_docente');
            $table->foreign('id_docente')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('nombre', 100);
            $table->string('materia', 150);
            $table->string('periodo', 50);
            $table->unsignedInteger('no_alumnos');
            $table->string('codigo_inv', 20)->unique()->nullable()->default(null);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE grupos ADD CONSTRAINT chk_no_alumnos CHECK (no_alumnos > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};