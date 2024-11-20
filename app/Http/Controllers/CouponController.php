<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function init() {
        $id = Session::get('agentID');
        if ($id) {
        
            // Getting coupon values (amount used and balance)
            $this->setCouponValues($id);

            // Getting current coupons
            $currentCoupons = DB::select('CALL federatedb.usp_getAgentCoupons (?)', [$id]);

            $stores = DB::select('SELECT ms_short_name from unify.managed_stores WHERE ms_short_name NOT LIKE "OCM"');
            return view('coupons', compact('stores', 'currentCoupons'));
        }
        // Redirecting if not logged in
        return redirect()->route('auth.login.page');
    }

    private function setCouponValues($agentID) {
        $couponValues = DB::select('CALL usp_getAvailableCouponAmounts(?)', [$agentID]);
            
        Session::put('couponBalancePretty', $this->formatMoney($couponValues[0]->AllowedAmount));
        Session::put('couponsUsedPretty', $this->formatMoney($couponValues[0]->Used));
        Session::put('couponBalanceActual', $couponValues[0]->AllowedAmount);
        Session::put('couponsUsedActual', $couponValues[0]->Used);
    }

    private function formatMoney($amount) {
        // Rounding to 2
        $amount = number_format($amount,2);
        
        // Pos is a flag for the strlen comparison depending on if the number is negative or not
        $pos = 9;
        if ($amount < 0) {
            $pos++;
        }

        $newStr = '';
        if (strlen($amount) == $pos) {
            // Above 999 and Under $10000
            $newStr .= $amount[0] . ',' . substr($amount, 1, strlen($amount));
        }
        if (strlen($amount) > $pos) {
            // Over 10000
            $newStr .= substr($amount, 0, 2) . ',' . substr($amount, 2, strlen($amount));
        }
        else {
            // Under 1000
            $newStr = $amount;
        }

        // Adding $ to amount
        if ($pos == 9) {
            $newStr = '$' . $newStr;
        } else {
            $tmp = $newStr[0];
            $newStr[0] = '$';
            $newStr = $tmp . $newStr;
        }

        return $newStr;
    }

    public function createCoupon(Request $request) {
        try {
            $agentID = $request->input('agentID');
            $store = $request->input('store');
            $couponAmount = $request->input('couponAmount');
            
            // Calling create coupon procedure
            $code = DB::select('CALL usp_CreateCoupon_v3(?,?,?)', [$agentID, $store, $couponAmount]);
 
            return response()->json([
                'success' => true,
                'data' => $code
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteCoupon(Request $request) {
        try {
            $store = $request->input('store');
            $couponCode = $request->input('couponCode');

            // Calling delete coupon procedure
            DB::select('CALL usp_deleteAgentCoupon(?,?)', [$store, $couponCode]);

            return response()->json([
                'success' => true,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
