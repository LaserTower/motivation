<?php


namespace App\Denis\Parts;


class CallAManager extends CorePart
{
    public $type = 'call-manager';
    public $message; //
    public $manager_id;

    public function __construct($id = null, $message = null)
    {
        $this->id = $id;
        $this->message = $message;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'message' => $this->message
        ];
    }

    public function execute($provider, $message, $conversation)
    {
        //прям здесь по диалогу определяется кому нужно послать сообщение
        //$manager_id=21142746;
        $manager_id = 33100912;

        $variables = [
            'id' => $provider->getUserId()
        ];

        array_walk($variables, function (&$value) {
            return "\{\{$value\}\}";
        });
        $this->message = str_replace(array_keys($variables), array_values($variables), $this->message);


        $newmessage = new Message(0, 0, $this->message);
        $newmessage->user_id = $manager_id;
        $provider->transmit($newmessage);
        return null;
    }
}