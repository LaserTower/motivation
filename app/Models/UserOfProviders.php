<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserOfProviders extends Model
{
    protected $table = 'users_of_providers';
    
    protected $fillable = [
        'provider',
        'provider_user_id',
        'player_id',
        'variables',
    
    ];
    protected $attributes = [
        'variables' => '[]',
    ];
    protected $casts = [
        'variables' => 'array',
    ];
}