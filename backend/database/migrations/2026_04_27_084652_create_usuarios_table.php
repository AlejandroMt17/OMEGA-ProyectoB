<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('ap_pat', 100);
            $table->string('ap_mat', 100);
            $table->string('email', 200)->unique();
            $table->string('contrasenia', 255);
            $table->unsignedTinyInteger('rol')->comment('1=Docente, 2=Alumno');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE usuarios ADD CONSTRAINT chk_rol CHECK (rol IN (1, 2))');
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};