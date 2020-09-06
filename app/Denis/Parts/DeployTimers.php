<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;
use App\Vadim\Vadim;

class DeployTimers extends CorePart
{
    public $type = 'deploy-timers';
    public $next;

    public static $fields = [
        'next',
    ];
    
    function execute($provider, $messages, ?Conversation $conversation)
    {
        (new Vadim())->deployTimers($conversation->parent_schedule_id);
        return $this->next;
    }
}