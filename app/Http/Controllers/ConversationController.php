<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ConversationController extends Controller
{
    protected function index(Request $request)
    {
        $columns = $request->input('columns');
    }
}