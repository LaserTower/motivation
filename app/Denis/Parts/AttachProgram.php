<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Vadim\Vadim;

class AttachProgram extends CorePart
{
    public $type = 'attach-program';
    public $alarm_clock_prototype_id;
    public $next;

    public static $fields = [
        'alarm_clock_prototype_id',
        'next',
    ];
    
    function execute($provider, $messages, ?Conversation $conversation)
    {
        (new Vadim())->attachAlarmsToUserProvider($conversation->user_of_provider_id, $this->alarm_clock_prototype_id);
    }
}