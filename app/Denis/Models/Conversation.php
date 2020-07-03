<?php


namespace App\Denis\Models;


use App\Denis\Parts\CorePart;
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
        'user_id',
        'provider',
        'prototype_id',
        'payload'
    ];

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($model) {
            $model->fillHistory();
            $model->fillVariables();
        });
    }

    protected function fillHistory()
    {
        $payload = $this->getAttribute('payload');
        $this->history = [];
        foreach ($payload['history'] as $entity) {
            $this->history[] = CorePart::fill($entity);
        }
    }

    protected function fillVariables()
    {
        $payload = $this->getAttribute('payload');
        $this->variables = $payload['variables'] ?? [];
    }

    protected $attributes = [
        'payload' => '[]',
    ];
    protected $casts = [
        'payload' => 'array',
    ];

    public function saveEntity(CorePart $message)
    {
        $payload = $this->getAttribute('payload');
        $this->history[] = $payload['history'][] = $message;
        $this->setAttribute('payload', $payload);
        $this->save();
    }

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
    
    public function getHistory()
    {
        return $this->history;
    }

    public function lastBotMessage()
    {
        $lastEntity = null;

        foreach ($this->history as $entity) {
            if ($entity->from == 'bot') {
                $lastEntity = $entity;
            }
        }
        return $lastEntity;
    }
}