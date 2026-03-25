<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSessionMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_session_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_session_id')
                ->constrained('event_sessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('material_name');
            $table->text('material_description')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_type')->nullable();
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
        Schema::dropIfExists('event_session_materials');
    }
}
