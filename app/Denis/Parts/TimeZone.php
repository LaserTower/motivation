<?php


namespace App\Denis\Parts;


class TimeZone extends UserChoiceOnce
{
    use PickDataTrait;

    public $type = 'timezone';

    public function checkAnswer($provider, $messages, $conversation)
    {
        if ($messages[0] instanceof EmptyPart) {
            $this->done = false;
            return null;
        }

        $good = preg_match('#(?<user_hour>\d{1,2}):(?<user_minute>\d{1,2})#', $messages[0]->body, $matches);
        
        $currentTime = new \DateTime();

        if (!$good) {
            $message = new Message(null, null, 'Пожалуйста напишите сколько у вас времени в формате ЧЧ:ММ');
            $message->user_id = $messages[0]->user_id;
            $this->done = false;
            $provider->transmit($message);
            return null;
        }

        if ($matches['user_minute'] == 59 or $currentTime->format('i') == 00) {
            $message = new Message(null, null, 'Я боюсь ошибиться, пожалуйста ещё раз напишите мне сколько времени');
            $message->user_id = $messages[0]->user_id;
            $this->done = false;
            $provider->transmit($message);
            return null;
        }
        $utc = $currentTime->format('H');
        $ct = $matches['user_hour'];
        $offset = (24 - $utc + $ct) % 24;
        
        $message = new Message(null, null, 'определил смещение как +' . $offset);
        $message->user_id = $messages[0]->user_id;
        $this->done = false;
        $provider->transmit($message);
        
        $this->savePartVariable($conversation, 'timezone', $offset);
        return $this->next;
    }
}