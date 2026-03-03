<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = ['user_id', 'session_id', 'product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartCount()
    {
        return Cart::where('user_id', session()->get('user.id'))
            ->whereHas('product', function ($q) {
                $q->where('status', 'active')->where('stock', '>', 0);
            })
            ->count();
    }
}
