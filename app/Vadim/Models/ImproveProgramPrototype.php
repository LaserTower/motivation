<?php


namespace App\Vadim\Models;


use Illuminate\Database\Eloquent\Model;

class ImproveProgramPrototype extends Model
{
    protected $table = 'improve_program_prototypes';

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