<?php

namespace App\Denis\Parts;

use App\Denis\Models\Conversation;

class GoToBot extends CorePart
{
    public $type = 'goto';
    public $scenario_id;
    public $next_id;

    public static $fields = [
        'scenario_id',
        'next_id',
    ];
    
    function execute($provider, $messages, ?Conversation $conversation)
    {
        // TODO: Implement execute() method.
    }
}