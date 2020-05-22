<?php


namespace App\Denis\Parts;


use App\Denis\Core;

class Condition extends CorePart
{
    public $type = 'condition';
    public $variable;
    public $rules;

    public function __construct($id=null, $variable=null, $rules=null)
    {
        $this->id = $id;
        $this->variable = $variable;
        $this->rules = $rules;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'variable' => $this->variable,
            'rules' => $this->rules
        ];
    }

    public function execute(Core $denis, $message)
    {
        $variables = $denis->getVariables();
            
        if(!array_key_exists($this->variable,$variables)){
           return  null;
        }

        if(array_key_exists($variables[$this->variable],$this->rules)){
            return $this->rules[$variables[$this->variable]];
        }
        
        return null;
    }
}