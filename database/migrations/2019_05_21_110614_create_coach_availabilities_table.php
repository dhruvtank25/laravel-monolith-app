<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('coach_availabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coach_id');
            $table->integer('position_no');
            $table->string('day');
            $table->time('time_from');
            $table->time('time_to');
            $table->timestamps();
        });*/
        Schema::create('coach_availabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coach_id');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->tinyInteger('is_requestable')->default(1)->comments('Coach controls can user request session on unavailable date');
            $table->date('date_on');
            $table->time('time_from');
            $table->time('time_to');
            $table->enum('recurring', ['single', 'weekly', 'daily'])->default('single');
            $table->integer('recurring_weeks')->default(0)->comments('recurring exists for x no of weeks');
            $table->string('recurring_week_days')->nullable()->comments('comma separated week day numbers');
            $table->date('recurring_end')->nullable();
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
        Schema::dropIfExists('coach_availabilities');
    }
}
