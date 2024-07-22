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
        Schema::create('election_vote_casts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_event_id');
            $table->unsignedBigInteger('election_position_id');
            $table->unsignedBigInteger('election_candidate_id')->nullable()->default(null);
            $table->unsignedBigInteger('voter_id');
            $table->enum('voted', ['Y','N'])->default('N');
            $table->dateTime('voted_at')->nullable()->default(now());
            $table->string('token')->nullable()->default(null);
            $table->unsignedBigInteger('status_id')->nullable()->default(null);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('election_event_id')->references('id')->on('election_events');
            $table->foreign('election_position_id')->references('id')->on('election_positions');
            $table->foreign('election_candidate_id')->references('id')->on('election_candidates');
            $table->foreign('voter_id')->references('id')->on('voters');
            $table->foreign('status_id')->references('id')->on('lkup_status');
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
        Schema::dropIfExists('election_vote_casts');
    }
};
