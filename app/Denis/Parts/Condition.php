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

    public function execute($provider, $message, $conversation)
    {
        $variables = $conversation->getVariables();
        
        if (array_key_exists($this->variable, $variables['many'])) {
            $test = end($variables['many'][$this->variable]);
        }

        if (array_key_exists($this->variable, $variables['once'])) {
            $test = $variables['once'][$this->variable];
        }

        if (is_array($test)) {
            return $this->rules[$test['vid']];
        } else {
            return $this->rules[$test];
        }
    }
}