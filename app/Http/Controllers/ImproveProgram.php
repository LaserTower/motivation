<?php


namespace App\Http\Controllers;


use App\Vadim\Models\ImproveProgramPrototype;
use Illuminate\Http\Request;

class ImproveProgram extends Controller
{
    public function index(Request $request)
    {
        //$columns = $request->input('columns');
        $model = new ImproveProgramPrototype();
        return $model->get();
    }
    
    public function store(Request $request){

        $name = $request->all();
        $model = new ImproveProgramPrototype();
        $model->create($name);
    }
    
}