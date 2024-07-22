<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSessionSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_session_sponsors', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key

            // Foreign key relationship with the event_sessions table
            $table->bigInteger('event_session_id')->unsigned();
            $table->foreign('event_session_id')
                ->references('id')->on('event_sessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Foreign key relationship with the event_sponsors table
            $table->bigInteger('event_sponsor_id')->unsigned();
            $table->foreign('event_sponsor_id')
                ->references('id')->on('event_sponsors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamps(); // Created at and updated at timestamps

            // Primary key constraint
            $table->primary('id', 'event_session_sponsors_pkey');

            // Indexes for better query performance
            $table->index('event_session_id');
            $table->index('event_sponsor_id');

            // Unique constraint for the combination of `event_session_id` and `event_sponsor_id`
            $table->unique(['event_session_id', 'event_sponsor_id'], 'event_session_sponsors_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_session_sponsors');
    }
}
