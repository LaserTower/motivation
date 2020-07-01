<?php


namespace App\Denis\Parts;


use App\Denis\Core;

abstract class CorePart
{
    public $id;
    public $from = 'bot';
    public $user_id; //
    public $type;
    public $date;
    public $externalData;

    const BINDINGS = [
        'call-manager' => CallAManager::class,
        'condition' => Condition::class,
        'pick-data' => PickData::class,
        'user-choice' => UserChoice::class,
        'message-text' => Message::class
    ];

    abstract function execute(Core $denis, ?CorePart $message);

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