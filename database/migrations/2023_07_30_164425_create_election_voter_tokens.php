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
        Schema::create('election_voter_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_event_id');
            $table->unsignedBigInteger('election_position_id')->nullable()->default(null);
            $table->unsignedBigInteger('voter_id');
            $table->string('token')->unique();
            $table->dateTime('token_valid_from');
            $table->dateTime('token_valid_to');
            $table->dateTime('token_expired_at')->nullable()->default(null);
            $table->boolean('email_sent')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('election_event_id')->references('id')->on('election_events');
            $table->foreign('election_position_id')->references('id')->on('election_positions');
            $table->foreign('voter_id')->references('id')->on('voters');
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
        Schema::dropIfExists('election_voter_tokens');
    }
};
