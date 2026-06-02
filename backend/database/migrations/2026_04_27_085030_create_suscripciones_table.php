<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id('id_suscripcion');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->unsignedTinyInteger('plan')->comment('1=Basico, 2=Mensual');
            $table->unsignedTinyInteger('est_suscripcion')->comment('1=Activa, 2=Vencida, 3=En gracia');
            $table->date('fec_inicio');
            $table->date('fec_fin');
            $table->date('fec_ultimo_pago')->nullable()->default(null);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE suscripciones ADD CONSTRAINT chk_plan CHECK (plan IN (1, 2))');
        DB::statement('ALTER TABLE suscripciones ADD CONSTRAINT chk_est_suscripcion CHECK (est_suscripcion IN (1, 2, 3))');
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};