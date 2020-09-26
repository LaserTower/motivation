<?php


namespace App\Vadim\Models;


use Illuminate\Database\Eloquent\Model;

class AlarmClockPool extends Model
{
    protected $table = 'alarm_clock_pool';

    public $timestamps = false;
    
    protected $fillable = [
        'clock_at',
        'players_program_id',
        'timer_part_id',
        'in_progress',
        'users_of_providers_id',
        'player_id'
    ];
}