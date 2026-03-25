<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_ship', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('type_of_member_id');
           $table->unsignedBigInteger('user_id');
            $table->string('nmc_register_number');
            $table->string('gender');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phone_number')->nullable();
            $table->string('mobile_number');
            $table->string('citizenship_number');
            $table->string('address');
            $table->string('current_working_hospital');
            $table->string('phone_number_hospital')->nullable();
            $table->string('current_working_clinic')->nullable();
            $table->string('phone_number_clinic')->nullable();
            $table->string('full_postal_address');
            $table->text('signature')->nullable();
            $table->text('all_document_after_mbbs')->nullable();
            $table->text('nmc_registration_certificate_mbbs')->nullable();
            $table->text('citizenship_photo')->nullable();
            $table->text('pp_size_photo')->nullable();
            $table->text('voucher')->nullable();
            $table->string('voucher_number')->nullable();
            $table->date('submitted_date')->nullable();
            $table->string('submitted_by')->nullable();
            $table->date('approved_by')->nullable();
            $table->date('approved_on')->nullable();
            $table->string('csn_reg_no')->nullable();
            $table->tinyInteger('status_id')->default(1);
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
        Schema::dropIfExists('member_ship');
    }
};
