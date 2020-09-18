<?php


namespace App\Http\Controllers;


use App\Denis\Models\Prototype;
use App\Models\UserOfProviders;
use App\Vadim\Models\AlarmClockSchedule;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $players = UserOfProviders::select('id','provider','provider_user_id AS provider_player_id')->get()->toArray();

        foreach ($players as $k => $v){
            $players[$k]['programs']=AlarmClockSchedule::join('program_scenario', 'program_scenario.id', '=', 'players_program.alarm_clock_prototype_id')
            ->where('players_program.users_of_providers_id',$v['id'])->select('program_scenario.id','program_scenario.name')->toSql();
        }
        return $players;
    }
    
    public function show($id,Request $request)
    {
        return  UserOfProviders::select('id','provider','provider_user_id AS provider_player_id','variables')->find($id);
    }

    public function motivationSchedule($id)
    {
        return AlarmClockSchedule::select(
            'players_program.id',
            'players_program.alarm_clock_prototype_id as motivation_scenario_id',
            'program_scenario.name'
        )
            ->leftJoin('program_scenario', 'players_program.alarm_clock_prototype_id', '=', 'program_scenario.id')
            ->where('users_of_providers_id', $id)
            ->get();
    }
    
}