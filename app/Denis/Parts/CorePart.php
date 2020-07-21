<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;

abstract class CorePart
{
    public $id;
    public $from = 'bot';
    public $user_id;
    public $player_id;
    public $user_of_provider_id;
    public $type;
    public $date;
    public $done = true;
    public $externalData = [];

    const BINDINGS = [
        'call-manager' => CallAManager::class,
        'condition' => Condition::class,
        'pick-data' => PickData::class,
        'user-choice' => UserChoice::class,
        'message-text' => Message::class,
        'denis-auth' => Auth::class,
    ];

    abstract function execute($provider, $messages,?Conversation $conversation);

    public static function fill($data)
    {
        $class = self::BINDINGS[$data['type']];
        $part = new $class();
        foreach ($data as $key => $value) {
            $part->{$key} = $value;
        }
        return $part;
    }
}