<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('appointment_request_id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('mode', ['online', 'offline'])->default('online');
            $table->text('location')->nullable();
            $table->enum('requested_by', ['coach', 'user'])->default('user');
            $table->enum('status', ['accepted', 'rejected'])->nullable();
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
        Schema::dropIfExists('request_slots');
    }
}
