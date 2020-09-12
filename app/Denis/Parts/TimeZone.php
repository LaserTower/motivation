<?php

namespace App\Denis\Parts;

class TimeZone extends CorePart
{
    public $type = 'timezone';
    public $variable;
    public $next;
    public $error_next_id;
    public $once = true;

    public static $fields = [
        'variable',
        'error_next_id',
        'next',
    ];
    
    public function execute($provider, $message, $conversation)
    {
        $currentTime = new \DateTime();
        
        if ($currentTime->format('i') == 00) {
            return $this->error_next_id;
        }
        
        $test = $conversation->getVariable($this->variable);
        $good = preg_match('#(?<user_hour>\d{1,2}):(?<user_minute>\d{1,2})#', $test, $matches);
        if(! $good){
            return $this->error_next_id;
        }
        $utc = $currentTime->format('H');
        $ct = $matches['user_hour'];
        $offset = (24 - $utc + $ct) % 24;
        $conversation->saveVariable($this->variable, $offset, $this->once);
        
        return $this->next;
    }
}