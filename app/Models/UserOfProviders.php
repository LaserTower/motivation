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

    public function getVariables(): array
    {
        if (is_null($this->player_id)) {
            return $this->getAttribute('variables');
        }
    }

    public function saveVariable($key, $value, $many)
    {
        $var = $this->getAttribute('variables');
        if ($many) {
            $var[$key][] = [
                't' => time(),
                'v' => $value
            ];
        } else {
            $var[$key] = $value;
        }

        $this->setAttribute('variables', $var);
        $this->save();
    }
}