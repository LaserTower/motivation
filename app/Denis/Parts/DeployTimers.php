<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Vadim\Vadim;

class DeployTimers extends CorePart
{
    function execute($provider, $messages, ?Conversation $conversation)
    {
        (new Vadim())->deployTimers($conversation->part_external_data['scheduler_id']);
        return null;
    }
}