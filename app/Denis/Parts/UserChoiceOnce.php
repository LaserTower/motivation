<?php


namespace App\Denis\Parts;


class UserChoiceOnce extends CorePart
{
    use PickDataTrait;

    public $type = 'user-choice-once';
    public $question;
    public $variants = [];
    public $variable;
    public $next;

    public function __construct($id = null, $next = null, $variable = null, $question = null, $variants = null)
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

    public function askQuestion($provider, $messages, $conversation)
    {
        $this->user_id = $conversation->userId();
        $this->done = false;
        $provider->transmit($this);
        return null;
    }

    public function checkAnswer($provider, $messages, $conversation)
    {
        if ($messages[0] instanceof EmptyPart) {
            $this->done = false;
            return null;
        }

        if (!empty($messages[0]->externalData)) {
            $this->savePartVariable($conversation, $this->variable, ['vid'=>$messages[0]->externalData[$this->variable],'v'=>$this->variants[$messages[0]->externalData[$this->variable]]]);
        } else {
            $this->savePartVariable($conversation, $this->variable, $messages[0]->body);
        }
        return $this->next;
    }

    function execute($provider, $messages, $conversation)
    {
        return $this->pickData($provider, $messages, $conversation);
    }

    public function savePartVariable($conversation, $key, $value)
    {
        $conversation->saveVariable($key, $value, false);
    }
}