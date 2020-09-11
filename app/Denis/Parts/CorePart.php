<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use phpDocumentor\Reflection\Types\Self_;

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
    public static $fields = [];
    
    protected static $fieldDefault = [
        'id' ,
        'type',
    ];

    const BINDINGS = [
        'message-text' => Message::class,
        'call-manager' => CallAManager::class,
        'condition' => Condition::class,
        'pick-data' => PickData::class,
        'user-choice' => UserChoice::class,
        'denis-auth' => Auth::class,
        'deploy-timers' => DeployTimers::class,
        'timezone' => TimeZone::class,
        'attach-program' => AttachProgram::class,
    ];

    abstract function execute($provider, $messages,?Conversation $conversation);
    
    public static function getFields()
    {
        return array_merge(self::$fieldDefault, static::$fields);    
    }

    public static function create($data)
    {
        $class = self::BINDINGS[$data['type']];
        $part = new $class();
        $part->fill($data);
        return $part;
    }

    public  function fill($data)
    {
        foreach (static::getFields() as $name) {
            if(!isset($data[$name])){
                continue;
            }
            $this->{$name} = $data[$name];
        }
    }
}