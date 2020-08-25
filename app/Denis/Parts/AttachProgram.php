<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;
use App\Vadim\Vadim;

class AttachProgram extends CorePart
{
    public $type = 'attach-program';
    public $alarm_clock_prototype_id;
    public $next;

    public function __construct($id = null, $next = null, $alarm_clock_prototype_id = null)
    {
        $this->id = $id;
        $this->alarm_clock_prototype_id = $alarm_clock_prototype_id;
        $this->next = $next;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'alarm_clock_prototype_id' => $this->alarm_clock_prototype_id,
            'next' => $this->next
        ];
    }
    
    function execute($provider, $messages, ?Conversation $conversation)
    {
        (new Vadim())->attachAlarmsToUserProvider($conversation->user_of_provider_id, $this->alarm_clock_prototype_id);
    }
}