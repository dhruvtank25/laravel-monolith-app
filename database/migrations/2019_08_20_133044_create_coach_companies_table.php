<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coach_id')->comment('user id');
            $table->integer('company_id');
            $table->string('company_name')->nullable();
            $table->date('joining_date');
            $table->string('designation');
            $table->string('document', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_companies');
    }
}
