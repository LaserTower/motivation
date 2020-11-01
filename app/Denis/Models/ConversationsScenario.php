<?php

namespace App\Denis\Models;

use App\Denis\Parts\CorePart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $bot_id
 * @property string $type
 * @property array $payload
 * @property int $next_part
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $external_id
 */
class ConversationsScenario extends Model
{
    protected $table = 'conversations_scenario';
    protected $parts = [];
    protected $fillable = [
        'name',
        'published_at',
        'published',
        'payload'
    ];

    protected $attributes = [
        'payload' => '[]',
    ];
    protected $casts = [
        'payload' => 'array',
    ];
    
    protected function fillParts()
    {
        $payload = $this->getAttribute('payload');
        foreach ($payload['parts'] as $entity) {
            $this->parts[] = CorePart::create($entity);
        }
    }

    public function getPart($id)
    {
        if(empty($this->parts)){
            $this->fillParts();
        }
        foreach ($this->parts as $part) {
            if ($part->id == $id) {
                return $part;
            }
        }
    }
}
