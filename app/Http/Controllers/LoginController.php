<?php

namespace App\Http\Controllers;
use App\Models\CouponAgentList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    public function authenticateLogin(Request $request)
    {
        $agentID = $request->input('agent_id');
        $password = $request->input('password');
    
        // Validate the login details
        $loginAttempt = CouponAgentList::select('AgentID','Agent')
            ->where('status', 1)
            ->where('AgentID', $agentID)
            ->where('Pwd', $password)
            ->first();
    
        if ($loginAttempt) {
            $userID = $loginAttempt->AgentID;
            $agentName = $loginAttempt->Agent;
            $agentFName = explode("_", $agentName)[0];
            // Store user ID in the session (which will be stored in a cookie)
            Session::put('user_id', $userID);
            Session::put('agentName', $agentFName);
            // Redirect to dashboard

            return redirect()->route('dashboard.page')->with('fName', $agentFName);
        } else {
            // Handle invalid login
            return redirect()->back()->withErrors(['Invalid login credentials']);
        }
    }
    
    
}
