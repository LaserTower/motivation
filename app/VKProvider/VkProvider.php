<?php


namespace App\VKProvider;

use App\Denis\Parts\CallAManager;
use App\Denis\Parts\CorePart;
use App\VKProvider\Parts\Message;
use App\VKProvider\Parts\UserChoice;
use App\VKProvider\Parts\PickData;

class VkProvider
{
    const BINDINGS = [
        'user-choice-once' => UserChoice::class,
        'user-choice-program' => UserChoice::class,
        'pick-data-once' => PickData::class,
        'pick-data-program' => PickData::class,
        'message-text' => Message::class,
        'call-manager' => CallAManager::class,
        'timezone' => PickData::class,
    ];
    
    public $name = 'vk';
    
    public function transmit(CorePart $part)
    {
        $class = self::BINDINGS[$part->type];
        $helper = new $class();
        $helper->transmit($part);
    }
}