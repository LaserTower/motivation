<?php


namespace App\Http\Controllers;


use App\Vadim\Models\ImproveProgramPrototype;
use Illuminate\Http\Request;

class ImproveProgram extends Controller
{
    protected function index(Request $request)
    {
        //$columns = $request->input('columns');
        $model = new ImproveProgramPrototype();
        return $model->get();
    }
}