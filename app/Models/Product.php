<?php

namespace App\Models;

use App\Traits\SlugGenerator;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SlugGenerator;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'base_price',
        'discount_percentage',
        'discounted_price',
        'final_price',
        'stock',
        'status'
    ];
    protected $slugSourceColumn = 'name';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', 1)
            ->select('id', 'product_id', 'image', 'is_primary');
    }

}
