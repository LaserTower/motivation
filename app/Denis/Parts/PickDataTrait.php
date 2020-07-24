<?php


namespace App\Denis\Parts;


trait PickDataTrait
{
    public function pickData($provider, $messages, $conversation)
    {
        if(!isset($conversation->part_external_data['pickData'])){
            $this->externalData['pickData'] = 'in_progress';
            return $this->askQuestion($provider, $messages, $conversation);
        }else{
            return $this->checkAnswer($provider, $messages, $conversation);
        }
    }
    
    
}