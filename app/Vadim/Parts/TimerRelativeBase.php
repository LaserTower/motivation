<?php


namespace App\Vadim\Parts;


use App\Models\UserOfProviders;
use DateTimeZone;


class TimerRelativeBase implements \JsonSerializable
{
    public $id;
    public $bot_id;
    public $base;
    public $interval;
    public $type = 'relative-base';

    public function __construct($id = null, $bot_id = null, $base = null, $interval = null)
    {
        $this->id = $id;
        $this->bot_id = $bot_id;
        $this->base = $base;
        $this->interval = $interval;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'base' => $this->base,
            'interval' => $this->interval,
            'type' => $this->type
        ];
    }

    public function getTimer($scheduler)
    {
        $userOfProvider = UserOfProviders::find($scheduler->users_of_providers_id);
        $variables = $userOfProvider->getVariables();

        //$base='23:18';
        $base = $variables['once'][$this->base];

        $tz = $variables['once']['timezone'];

        $date = \DateTime::createFromFormat('G:i', $base);
        $date->modify($this->interval);
        $date->modify('-'.$tz.' hour');
        return $date->format('Y-m-d H:i:s');
    }
}