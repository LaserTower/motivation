<?php


namespace App\Denis\Parts;


use App\Models\UserOfProviders;

class CallAManager extends CorePart
{
    use ApplyVariables;
    public $type = 'call-manager';
    public $message; //
    public $manager_id;

    public function __construct($id = null, $message = null)
    {
        $this->id = $id;
        $this->message = $message;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'message' => $this->message
        ];
    }

    public function execute($provider, $message, $conversation)
    {
        if(array_key_exists('is_send', $conversation->part_external_data) && $conversation->part_external_data['is_send'] == true){
            $this->externalData['is_send'] = true;
            return null;
        }
        
        //прям здесь по диалогу определяется кому нужно послать сообщение
        //$manager_id = 33100912;
        $manager_id = 3697315;// (Александр Князев)
        $this->message = $this->formatVariables($this->message, $conversation->getVariables());

        $userOfProvidersModel = UserOfProviders::find($conversation->user_of_provider_id);
        $newmessage = new Message(0, 0, 'Решить проблему поможет наш специалист');
        $newmessage->user_id = $userOfProvidersModel->provider_user_id;
        $provider->transmit($newmessage);
        
        $newmessage = new Message(0, 0, $this->message);
        $newmessage->user_id = $manager_id;
        $provider->transmit($newmessage);
        $this->externalData['is_send'] = true;
        return null;
    }
}