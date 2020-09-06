<?php


namespace App\Denis\Parts;


class Condition extends CorePart
{
    public $type = 'condition';
    public $variable;
    public $rules;
    public $nextIfAnswerIsNull;
    public static $fields = [
        'variable',
        'rules',
        'nextIfAnswerIsNull',
    ];
    
    public function execute($provider, $message, $conversation)
    {
        $test = $conversation->getVariable($this->variable);
        if(is_null($test)){
            return $this->nextIfAnswerIsNull;
        }elseif (is_array($test)) {
            return $this->rules[$test['vid']];
        } else {
            return $this->rules[$test];
        }
    }
}