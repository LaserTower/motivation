<?php


namespace App\Http\Controllers;

use App\Denis\Models\Prototype;
use App\Denis\Parts\CorePart;
use Illuminate\Http\Request;

class BotPrototype extends Controller
{
    public function index(Request $request)
    {
        return Prototype::select('id','name','created_at')->get();
    }
    
    public function store(Request $request)
    {
        $parts = $request->all('parts');
        Prototype::create(
            [
                'name' => $request->get('name'),
                'published' => true,
                'payload' => [
                    'parts' => $parts
                ]
            ]);
    }
    
    public function show($id)
    {
        return Prototype::find($id);
    }
    
    public function parts(Request $request)
    {
        $out = [];
        foreach (CorePart::BINDINGS as $type=>$class){
            $out[$type]=$class::getFields();
        }
        return $out;
    }
}