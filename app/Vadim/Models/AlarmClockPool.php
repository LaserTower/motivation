<?php


namespace App\Vadim\Models;


use Illuminate\Database\Eloquent\Model;

class AlarmClockPool extends Model
{
    protected $table = 'alarm_clock_pool';
    
    protected $fillable = [
        'clock_at',
        'alarm_clock_schedule_id',
        'timer_part_id',
        'in_progress',
    ];
}