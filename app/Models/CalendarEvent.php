<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $table = 'calendar_events';
    protected $fillable = [
        'title',
        'event_type',
        'description',
        'start_date',
        'end_date',
        'vendor_id',
    ];
}
