<?php


namespace App\Http\Controllers;


use App\Denis\Models\Prototype;
use App\Models\UserOfProviders;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $model = new UserOfProviders();
        return $model->get();
    }
}