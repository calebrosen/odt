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
            // getting coupon values (amount used and balance)
            $couponValues = DB::select('CALL usp_getAvailableCouponAmounts(?)', [$id]);
            
            Session::put('couponBalancePretty', $this->formatMoney($couponValues[0]->AllowedAmount));
            Session::put('couponsUsedPretty', $this->formatMoney($couponValues[0]->Used));
            Session::put('couponBalanceActual', $couponValues[0]->AllowedAmount);
            Session::put('couponsUsedActual', $couponValues[0]->Used);


            $stores = DB::select('SELECT ms_short_name from unify.managed_stores WHERE ms_short_name NOT LIKE "OCM"');
            return view('coupons')->with('stores',$stores);
        }
        return redirect()->route('login.page');
    }

    private function formatMoney($amount) {
        // Rounding to 2
        $amount = number_format(round($amount),2);
        
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

    }
}
