<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'user_id', 'coach_id', 'category_id', 'start', 'end', 'mode', 'location', 'notes', 'status', 'call_duration', 'cancelled_by', 'cancelled_on', 'cancel_fee_percent', 'cancel_fee', 'refund_status', 'price_per_hour', 'amount', 'fee_percent', 'fee', 'coach_credited', 'payout_status', 'payout_message'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start', 'end', 'cancelled_on'];

    /**
     * Get user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * get catogories
     */
    public function categories()
    {
        return $this->belongsTo('App\Models\category','category_id', 'id');
    }

    /**
     * Get coach
     */
    public function coach()
    {
        return $this->belongsTo('App\Models\User', 'coach_id')->withTrashed();
    }

    /**
     * Get appointment activities.
     */
    public function activities()
    {
        return $this->morphMany('App\Models\ActivityLog', 'activitable')->orderBy('created_at','desc');
    }

    /**
     * Get Appointment call logs
     */
    public function callLogs()
    {
        return $this->hasMany('App\Models\CallLog');
    }

    /**
     * Get Appointment Review
     */
    public function review()
    {
        return $this->hasOne('App\Models\CoachReview', 'appointment_id');
    }

    /**
     * Get Sessions which ended before x days
     */
    public function scopeEndedBefore($query, $days)
    {
        return $query->where('status', 'completed')->where('end', '<=', Carbon::now('Europe/Berlin')->subDays($days)->format('Y-m-d 23:59:59'));
    }

    public function isTransferrableAttribute()
    {
        if($this->amount<=0 || $this->coach_credited>0 || $this->status!='completed')
            return false;
        else
            return true;
    }

    public function isPayableAttribute()
    {
        if($this->payout_status=='processing' || $this->payout_status=='paid' || $this->coach_credited<=0)
            return false;
        else
            return true;
    }

    public function isRefundableAttribute()
    {
        if($this->status!='cancelled' || $this->refund_status!='unpaid' || $this->amount<=0)
            return false;
        else
            return true;
    }

    public function getCostCalculationAttribute()
    {
        // NET AMOUT FORMULAE: (100*Gross Amount)/(100+VAT%)
        $price_per_hour = $this->price_per_hour;
        $vat_percent    = env('VAT_PERCENT', 19);
        if($this->created_at->lt('2020-07-01')) {
            $vat_percent = 19;   
        }
        $net_per_hr     = round((100*$price_per_hour)/(100+$vat_percent), 3);
        $duration_min   = $this->start->diffInMinutes($this->end);
        $duration_hr    = $duration_min/60;
        $final_net_cost = round($net_per_hr*$duration_hr, 3);

        // User cost calculations
        $final_vat_cost = round($final_net_cost*($vat_percent/100), 3);
        $gross_cost     = round($final_net_cost+$final_vat_cost, 3);
        
        // Commision from coach calculation
        calc_commision:
        if(!$this->fee_percent || $this->fee_percent==0)
            $commission_percent = 20;
        else
            $commission_percent = $this->fee_percent;

        // Calculate Vat and gross
        $commission_amount  = round($commission_percent*($final_net_cost/100), 3);
        $commission_vat     = round($commission_amount*($vat_percent/100), 3);
        $gross_commission   = round($commission_vat+$commission_amount, 3);
        $coach_total        = $gross_cost-$gross_commission;

        // Coach cost cancellation and commission in case of refund
        if($this->status=='cancelled' && $this->cancel_fee_percent>0) {
            $coach_credit_per = $this->cancel_fee_percent;
            $user_credit_per  = 100-$coach_credit_per;

            // User return calculations
            $user_return_net  = round($final_net_cost*($user_credit_per/100), 3);
            $user_return_vat  = round($user_return_net*($vat_percent/100), 3);
            $user_return_gross= round($user_return_net+$user_return_vat, 3);

            // Coach return calculation
            $coach_return_net        = round($final_net_cost*($coach_credit_per/100), 3);
            $return_commission       = round($commission_percent*($coach_return_net/100), 3);
            $return_commission_vat   = round($return_commission*($vat_percent/100), 3);
            $gross_return_commission = round($return_commission_vat+$return_commission, 3);
            $coach_return_gross      = $coach_return_net+round($coach_return_net*($vat_percent/100), 3);
            $coach_return_total      = $coach_return_gross-$gross_return_commission;
        } else {
            $coach_credit_per = $coach_return_net = $return_commission = $return_commission_vat  = $coach_return_gross = $coach_return_total = 0;
            $user_credit_per         = 100;
            $user_return_net         = $final_net_cost;
            $user_return_vat         = $final_vat_cost;
            $user_return_gross       = $gross_cost;
            $gross_return_commission = $gross_commission;
        }
        // If commision is less than 2.5€, fix it to 2.5€ and figure out percent charged
        if($gross_return_commission<2.5 && $this->cancelled_by!='coach') {
            // commision percent = (100*fixed commision)/(Total cost or total cancel fee)
            $new_commission_percent = 100*2.50/($this->status=='cancelled'?$coach_return_gross:$gross_cost);
            $this->fee_percent = $new_commission_percent;
            $this->save();
            goto calc_commision;
        }
        
        return  [
                    'price_per_hour'          => $price_per_hour,
                    'vat_percent'             => $vat_percent,
                    'net_per_hr'              => $net_per_hr,
                    'duration_min'            => $duration_min,
                    // Invoice Calculation for user
                    'final_net_cost'          => $final_net_cost,
                    'final_vat_cost'          => $final_vat_cost,
                    'gross_cost'              => $gross_cost,
                    // Invoice Calculation for coach
                    'commission_percent'      => $commission_percent,
                    'commission_amount'       => $commission_amount,
                    'commission_vat'          => $commission_vat,
                    'gross_commission'        => $gross_commission,
                    'coach_total'             => $coach_total,
                    // Return Calculation for user
                    'user_credit_per'         => $user_credit_per,  // Percent returned
                    'user_return_net'         => $user_return_net,
                    'user_return_vat'         => $user_return_vat,
                    'user_return_gross'       => $user_return_gross,
                    // Return Calculation for coach
                    'coach_credit_per'        => $coach_credit_per, // Percent deducted
                    'coach_return_net'        => $coach_return_net,
                    'return_commission'       => $return_commission,
                    'return_commission_vat'   => $return_commission_vat,
                    'gross_return_commission' => $gross_return_commission,
                    'coach_return_gross'      => $coach_return_gross,
                    'coach_return_total'      => $coach_return_total,
                ];
    }

    public function getFormattedCostCalculationAttribute($value='')
    {
        $coach_calc_arr = $this->getCostCalculationAttribute();
        $cost_calculation = array();
        foreach ($coach_calc_arr as $key => $value) {
            if($key!='duration_min')
                $cost_calculation[$key] = number_format($value, 2, ',', '');
            else
                $cost_calculation[$key] = $value;
        }
        return $cost_calculation;
    }

}
