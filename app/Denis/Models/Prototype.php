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
class Prototype extends Model
{
    protected $table = 'bot_prototypes';
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

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($model) {
            $model->fillParts();
        });
    }

    protected function fillParts()
    {
        $payload = $this->getAttribute('payload');
        foreach ($payload['parts'] as $entity) {
            $this->parts[] = CorePart::fill($entity);
        }
    }

    public function getPart($id)
    {
        foreach ($this->parts as $part) {
            if ($part->id == $id) {
                return $part;
            }
        }
    }

}
