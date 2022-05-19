<?php

namespace App\Models;

use App\Events\UserSaved;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\UserRegisteredNotification;
use App\Notifications\MailResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes;
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status', 'role_id', 'can_login', 'mango_user_id', 'mango_wallet_id', 'first_name', 'last_name', 'phone_number', 'email', 'birth_date', 'nationality', 'user_name', 'is_anonymous', 'latitude', 'longitude', 'street', 'house_no', 'post_code', 'place', 'country', 'country_code', 'different_work', 'work_latitude', 'work_longitude', 'work_street', 'work_house_no', 'work_post_code', 'work_place', 'work_country', 'work_country_code', 'show_on_map', 'community', 'language', 'priorities', 'coaching_method', 'price_per_hour', 'description', 'coach_company', 'is_commercial', 'person_type', 'small_business', 'tax_number', 'ust_id', 'company_type', 'company_number', 'impressum', 'commercial_doc', 'ustid_doc', 'avatar', 'banner', 'video', 'id_doc', 'bank_account_id', 'owner_name', 'iban', 'bic', 'agree_copyright', 'agree_ustid', 'password', 'terms_condition', 'privacy_policy', 'agree_credentials', 'kyc_status', 'kyc_message', 'ubo_status', 'kyc_message'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birth_date',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved'  => UserSaved::class,
    ];

    /** Save without firing save/update event */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    /**
     * Get user role
     */
    public function roles()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    /**
     * Get user categories
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'coach_categories', 'coach_id', 'category_id');
        //return $this->hasMany('App\Models\CoachCategory', 'coach_id');
    }

    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'coach_companies', 'coach_id', 'company_id')
            ->withPivot('coach_id', 'company_id', 'company_name', 'joining_date', 'designation',  'document')
            ->orderBy('coach_companies.joining_date', 'desc');
    }

    public function shareholders()
    {
        return $this->hasMany('App\Models\Shareholder', 'user_id');
    }

    /**
     * Get Coach Appointments
     */
    public function appointments()
    {
        return $this->hasMany('App\Models\Appointment', 'coach_id')->whereNotIN('status', ['payment cancelled', 'cancelled'])->orderBy('start', 'asc');
    }

    /**
     * Get user availabilities
     */
    public function availabilites()
    {
        return $this->hasMany('App\Models\CoachAvailability', 'coach_id');
    }

    /**
     * Get user unavailabilities
     */
    public function unavailabilities()
    {
        return $this->hasMany('App\Models\CoachUnavailability', 'coach_id')->orderBy('unavailable_from')->orderBy('unavailable_to');
    }

    /**
     * Get user activities.
     */
    public function activities()
    {
        return $this->morphMany('App\Models\ActivityLog', 'activitable')->orderBy('created_at','desc');
    }

    /**
     * Get user reviews
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\CoachReview', 'coach_id')->with('user:id,first_name,last_name')->orderBy('created_at', 'desc');
    }

    /**
     * Set the user's Language.
     *
     * @param  string  $value
     * @return void
     */
    public function setLanguageAttribute($value='')
    {
        $this->attributes['language'] = $value?htmlspecialchars($value):null;
    }

    /**
     * Set the user's Priorities.
     *
     * @param  string  $value
     * @return void
     */
    public function setPrioritiesAttribute($value)
    {
        $this->attributes['priorities'] = $value?htmlspecialchars($value):null;
    }

    /**
     * Get the user's Language.
     *
     * @param  string  $value
     * @return void
     */
    /*public function getLanguageAttribute($value)
    {
        return stripslashes($value);
    }*/

    /**
     * Get the user's Priorities.
     *
     * @param  string  $value
     * @return void
     */
    /*public function getPrioritiesAttribute($value)
    {
        return stripslashes($value);
    }*/

    /**
     * Get the user avatar.
     *
     * @param  string  $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if($value==null){
            return 'default.jpg';
        }else{
            return $value;
        }
    }

    /**
     * Get the user banner.
     *
     * @param  string  $value
     * @return string
     */
    public function getBannerAttribute($value)
    {
        if($value==null){
            return 'default.jpg';
        }else{
            return $value;
        }
    }


     /**
     * Get the user with distance.
     *
     * @param  string  $value
     * @return string
     */
    public function isWithinMaxDistance( $coordinates, $distance)
    {
        $haversine = "(6371 * acos(cos(radians(" . $coordinates['latitude'] . ")) 
                        * cos(radians(`latitude`)) 
                        * cos(radians(`longitude`) 
                        - radians(" . $coordinates['longitude'] . ")) 
                        + sin(radians(" . $coordinates['latitude'] . ")) 
                        * sin(radians(`latitude`))))";

        return $this ->selectRaw("{$haversine} AS distance")
                        ->whereRaw("{$haversine} < ?", [$distance]);
    }

    public function getProfileCompleteAttribute()
    {
        $user = $this;
        $complete = true;
        if(!$user->nationality || !$user->birth_date || !$user->email || !$user->first_name || !$user->last_name || !$user->id_doc)
            $complete = false;
        if(!$user->owner_name || !$user->iban || !$user->bic)
            $complete = false;
        if($user->companies->count()==0 || $user->categories->count()==0 || !$user->language || !$user->priorities || !$user->description)
            $complete = false;
        if(!$user->coach_company || !$user->latitude || !$user->street || !$user->post_code || !$user->place || !$user->country_code || (!$user->tax_number && !$user->ust_id))
            $complete = false;
        if($user->is_commercial && !$user->ustid_doc)
            $complete = false;
        if($user->person_type=='business' && (!$user->company_number || !$user->commercial_doc || !$user->ustid_doc || $user->shareholders->count()==0))
            $complete =false;
        return $complete;
    }

    public function sendEmailVerificationNotification()
    {
        $user = $this;
        $this->notify(new UserRegisteredNotification($user));
    }
    
    public function sendPasswordResetNotification($token)
    {
        $user = $this;
        $this->notify(new MailResetPasswordNotification($user, $token));
    }

}
