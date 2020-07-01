<?php


namespace App\Denis\Parts;

class Message extends CorePart
{
    public $type = 'message-text';
    public $next;
    public $body;

    public function __construct($id = null, $next = null, $body = null)
    {
        $this->id = $id;
        $this->body = $body;
        $this->next = $next;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'body' => $this->body,
            'next' => $this->next
        ];
    }

    public function execute($denis, $message)
    {
        $variables = $denis->getVariables();
        
        array_walk($variables,function (&$value){
            return "\{\{$value\}\}";
        });
        
        $this->body  = str_replace(array_keys($variables), array_values($variables),  $this->body);
        $this->user_id =$denis->getUserId();
        $denis->transmit($this);
        return $this->next;
    }
}