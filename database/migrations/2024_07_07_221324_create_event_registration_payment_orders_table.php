<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRegistrationPaymentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_registration_payment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('status', 255)->nullable();
            $table->string('statusDesc', 255)->nullable();
            $table->string('merchantId', 255)->nullable();
            $table->string('appId', 255)->nullable();
            $table->string('referenceId', 255)->nullable();
            $table->string('txnAmt', 255)->nullable();
            $table->text('token')->nullable();
            $table->string('debitBankCode', 255)->nullable();
            $table->string('txnId', 255)->nullable();
            $table->string('batchId', 255)->nullable();
            $table->string('txnDate', 255)->nullable();
            $table->string('txnCrncy', 255)->nullable();
            $table->string('chargeAmt', 255)->nullable();
            $table->string('chargeLiability', 255)->nullable();
            $table->string('refId', 255)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->string('particulars', 255)->nullable();
            $table->string('creditStatus', 255)->nullable();
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
        Schema::dropIfExists('event_registration_payment_orders');
    }
}
