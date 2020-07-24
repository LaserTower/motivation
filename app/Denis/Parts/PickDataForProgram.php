<?php


namespace App\Denis\Parts;


class PickDataForProgram extends PickDataOnce
{
  
    public $type = 'pick-data-program';
    
    public function savePartVariable($conversation, $key, $value)
    {
        $conversation->saveVariable($key, $value, true);
    }
}