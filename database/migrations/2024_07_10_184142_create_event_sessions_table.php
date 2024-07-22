<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('event_id')->unsigned(); // Unsigned for foreign key relations
            $table->string('name');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location', 255);
            $table->timestamps();

            // Adding foreign key constraints
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Adding a unique constraint on the combination of `event_id`, `date`, `start_time`
            $table->unique(['event_id', 'date', 'start_time']);

            // Adding an index on the `event_id` column for better query performance
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_sessions');
    }
}
