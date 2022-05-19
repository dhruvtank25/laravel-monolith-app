<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_id')->nullable()->comment('Invoice id from reviso');
            $table->integer('user_id');
            $table->integer('coach_id');
            $table->integer('category_id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('mode', ['online', 'offline'])->default('online');
            $table->text('location')->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('status', ['payment pending', 'payment processing', 'payment failed', 'scheduled', 'cancelled', 'completed'])->default('payment pending');
            $table->integer('call_duration')->comment('Call duration in mins applicable for online mode')->default(0);
            $table->dateTime('user_updated_on')->nullable()->comment('Only 1 update allowed for user');
            $table->text('user_update_comment')->nullable();
            $table->enum('cancelled_by', ['user', 'coach'])->nullable();
            $table->dateTime('cancelled_on')->nullable();
            $table->decimal('cancel_fee_percent', 8, 2)->default(0);
            $table->decimal('cancel_fee', 8, 2)->default(0);
            $table->enum('refund_status', ['unpaid', 'processing', 'paid', 'failed']);
            $table->integer('price_per_hour')->default(1);
            $table->decimal('amount', 8, 2)->default(0);
            $table->decimal('fee_percent', 3, 2)->default(0);
            $table->decimal('fee', 8, 2)->default(0);
            $table->decimal('coach_credited', 8, 2)->default(0);
            $table->enum('payout_status', ['unpaid', 'processing', 'paid', 'failed'])->default('unpaid');
            $table->text('payout_message')->nullable();
            $table->dateTime('credit_redeemed_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
