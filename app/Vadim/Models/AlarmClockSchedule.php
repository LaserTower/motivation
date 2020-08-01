<?php

namespace App\Vadim\Models;

use Illuminate\Database\Eloquent\Model;

class AlarmClockSchedule extends Model
{
    protected $table = 'alarm_clock_schedule';
    
    protected $fillable = [
        'users_of_providers_id',
        'alarm_clock_prototype_id',
        'clock_external_data'
    ];

    protected $attributes = [
        'clock_external_data' => '[]',
    ];

    protected $casts = [
        'clock_external_data' => 'array',
    ];
}