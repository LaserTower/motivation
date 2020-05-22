<?php


namespace App\Denis;




class Constructor 
{
    protected $parts;
    public function __construct($parts)
    {
        $this->parts=$parts;
    }
    
    public function makePrototype()
    {
        $out = [];
        foreach ($this->parts as $part){
            $out[]=$part->constructor();
        }
        return $out;
    }

}