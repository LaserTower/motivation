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
        return  UserOfProviders::select('id','provider','provider_user_id','"аноним"as name')->get();
    }

    public function motivationSchedule($id)
    {
        return AlarmClockSchedule::select(
            'alarm_clock_schedule.id',
            'alarm_clock_schedule.alarm_clock_prototype_id as motivation_scenario_id',
            'improve_program_prototypes.name'
        )
            ->leftJoin('improve_program_prototypes', 'alarm_clock_schedule.alarm_clock_prototype_id', '=', 'improve_program_prototypes.id')
            ->where('users_of_providers_id', $id)
            ->get();
    }
    
}