<?php

namespace App\Models;

use App\Traits\SlugGenerator;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use SlugGenerator;
    protected $table = 'brands';
    protected $fillable = ['name', 'slug', 'logo', 'status'];
    protected $slugSourceField = 'name';
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
