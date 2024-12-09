<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiayaAdminToPaymentSpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_sps', function (Blueprint $table) {
            $table->decimal('biaya_admin', 10, 2)->nullable()->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_sps', function (Blueprint $table) {
            $table->dropColumn('biaya_admin');
        });
    }
}
