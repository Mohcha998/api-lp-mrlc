<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospect_parents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->foreignId('id_cabang')->nullable()->constrained('branches')->onDelete('set null')->change();
            $table->foreignId('id_program')->nullable()->constrained('programs')->onDelete('set null')->change();
            $table->bigInteger('phone')->nullable();
            $table->string('source')->nullable();
            $table->foreignId('invoice_sp')->nullable()->constrained('payment_sps')->onDelete('set null')->change();
            $table->foreignId('invoice_prg')->nullable()->constrained('pembayaran_programs')->onDelete('set null')->change();
            $table->integer('call')->default(0);
            $table->date('tgl_checkin')->nullable();
            $table->string('invitional_code')->nullable();
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
        Schema::dropIfExists('prospect_parents');
    }
}
