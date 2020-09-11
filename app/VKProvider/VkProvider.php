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
        'user-choice' => UserChoice::class,
        'pick-data' => PickData::class,
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