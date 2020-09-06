<?php


namespace App\Denis\Parts;


class PickData extends CorePart
{
    public $type = 'pick-data';
    public $question;
    public $next;
    public $variable;
    public $once = false;
    public $regexp_pattern ;
    public $regexp_repeat_message ;

    public static $fields = [
        'question',
        'regexp_pattern',
        'regexp_repeat_message',
        'next',
        'variable',
        'once',
    ];
    
    public function pickData($provider, $messages, $conversation)
    {
        if(!isset($conversation->part_external_data['pickData'])){
            $this->externalData['pickData'] = 'in_progress';
            return $this->askQuestion($provider, $messages, $conversation);
        }else{
            $res = $this->checkAnswer($provider, $messages, $conversation);
            if (!$this->done){
                $this->externalData['pickData'] = 'in_progress';
            }
            return $res;
        }
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
            if (!($message instanceof Message)) {
               continue;
            }
            
            if ($message->date < $conversation->updated_at->format('U')) {
                continue;
            }
            
            $mess[] = $message->body;
        }
        if (count($mess) < 1) {
            $this->done = false;
            return null;
        }
        
        $text = implode(' ', $mess);
        
        if(!empty($this->regexp)){
            $good = preg_match($this->regexp, $text, $matches);
            if (!$good){
                $this->repeatQuestion($provider,$mess[0]->user_id, $conversation);
                $this->done = false;
                return null;
            }
        }
        
        $this->savePartVariable($conversation, $this->variable, $text);
        return $this->next;
    }

    public function execute($provider, $messages, $conversation)
    {
        return $this->pickData($provider, $messages, $conversation);
    }

    public function savePartVariable($conversation, $key, $value)
    {
        $conversation->saveVariable($key, $value, $this->once);
    }

    protected function repeatQuestion($provider,$userid, $conversation)
    {
        $newmessage = new Message;
        $newmessage->user_id = $userid;
        $newmessage->body = $this->formatVariables($this->regexp_repeat_message, $conversation->getVariables());
        $provider->transmit($newmessage);
    }
}