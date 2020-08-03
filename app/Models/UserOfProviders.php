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

            $temp = [
                't' => time(),
            ];

            if (is_array($value)) {
                $temp['vid'] = $value[0];
                $temp['v'] = $value[1];
            } else {
                $temp['v'] = $value;
            }

            $var['many'][$key][] = $temp;
        } else {
            $var['once'][$key] = $value;
        }

        $this->setAttribute('variables', $var);
        $this->save();
    }
}