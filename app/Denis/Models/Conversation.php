<?php


namespace App\Denis\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation.
 *
 * @property int $id
 * @property int $prototype_id
 * @property string $provider
 * @property int $user_id
 * @property array $payload
 */
class Conversation extends Model
{
    protected $table = 'bot_conversations';
    protected $history = [];
    protected $variables = [];

    protected $fillable = [
        'player_id',
        'provider_user_id',
        'provider',
        'prototype_id',
        'part_done',
        'part_external_data',
        'next_part_id',
    ];

    protected $attributes = [
        'part_external_data' => '[]',
    ];
    
    protected $casts = [
        'part_external_data' => 'array',
    ];

    public function saveVariable($key, $value)
    {
        $payload = $this->getAttribute('payload');
        $this->variables[$key] = $value;
        $payload['variables'] = $this->variables;
        $this->setAttribute('payload', $payload);
        $this->save();
    }

    public function getVariables()
    {
        $v = $this->variables;
        $v['id'] = $this->getAttribute('user_id');
        return $v;
    }
    
    public function playerConnect(UserCard $userCard)
    {
        $this->setAttribute('player_id', $userCard->player_id);
        $this->save();
    }
}