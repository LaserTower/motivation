<?php


namespace App\Http\Controllers;


use App\Vadim\Models\ProgramScenario;
use App\Vadim\Parts\TimerRelativeBase;
use App\Vadim\Vadim;
use Illuminate\Http\Request;

class ImproveProgram extends Controller
{
    public function index(Request $request)
    {
        return ProgramScenario::select('id', 'name', 'settings_bot_id', 'created_at')->get();
    }

    public function store(Request $request)
    {
        ProgramScenario::create(
            [
                'name' => $request->get('name'),
                'settings_bot_id' => $request->get('settingsScenarioId'),
                'payload' => [
                    'timers' => $request->get('timers')
                ]
            ]);
    }

    public function update($id, Request $request)
    {
        ProgramScenario::find($id)
            ->update([
                'name' => $request->get('name'),
                'settings_bot_id' => $request->get('settingsScenarioId'),
                'payload' => [
                    'timers' => $request->get('timers')
                ]
            ]);
    }

    public function attach(Request $request)
    {
        $users_of_providers_id = $request->input('playerId');
        $alarm_clock_prototype_id = $request->input('programId');
        (new Vadim())->attachAlarmsToUserProvider($users_of_providers_id, $alarm_clock_prototype_id);
    }
    
    public function show($id)
    {
        return ProgramScenario::find($id);
    }
}