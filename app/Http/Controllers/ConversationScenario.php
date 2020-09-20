<?php


namespace App\Http\Controllers;

use App\Denis\Models\ConversationsScenario;
use App\Denis\Parts\CorePart;
use Illuminate\Http\Request;

class ConversationScenario extends Controller
{
    public function index(Request $request)
    {
        return ConversationsScenario::select('id','name','created_at')->get();
    }
    
    public function store(Request $request)
    {
        $scenario = ConversationsScenario::create(
            [
                'name' => $request->get('name'),
                'published' => true,
                'payload' => [
                    'parts' =>  $request->get('parts')
                ]
            ]);
        return ['id' => $scenario->id];
    }
    
    public function show($id)
    {
        return ConversationsScenario::find($id);
    }

    public function parts(Request $request)
    {
        $out = [];
        foreach (CorePart::BINDINGS as $type => $class) {
            $out[$type] = $class::getFields();
        }
        return $out;
    }
    
    public function update($id, Request $request)
    {
        ConversationsScenario::find($id)->update([
            'name' => $request->get('name'),
            'payload' => [
                'parts' => $request->get('parts')
            ]
        ]);
    }
}