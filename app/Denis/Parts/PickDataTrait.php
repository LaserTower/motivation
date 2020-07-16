<?php


namespace App\Denis\Parts;


trait PickDataTrait
{
    public function pickData($provider, $messages, $conversation)
    {
        if($conversation->next_part_id!=$this->id && $conversation->done == true){
            $conversation->done = false;
            return $this->askQuestion($provider, $messages, $conversation);
        }else{
            return $this->checkAnswer($provider, $messages, $conversation);
        }
    }
}