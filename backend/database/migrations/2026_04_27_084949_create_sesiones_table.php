<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->foreignId('id_grupo')
                  ->constrained('grupos', 'id_grupo')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('clave', 20);
            $table->unsignedTinyInteger('est_sesion')->default(1)->comment('1=Activa, 0=Cerrada');
            $table->date('fec_sesion');
            $table->dateTime('hora_apertura')->useCurrent();
            $table->dateTime('hora_cierre')->nullable()->default(null);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE sesiones ADD CONSTRAINT chk_est_sesion CHECK (est_sesion IN (0, 1))');
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};