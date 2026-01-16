<?php

namespace App\Models;

use App\Traits\SlugGenerator;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use SlugGenerator;
    protected $table = 'vendors';
    protected $fillable = [
        'name', 
        'slug',
        'email', 
        'phone', 
        'address', 
        'status'
    ];

    protected $slugSourceColumn = 'name';

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
