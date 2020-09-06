<?php


namespace App\Http\Controllers;


use App\Vadim\Models\ImproveProgramPrototype;
use App\Vadim\Vadim;
use Illuminate\Http\Request;

class ImproveProgram extends Controller
{
    public function index(Request $request)
    {
        return ImproveProgramPrototype::select('id','name','settings_bot_id','created_at')->get();
    }
    
    public function store(Request $request)
    {
        $name = $request->all();
        $model = new ImproveProgramPrototype();
        $model->create($name);
    }

    public function attach(Request $request)
    {
        $users_of_providers_id = $request->input('user');
        $alarm_clock_prototype_id = $request->input('program');
        (new Vadim())->attachAlarmsToUserProvider($users_of_providers_id, $alarm_clock_prototype_id);
    }
}