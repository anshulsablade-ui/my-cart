<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = ['sortname','name', 'phonecode', 'status'];

    public function states()
    {
        return $this->hasMany(State::class);
    }

}
