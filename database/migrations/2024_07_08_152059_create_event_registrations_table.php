<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('event_id');
            $table->string('nmc_registration_number')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email_address');
            $table->string('phone_number')->nullable();
            $table->string('event_token')->nullable();

            $table->json('payment_details')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('status')->nullable();
            
            $table->string('ip_address')->nullable();
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();

            $table->string('txnid')->nullable();
            $table->timestamp('txndate')->nullable();
            $table->string('txncrncy')->nullable();
            $table->decimal('txnamt', 10, 2)->nullable();
            $table->string('referenceid')->nullable();
            $table->text('remarks')->nullable();
            $table->text('particulars')->nullable();
            $table->string('token')->nullable();

            $table->bigInteger('event_category_id')->nullable();
            $table->bigInteger('event_category_ticket_id')->nullable();
            $table->bigInteger('event_category_ticket_price_id')->nullable();

            $table->bigInteger('event_category_id_two')->nullable();
            $table->bigInteger('event_category_ticket_id_two')->nullable();
            $table->bigInteger('event_category_ticket_price_id_two')->nullable();

            $table->boolean('is_mobile')->default(false);
            $table->boolean('is_tablet')->default(false);
            $table->boolean('is_desktop')->default(false);
            $table->boolean('is_bot')->default(false);
            $table->boolean('is_iphone')->default(false);
            $table->boolean('is_android')->default(false);
            
            $table->bigInteger('hold_id')->nullable();

            $table->timestamps();

            // Optionally add foreign key for event_id
            // $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};