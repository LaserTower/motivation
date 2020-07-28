<?php


namespace App\Denis\Parts;


class PickDataOnce extends CorePart
{
    use PickDataTrait;

    public $type = 'pick-data-once';
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
        $this->done = false;
        $this->user_id = $conversation->userId();
        $provider->transmit($this);
        return null;
    }

    public function checkAnswer($provider, $messages, $conversation)
    {
        $mess = [];
        foreach ($messages as $message) {
            if ($message instanceof Message) {
                $mess[] = $message->body;
            }
        }
        if (count($mess) < 1) {
            $this->done = false;
            return null;
        }
        $this->savePartVariable($conversation, $this->variable, implode(' ', $mess));
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