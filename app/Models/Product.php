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
        'vendor_id',
        'name',
        'slug', 
        'price',
        'compare_price',
        'stock',
        'sku',
        'description',  
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
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
