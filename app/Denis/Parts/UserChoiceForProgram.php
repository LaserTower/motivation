<?php


namespace App\Denis\Parts;


class UserChoiceForProgram extends UserChoiceOnce
{
    public $type = 'user-choice-program';

    public function savePartVariable($conversation, $key, $value)
    {
        $conversation->saveVariable($key, $value, true);
    }
}