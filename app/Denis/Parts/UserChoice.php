<?php


namespace App\Denis\Parts;


class UserChoice extends PickData
{
    public $type = 'user-choice';
    public $question;
    public $variants = [];
    public $variable;
    public $next;
    public $once = false;

    public static $fields = [
        'question',
        'variable',
        'next',
        'variants',
        'once',
    ];
    
    public function checkAnswer($provider, $messages, $conversation)
    {
        if ($messages[0] instanceof EmptyPart) {
            $this->done = false;
            return null;
        }

        if (!empty($messages[0]->externalData)) {
            $vid = $messages[0]->externalData[$this->variable];
            $v = $this->variants[$messages[0]->externalData[$this->variable]];
            
            $this->savePartVariable($conversation, $this->variable, compact('v','vid'));
        } else {
            $this->savePartVariable($conversation, $this->variable, $messages[0]->body);
        }
        return $this->next;
    }
}