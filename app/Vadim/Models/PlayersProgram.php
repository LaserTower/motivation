<?php

namespace App\Vadim\Models;

use Illuminate\Database\Eloquent\Model;

class PlayersProgram extends Model
{
    protected $table = 'players_program';
    
    protected $fillable = [
        'users_of_providers_id',
        'program_scenario_id',
        'clock_external_data'
    ];

    protected $attributes = [
        'clock_external_data' => '[]',
    ];

    protected $casts = [
        'clock_external_data' => 'array',
    ];
}