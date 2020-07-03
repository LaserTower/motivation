<?php


namespace App\Denis\Parts;


class UserChoice extends CorePart
{
    public $type = 'user-choice';
    public $question;
    public $variants = [];
    public $variable;
    public $next;

    public function __construct($id=null, $next=null, $variable=null, $question=null, $variants=null)
    {
        $this->id = $id;
        $this->question = $question;
        $this->next = $next;
        $this->variable = $variable;
        $this->variants = $variants;
    }
    
    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'question' => $this->question,
            'next' => $this->next,
            'variable' => $this->variable,
            'variants' => $this->variants,
        ];
    }

    function execute($provider, $message, $conversation)
    {
        if ($message instanceof EmptyPart) {
            $this->user_id = $provider->getUserId();
            $provider->transmit($this);
            return null;
        }

        if($message instanceof Message){
            if(!empty($message->externalData)){
                $conversation->saveVariable($this->variable, $message->externalData[$this->variable]);
            }else{
                $conversation->saveVariable($this->variable, $message->body);
            }
        }

        return $this->next;
    }
}