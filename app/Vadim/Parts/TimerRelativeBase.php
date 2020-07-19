<?php


namespace App\Vadim\Parts;


class TimerRelativeBase implements \JsonSerializable
{
    public $bot_id;
    public $base;
    public $interval;
    public $type = 'relative-base';

    public function __construct($bot_id = null, $base = null, $interval = null)
    {
        $this->bot_id = $bot_id;
        $this->base = $base;
        $this->interval = $interval;
    }

    public function jsonSerialize()
    {
        return [
            'bot_id' => $this->bot_id,
            'base' => $this->base,
            'interval' => $this->interval,
            'type' => $this->type
        ];
    }
}