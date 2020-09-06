<?php

namespace App\Denis\Parts;

use App\Denis\Models\Conversation;

class GoToBot extends CorePart
{
    public $type = 'goto-bot';
    
    function execute($provider, $messages, ?Conversation $conversation)
    {
        // TODO: Implement execute() method.
    }
}