<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Vadim\Vadim;

class DeployTimers extends CorePart
{
    public $type = 'deploy-timers';
    function execute($provider, $messages, ?Conversation $conversation)
    {
        (new Vadim())->deployTimers($conversation->parent_schedule_id);
        return null;
    }
}