<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_sps', function (Blueprint $table) {
            $table->id();
            $table->integer('id_parent')->constrained('prospect_parents');
            $table->string('link_pembayaran')->nullable();
            $table->string('no_invoice');
            $table->string('no_pemesanan')->nullable();
            $table->date('date_paid');
            $table->integer('status_pembayaran');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_sps');
    }
}
