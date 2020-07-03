<?php


namespace App\Denis\Parts;


class PickData extends CorePart
{
    public $type = 'pick-data';
    public $question;
    public $next;
    public $variable;

    public function __construct($id = null, $next = null, $variable = null, $question = null)
    {
        $this->id = $id;
        $this->question = $question;
        $this->next = $next;
        $this->variable = $variable;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'question' => $this->question,
            'next' => $this->next,
            'variable' => $this->variable,
        ];
    }

    public function execute($provider, $message, $conversation)
    {
        if ($message instanceof EmptyPart) {
            $this->user_id = $conversation->user_id;
            $provider->transmit($this);
            return null;
        }
        
        if($message instanceof Message){
            $conversation->saveVariable($this->variable, $message->body);
        }
        
        return $this->next;
    }
}