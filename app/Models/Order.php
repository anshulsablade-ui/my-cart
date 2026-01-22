<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_no', 
        'user_id', 
        'sub_total', 
        'discount_percentage', 
        'discounted_price',
        'tax_amount',
        'shipping_amount',
        'grand_total',
        'payment_status',
        'order_status', 
        'payment_method'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
