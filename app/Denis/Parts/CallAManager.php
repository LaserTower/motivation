<?php


namespace App\Denis\Parts;


class CallAManager extends CorePart
{
    public $type = 'call-manager';
    public $message;

    public function __construct($id=null, $message=null)
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
        return null;
    }


}