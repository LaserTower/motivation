<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PlayerCard extends Model
{
    protected $table = 'player_card';
    protected $primaryKey = 'player_id';
    protected $fillable = [
        'player_id',
        'variables',
        'alarm_clock_properties',
        'email',
        'phone',
        'oauth'
    ];

    protected $attributes = [
        'variables' => '[]',
        'alarm_clock_properties' => '[]',
    ];
    protected $casts = [
        'variables' => 'array',
        'alarm_clock_properties' => 'array',
    ];
}