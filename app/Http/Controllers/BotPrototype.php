<?php


namespace App\Http\Controllers;


use App\Denis\Models\Prototype;
use Illuminate\Http\Request;

class BotPrototype extends Controller
{
    public function index(Request $request)
    {
        //$columns = $request->input('columns');
        $model = new Prototype();
        return $model->get();
    }
    
    public function store(Request $request){

        $name = $request->all();
        $model = new Prototype();
        $model->create($name);
    }
}