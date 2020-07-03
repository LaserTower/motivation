<?php

namespace App\Denis\Commands;


use App\Denis\Constructor;
use App\Denis\Models\Prototype as PrototypeModel;
use App\Denis\Parts\CallAManager;
use App\Denis\Parts\Condition;
use App\Denis\Parts\Message;
use App\Denis\Parts\PickData;
use App\Denis\Parts\UserChoice;
use Illuminate\Console\Command;


class CreatePrototype extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CreatePrototype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function handle()
    {
/*        $payload = [
            new Message(1,2,'Здравствуйте! Вы обратились в группу психологической помощи и поддержки.'),
            new PickData(2,3,'name','Как к Вам можно обращаться?'),
            new Message(3,4, 'Приятно познакомиться , #name#'),
            new UserChoice(4,5,'problem','Уточните, какая у Вас проблема :',[
                1=>'Отсутствие мотивации',
                2=>'Депрессия',
                3=>'Тревожность',
                4=>'Проблемы со сном',
                5=>'Несамостоятельность',
                6=>'Самооценка',
                7=>'Проблемы в личной жизни',
                8=>'Безответственность',
                9=>'Инфантильность',
                10=>'Ничего из перечисленного.'
            ]),
            new Condition(5,'problem',[
               10=>7,
               1=>6,
               2=>6,
               3=>6,
               4=>6,
               5=>6,
               7=>6,
               8=>6,
               9=>6 
            ]) ,
            new PickData(6,8,'problem_advance','Расскажите подробнее о своей проблеме.'),
            new PickData(7,8,'problem_advance','Что Вас беспокоит? Расскажите подробнее об этом.'),
            new CallAManager(8,'пора присоединиться к диалогу с пользователем #id#')
        ];*/

        $payload = [
            new PickData(1,2,'sleep_hour','Сколько часов в день вы спите?'),
            new UserChoice(2,3,'insomnia_fear','спать мешают страхи?',[
                1=>'Да',
                2=>'Нет',
            ]),
            new PickData(3,4,'sleep_time','во сколько часов засыпаете?'),
            new CallAManager(4,'пора присоединиться к диалогу с пользователем #id# на вопрос "Сколько часов в день вы спите?" он ответил #sleep_hour#,на вопрос "спать мешают страхи?"  ответил #insomnia_fear#  на вопрос "во сколько часов засыпаете?" - #sleep_time# ')
        ];


       PrototypeModel::create(
            [
                'name' => 'Prototype',
                'published' => true,
                'payload' => [
                    'parts' => (new Constructor($payload))->makePrototype() 
                ]
            ]);
    }
}
