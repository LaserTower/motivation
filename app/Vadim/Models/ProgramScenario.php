<?php


namespace App\Vadim\Models;


use Illuminate\Database\Eloquent\Model;

class ProgramScenario extends Model
{
    protected $table = 'program_scenario';

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