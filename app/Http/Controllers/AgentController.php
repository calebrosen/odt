<?php
namespace App\Http\Controllers;

use App\Models\CouponAgentList;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function showLoginPage()
    {
        // if (session('agentID') && session('agentName')) {
        //     return redirect()->route('dashboard.page');
        // }

        // Fetch agents from the database
        $agents = CouponAgentList::select('AgentID', 'Agent')->where('status',1)->get();
        
        // Return the login view and pass the agents
        return view('login', compact('agents'));
    }
}
