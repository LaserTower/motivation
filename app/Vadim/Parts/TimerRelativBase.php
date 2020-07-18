<?php


namespace App\Vadim\Parts;


class TimerRelativBase implements \JsonSerializable
{
    public $bot_id;
    public $base;
    public $interval;

    public function __construct($bot_id,$part_id, $base, $interval)
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
            'interval' => $this->interval
        ];
    }
}