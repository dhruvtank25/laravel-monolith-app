<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('coach_id');
            $table->integer('appointment_id')->default(0);
            $table->integer('category_id');
            $table->mediumText('notes')->nullable();
            $table->integer('price_per_hour')->default(1);
            $table->enum('status', ['user_requested', 'coach_accepted', 'coach_rejected', 'coach_suggested', 'user_accepted',  'user_rejected'])->default('user_requested');
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
        Schema::dropIfExists('appointment_requests');
    }
}
