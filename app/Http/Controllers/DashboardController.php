<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function loadSections() {
        if (!session('agentName')) {
            return redirect()->route('auth.login.page');
        }
        return view('dashboard');
    }
}
