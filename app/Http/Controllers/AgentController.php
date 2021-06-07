<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        return view("layouts.agent");
    }

    public function getAgents()
    {
        return User::where("role", "agent")->get();
    }
}
