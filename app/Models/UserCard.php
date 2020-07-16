<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $table = 'user_card';
    protected $primaryKey = 'player_id';
    protected $fillable = [
        'player_id',
        'variables',
        'email',
        'phone',
        'oauth'
    ];

    protected $attributes = [
        'variables' => '[]',
    ];
    protected $casts = [
        'variables' => 'array',
    ];
}