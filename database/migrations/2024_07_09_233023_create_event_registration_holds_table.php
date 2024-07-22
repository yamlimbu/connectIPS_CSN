<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRegistrationHoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_registration_holds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned();
            $table->string('nmc_registration_number', 191)->nullable();
            $table->string('first_name', 191);
            $table->string('middle_name', 191)->nullable();
            $table->string('last_name', 191);
            $table->string('email_address', 191)->nullable();
            $table->string('phone_number', 191)->nullable();
            $table->json('payment_details')->nullable();
            $table->string('payment_method', 191)->nullable();
            $table->string('total_amount', 191)->nullable();
            $table->string('status', 191)->nullable();
            $table->timestamps(0);
            $table->integer('retry_attempts')->nullable();
            $table->string('ip_address', 255)->nullable();
            $table->string('device', 255)->nullable();
            $table->string('platform')->nullable();
            $table->string('browser', 255)->nullable();
            $table->string('txnid', 255)->nullable();
            $table->date('txndate')->nullable();
            $table->string('txncrncy')->nullable();
            $table->string('txnamt', 255)->nullable();
            $table->string('referenceid', 255)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->string('particulars', 255)->nullable();
            $table->text('token')->nullable();
            $table->bigInteger('event_category_id')->nullable();
            $table->bigInteger('event_category_ticket_id')->nullable();
            $table->bigInteger('event_category_ticket_price_id')->nullable();
            $table->bigInteger('event_category_id_two')->nullable();
            $table->bigInteger('event_category_ticket_id_two')->nullable();
            $table->bigInteger('event_category_ticket_price_id_two')->nullable();
            $table->string('browser_version', 255)->nullable();
            $table->string('is_mobile', 255)->nullable();
            $table->string('is_tablet', 255)->nullable();
            $table->string('is_desktop', 255)->nullable();
            $table->string('is_bot', 255)->nullable();
            $table->string('is_iphone', 255)->nullable();
            $table->string('is_android', 255)->nullable();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->index('nmc_registration_number');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_registration_holds');
    }
}
