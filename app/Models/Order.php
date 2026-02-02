<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_no',
        'user_id',
        'subtotal',
        'discounted_price',
        'tax_amount',
        'shipping_amount',
        'grand_total',
        'payment_status',
        'order_status',
        'payment_method',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderAddresses()
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
