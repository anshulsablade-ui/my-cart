<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_addresses';
    protected $fillable = [
        'order_id', 
        'address_type', 
        'name',  
        'phone', 
        'address', 
        'country_id',
        'state_id', 
        'city_id', 
        'pincode', 
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
