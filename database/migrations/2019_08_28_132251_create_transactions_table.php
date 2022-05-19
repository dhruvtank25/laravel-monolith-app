<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id', 255);
            $table->string('type');
            $table->string('nature')->nullable();
            $table->integer('appointment_id');
            $table->integer('debited_user_id')->nullable();
            $table->string('debited_mango_id')->nullable();
            $table->string('debited_wallet_id')->nullable();
            $table->string('debited_amount')->default('0');
            $table->integer('credited_user_id')->nullable();
            $table->string('credited_mango_id')->nullable();
            $table->string('credited_wallet_id')->nullable();
            $table->string('credited_amount')->default('0');
            $table->string('fees')->default('0');
            $table->string('payment_type')->nullable();
            $table->string('execution_type')->nullable();
            $table->string('payment_card_id')->nullable();
            $table->enum('secure_mode_needed', ['0', '1'])->default('0');
            $table->string('secured_mode_url', 255)->nullable();
            $table->string('status')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_message')->nullable();
            $table->text('payment_response')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
