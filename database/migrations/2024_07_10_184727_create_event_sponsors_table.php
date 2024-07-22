<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_sponsors', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key

            $table->string('name', 255); // Sponsor name
            $table->string('logo')->nullable(); // Logo for the sponsor, can be NULL
            $table->text('information')->nullable(); // Additional information about the sponsor, can be NULL
            $table->string('url_link', 255)->nullable();
            $table->timestamps(); // Created at and updated at timestamps

            // Primary key constraint
            $table->primary('id', 'sponsors_pkey');

            // Index for better query performance (optional, but recommended if querying by `name`)
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_sponsors');
    }
}
