<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KYCManager extends Controller
{
    public function basic_submit(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:10',
            'registration_type' => 'required'
        ]);
        return redirect(route('otp_page', encrypt($request->phone_number)));
    }
    public function otp_page(Request $request)
    {
        $phone_number = decrypt($request->phone_number);
        return view('otp', compact('phone_number'));
    }
    public function otp_validate(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:10',


        ]);
        $otp = implode($request->otp);
        if (strlen($otp) != 6) {
            return back()->withErrors(['OTP Must Be 6 Digits']);
        }
        return redirect(route('user_detailes'));
    }
    public function user_detailes(Request $request)
    {
        return view('user_detail');
    }
}
