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
        Schema::create('event_category_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('event_category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('start_date', 6)->nullable();
            $table->timestamp('end_date', 6)->nullable();
            $table->text('information')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('created_at', 6)->nullable();
            $table->timestamp('updated_at', 6)->nullable();

            // Add foreign key constraints
            $table->foreign('event_category_id')->references('id')->on('event_categories')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_category_tickets', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['event_category_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
}
};
