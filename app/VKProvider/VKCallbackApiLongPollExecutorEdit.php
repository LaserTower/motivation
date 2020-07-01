<?php


namespace App\VKProvider;


use VK\CallbackApi\LongPoll\Exceptions\VKLongPollServerKeyExpiredException;
use VK\CallbackApi\LongPoll\VKCallbackApiLongPollExecutor;

class VKCallbackApiLongPollExecutorEdit extends VKCallbackApiLongPollExecutor
{
    public function listen(?int $ts = null) {
        if ($this->server === null) {
            $this->server = $this->getLongPollServer();
        }

        if ($this->last_ts === null) {
            $this->last_ts = $this->server[static::SERVER_TIMESTAMP];
        }

        if ($ts === null) {
            $ts = $this->last_ts;
        }

        try {
            $response = $this->getEvents($this->server[static::SERVER_URL], $this->server[static::SERVER_KEY], $ts);
            $fabric = new DenisEntityFabric();
            foreach ($response[static::EVENTS_UPDATES] as $event) {
               yield $fabric->createEntity($this->group_id, null, $event[static::EVENT_TYPE], $event[static::EVENT_OBJECT]);
            }
            $this->last_ts = $response[static::EVENTS_TS];
        } catch (VKLongPollServerKeyExpiredException $e) {
            $this->server = $this->getLongPollServer();
        }
    }
}