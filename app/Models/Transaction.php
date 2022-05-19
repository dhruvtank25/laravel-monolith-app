<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id', 'type', 'nature', 'appointment_id', 'debited_user_id', 'debited_mango_id', 'debited_wallet_id', 'debited_amount', 'credited_user_id', 'credited_mango_id', 'credited_wallet_id', 'credited_amount', 'fees', 'payment_type', 'execution_type', 'payment_card_id', 'secure_mode_needed', 'secured_mode_url', 'status', 'result_code', 'result_message', 'payment_response'
    ];

    /**
     * Get appointment
     */
    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment');
    }

    public function getCreditedAmountAttribute($value)
    {
        return $value/100;
    }

    public function getFeesAttribute($value)
    {
        return $value/100;
    }

}
