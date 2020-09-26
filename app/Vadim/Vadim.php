<?php


namespace App\Vadim;

use App\Denis\Core;
use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;
use App\Vadim\Models\AlarmClockPool;
use App\Vadim\Models\ProgramScenario;
use App\Vadim\Models\PlayersProgram;
use App\Vadim\Parts\TimerRelativeBase;
use Illuminate\Support\Facades\DB;

class Vadim
{
    protected $bindings = [
        'relative-base' => TimerRelativeBase::class
    ];

    public function attachAlarmsToUserProvider($users_of_providers_id, $alarm_clock_prototype_id)
    {
        DB::transaction(function () use ($users_of_providers_id, $alarm_clock_prototype_id) {
            //за пользователем теперь следит бот
            //привязываем прототип программы мотивации к игроку 
            $scheduler = PlayersProgram::create([
                'users_of_providers_id' => $users_of_providers_id,
                'program_scenario_id' => $alarm_clock_prototype_id,
                'clock_external_data' => ['mode' => 'setup']
            ]);

            //установить будильник на сейчас
            //когда освободится основной диалог должен начаться настроечный бот
            $usersOfProvidersModel = UserOfProviders::find($users_of_providers_id);
            AlarmClockPool::create([
                'clock_at' => \DB::raw('transaction_timestamp()'),
                'players_program_id' => $scheduler->id,
                'timer_part_id' => 0,
                'users_of_providers_id' => $users_of_providers_id,
                'player_id' => $usersOfProvidersModel->player_id,
            ]);
        });
    }

    public function deployTimers($alarm_clock_schedule_id)
    {
        $scheduler = PlayersProgram::find($alarm_clock_schedule_id);
        $prototype = ProgramScenario::find($scheduler->alarm_clock_prototype_id);
        $timers = [];
        foreach ($prototype->payload['timers'] as $raw) {
            $timers[] = $this->gyrate($raw);
        }

        DB::transaction(function () use ($scheduler, $timers, $prototype) {
            foreach ($timers as $timer) {
                AlarmClockPool::create([
                    'users_of_providers_id' => $scheduler->users_of_providers_id,
                    'clock_at' => $timer->getTimer($scheduler),
                    'alarm_clock_schedule_id' => $scheduler->id,
                    'timer_part_id' => $timer->id,
                ]);
            }
        });
    }

    public function clockExec($alarm_clock_pool_id)
    {
        $clockTimer = AlarmClockPool::find($alarm_clock_pool_id);
        $schedulerModel = PlayersProgram::find($clockTimer->alarm_clock_schedule_id);
        $clockPrototype = ProgramScenario::find($schedulerModel->alarm_clock_prototype_id);
        $userOfProvidersModel = UserOfProviders::find($schedulerModel->users_of_providers_id);

        $attached = false;
        if ($schedulerModel->clock_external_data['mode'] == 'run') {
            //при наступлении события начинать диалог

            $prototype_id = null;
            foreach ($clockPrototype->payload['timers'] as $timer){
                if($timer['id'] == $clockTimer->timer_part_id){
                    $prototype_id = $timer['bot_id'];
                    break;
                }
            }
            
            $conversationModel = Conversation::create([
                'user_of_provider_id' => $schedulerModel->users_of_providers_id,
                'prototype_id' => $prototype_id,
                'next_part_id' => 1,
            ]);
            $conversationModel->refresh();
            $attached = (new Core())->attachConversation($userOfProvidersModel, $conversationModel);
        }

        if ($schedulerModel->clock_external_data['mode'] == 'setup') {
            //при прикреплении программы начать настроечный диалог
            $conversationModel = Conversation::create([
                'user_of_provider_id' => $schedulerModel->users_of_providers_id,
                'prototype_id' => $clockPrototype->settings_bot_id,
                'next_part_id' => 1,
                'parent_schedule_id' => $schedulerModel->id
            ]);
            $conversationModel->refresh();

            $attached = (new Core())->attachConversation($userOfProvidersModel, $conversationModel);

            if ($attached) {
                $temp = $schedulerModel->clock_external_data;
                $temp['mode'] = 'run';
                $schedulerModel->clock_external_data = $temp;
                $schedulerModel->save();
                $clockTimer->delete();
                $attached = true;
            }
        }

        if (!$attached) {
            $clockTimer->in_progress = false;
            $clockTimer->save();
        }
    }

    protected function gyrate($el)
    {
        $obj = new $this->bindings[$el['type']]();

        foreach ($el as $field => $value) {
            $obj->$field = $value;
        }
        return $obj;
    }
}