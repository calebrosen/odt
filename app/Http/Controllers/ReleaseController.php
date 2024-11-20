<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    public function init() {
        $id = Session::get('agentID');
        if ($id) {

            $stores = DB::select('SELECT ms_short_name from unify.managed_stores WHERE ms_short_name NOT LIKE "OCM"');
            return view('releases', compact('stores'));
        }
        // Redirecting if not logged in
        return redirect()->route('auth.login.page');
    }
}
