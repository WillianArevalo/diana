<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RRHHController extends Controller
{
    public function index()
    {
        $users = User::where("role", "collaborator")->get();
        return view("rrhh.dashboard", compact("users"));
    }
}
