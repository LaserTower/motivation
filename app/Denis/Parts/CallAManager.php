<?php


namespace App\Denis\Parts;

class CallAManager extends CorePart
{
    use ApplyVariables;
    public $type = 'call-manager';
    public $message_for_manager; 
    public $manager_id;
    public $next;

    public static $fields = [
        'message_for_manager',
        'next'
    ];

    public function execute($provider, $message, $conversation)
    {
        //прям здесь по диалогу определяется кому нужно послать сообщение
        //$manager_id = 33100912;
        $manager_id = 3697315;// (Александр Князев)
        $newmessage = new Message;
        $newmessage->user_id = $manager_id;
        $newmessage->body = $this->formatVariables($this->message_for_manager, $conversation->getVariables());
        $provider->transmit($newmessage);
      
        return $this->next;
    }
}