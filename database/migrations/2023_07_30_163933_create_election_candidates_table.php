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
        Schema::create('election_candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_event_id');
            $table->unsignedBigInteger('election_position_id');
            $table->unsignedBigInteger('candidate_id');
            $table->dateTime('activated_at')->nullable()->default(null);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('election_event_id')->references('id')->on('election_events');
            $table->foreign('election_position_id')->references('id')->on('election_positions');
            $table->foreign('candidate_id')->references('id')->on('candidates');
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
        Schema::dropIfExists('election_candidates');
    }
};
