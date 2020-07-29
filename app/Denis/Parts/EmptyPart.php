<?php

namespace App\Denis\Parts;


class EmptyPart extends CorePart
{
    public $type = 'empty';
    function execute($provider,  $message, $conversation)
    {
     
    }
}