<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id',
        'address_type',
        'name',
        'phone',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'pincode',
        'is_primary',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }   
}
