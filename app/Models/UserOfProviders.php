<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class UserOfProviders
 * @package App\Models
 * 
 */
class UserOfProviders extends Model
{
    protected $table = 'users_of_providers';
    
    protected $fillable = [
        'provider',
        'provider_user_id',
        'player_id',
        'variables',
        'locked_by_conversation_id'
    ];
    protected $attributes = [
        'variables' => '[]',
    ];
    protected $casts = [
        'variables' => 'array',
    ];
}