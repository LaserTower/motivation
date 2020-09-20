<?php


namespace App\Denis\Models;


use App\Models\UserOfProviders;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation.
 *
 * @property int $id
 * @property int $prototype_id
 * @property string $provider
 * @property int $user_id
 * @property int $user_of_provider_id
 * @property int $parent_schedule_id
 * @property array $payload
 */
class Conversation extends Model
{
    protected $table = 'conversations';
    protected $history = [];
    protected $variables = [];

    protected $fillable = [
        'user_of_provider_id',
        'prototype_id',
        'next_part_id',
        'part_external_data',
        'parent_schedule_id',
    ];

    protected $attributes = [
        'part_external_data' => '[]',
    ];

    protected $casts = [
        'part_external_data' => 'array',
    ];

    public function saveVariable($key, $value, $once = true)
    {
        $uop = UserOfProviders::find($this->user_of_provider_id);
        $uop->saveVariable($key, $value, $once);
    }

    public function getVariables()
    {
        $uop = UserOfProviders::find($this->user_of_provider_id);
        return $uop->getVariables();
    }

    public function getVariable($name)
    {
        $variables = $this->getVariables();
        $test = null;
        if (array_key_exists($name, $variables['many'])) {
            $test = end($variables['many'][$name]);
        }

        if (array_key_exists($name, $variables['once'])) {
            $test = $variables['once'][$name];
        }
        return $test;
    }

    public function playerConnect(UserCard $userCard)
    {
        $this->setAttribute('player_id', $userCard->player_id);
        $this->save();
    }

    public function userId()
    {
        return UserOfProviders::find($this->user_of_provider_id)->provider_user_id;
    }
}