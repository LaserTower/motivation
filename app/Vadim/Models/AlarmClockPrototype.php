<?php


namespace App\Vadim\Models;


use Illuminate\Database\Eloquent\Model;

class AlarmClockPrototype extends Model
{
    protected $table = 'alarm_clock_prototypes';

    protected $fillable = [
        'name',
        'settings_bot_id',
        'payload',
    ];

    protected $attributes = [
        'payload' => '[]',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}