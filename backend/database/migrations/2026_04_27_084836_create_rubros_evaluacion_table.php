<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubros_evaluacion', function (Blueprint $table) {
            $table->id('id_rubro');
            $table->foreignId('id_institucion')
                  ->constrained('instituciones', 'id_institucion')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('nombre', 100);
            $table->decimal('porcentaje_minimo', 5, 2);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE rubros_evaluacion ADD CONSTRAINT chk_porcentaje CHECK (porcentaje_minimo BETWEEN 0 AND 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('rubros_evaluacion');
    }
};