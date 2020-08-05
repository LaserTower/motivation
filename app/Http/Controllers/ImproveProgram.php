<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ImproveProgram extends Controller
{
    protected function index(Request $request)
    {
        
        $columns = $request->input('columns');
    }
}