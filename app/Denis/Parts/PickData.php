<?php


namespace App\Denis\Parts;


class PickData extends CorePart
{
    use PickDataTrait;
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

    public function askQuestion($provider, $messages, $conversation)
    {
        $this->user_id = $conversation->user_id;
        $provider->transmit($this);
        return null;
    }

    public function checkAnswer($provider, $messages, $conversation)
    {
        $mess = [];
        foreach ($messages as $message) {
            if ($message instanceof Message) {
                $mess[] = $message->body ;
            }
        }
        if(count($mess)<1){
           return null;
        }
        $conversation->saveVariable($this->variable, implode(' ',$mess));
        return $this->next;
    }

    function execute($provider, $messages, $conversation)
    {
        return $this->pickData($provider, $messages, $conversation);
    }
}