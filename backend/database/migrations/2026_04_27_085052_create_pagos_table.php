<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('id_suscripcion')
                  ->constrained('suscripciones', 'id_suscripcion')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('paypal_order_id', 100)->unique();
            $table->string('paypal_transaction_id', 100)->nullable()->default(null);
            $table->double('mon_monto');
            $table->string('est_pago', 20)->comment('COMPLETED, PENDING, FAILED, REFUNDED');
            $table->date('fec_pago');
            $table->string('tipo_pago', 30)->comment('paypal, card, oxxo');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE pagos ADD CONSTRAINT chk_mon_monto CHECK (mon_monto >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};