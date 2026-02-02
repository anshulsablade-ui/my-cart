<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    protected $fillable = ['email', 'token', 'created_at'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
