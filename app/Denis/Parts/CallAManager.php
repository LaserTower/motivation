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

    public function execute($denis, $message)
    {
        //прям здесь по диалогу определяется кому нужно послать сообщение
        //$manager_id=21142746;
        $manager_id = 3697315;

        $variables = [
            'id' => $denis->getUserId()
        ];

        array_walk($variables, function (&$value) {
            return "\{\{$value\}\}";
        });
        $this->message = str_replace(array_keys($variables), array_values($variables), $this->message);


        $newmessage = new Message(0, 0, $this->message);
        $newmessage->user_id = $manager_id;
        $denis->transmit($newmessage);
        return null;
    }
}