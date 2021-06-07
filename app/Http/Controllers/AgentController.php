<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
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

    public function getMessages()
    {
        $messages = FollowUp::where("agent_id", auth()->user()->id)->get();

        foreach ($messages as $message) {
            $message->customer_name = $message->customer_name;
        }

        return $messages;
    }
}
