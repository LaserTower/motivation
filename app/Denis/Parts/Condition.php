<?php


namespace App\Denis\Parts;


class Condition extends CorePart
{
    public $type = 'condition';
    public $variable;
    public $values;
    public $nextIds;
    public $nextIfAnswerIsNull;
    public static $fields = [
        'variable',
        'values',
        'nextIds',
        'nextIfAnswerIsNull',
    ];
    
    public function execute($provider, $message, $conversation)
    {
        $test = $conversation->getVariable($this->variable);
        if (is_null($test)) {
            return $this->nextIfAnswerIsNull;
        } elseif (is_array($test)) {
            //поиск по значению
            if (in_array($test['v'], $this->values)) {
                return $this->nextIds[array_search($test['v'], $this->values)];
            }
            //поиск по шв
            if (in_array($test['vid'], $this->values)) {
                return $this->nextIds[array_search($test['vid'], $this->values)];
            }
        } else {
            return $this->nextIds[array_search($test, $this->values)];
        }
        return $this->nextIfAnswerIsNull;
    }
}