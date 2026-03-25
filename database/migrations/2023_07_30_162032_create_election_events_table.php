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
        Schema::create('election_events', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->date('election_date')->nullable();
            $table->dateTime('voting_start_datetime')->nullable();
            $table->dateTime('voting_end_datetime')->nullable();
            $table->unsignedBigInteger('election_officer_id')->nullable()->default(null);
            $table->longText('location')->nullable()->default(null);
            $table->boolean('result_published')->nullable()->default(false);
            $table->dateTime('activated_at')->nullable()->default(null);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('election_officer_id')->references('id')->on('election_officers');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('election_events');
    }
};
