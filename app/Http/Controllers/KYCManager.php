<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerficationCodes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Throwable;
use App\Models\AadharData;

class KYCManager extends Controller
{




    public function basic_submit(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:10',
            'registration_type' => 'required'
        ]);
        $temp = [
            'email' => $request->email,
            'registration_type' => $request->registration_type,
        ];
        $this->genarateotp($request->phone_number, $temp);
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
        $data = $this->VerifyOTP($request->phone_number, $otp);
        if ($data) {
            $temp = json_decode($data->temp);
            $checkphone = User::where('mobile_number', $request->phone_number)->first();
            if ($checkphone) {
                Auth::loginUsingId($checkphone->id);

                if ($checkphone->user_type == 'company') {
                    return redirect(route('company_details'));
                }
                if ($checkphone->getAadharData == null)
                    return redirect(route('aadhar_details'));
                return redirect(route('bank_data_page'));
            } else {
                $newuser = User::create([
                    'mobile_number' => $request->phone_number,
                    'user_type' => $temp->registration_type,
                    'email' => $temp->email,
                    'customer_id' => 'NTS' . time() . 'VC'
                ]);

                Auth::loginUsingId($newuser->id);
                if ($newuser->user_type == 'company') {
                    return redirect(route('company_details'));
                }
                return redirect(route('aadhar_details'));
            }
        } else {
            return back()->withErrors('Invalid OTP Entered');
        }
    }
    public function aadhar_details(Request $request)
    {
        return view('aadhar');
    }
    public function aadhar_otp(Request $request)
    {
        $aadhar = implode($request->otp);
        if (strlen($aadhar) != 12) {
            return back()->withErrors(['Aadhar Must Be 12 Digits']);
        }
        try {
            $response = Http::withHeaders([
                'x-api-key' => env('SANDBOX_API_KEY'),
                'authorization' => env('SANDBOX_ACCESS_TOKEN'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.sandbox.co.in/kyc/aadhaar/okyc/otp', [
                'aadhaar_number' => $aadhar
            ]);
            $response = json_decode($response);
            if ($response->data->message == "Invalid Aadhaar Card") {
                return back()->withErrors($response->data->message);
            }
        } catch (Exception $e) {
            return back()->withErrors("Something went Wrong");
        }
        if ($response->code == 200) {
            $pagerec = [
                'ref_id' => $response->data->ref_id,
                'aadhaar_number' => $aadhar,
            ];
            return redirect(route('aadhar_validate_otp', encrypt($pagerec)));
        }
    }
    public function aadhar_validate_otp(Request $request)
    {
        $pagerec = decrypt($request->pagerec);

        return view('aadhar_otp', compact('pagerec'));
    }
    public function aadhar_otp_submit(Request $request)
    {
        $request->validate([
            'aadhar_number' => 'required'
        ]);
        $otp = implode($request->otp);
        if (strlen($otp) != 6) {
            return back()->withErrors(['OTP Must Be 6 Digits']);
        }
        try {
            $response = Http::withHeaders([
                'x-api-key' => env('SANDBOX_API_KEY'),
                'authorization' => env('SANDBOX_ACCESS_TOKEN'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.sandbox.co.in/kyc/aadhaar/okyc/otp/verify', [
                'otp' => $otp,
                'ref_id' => $request->ref
            ]);
            $response = json_decode($response);
            if ($response->code != 200) {
                return back()->withErrors($response);
            }
            AadharData::create([
                'user_id' => $request->user()->id,
                'aadhar_number' => $request->aadhar_number,
                'ref_id' => $request->ref,
                'transaction_id' => $response->transaction_id,
                'timestamp' => $response->timestamp,
                'core' => json_encode($response),
            ]);
            User::find($request->user()->id)->update([
                'name' => $response->data->name,
              
            ]);
            return redirect(route('bank_data_page'));
        } catch (Exception $e) {
            return back()->withErrors("Invalid OTP Entered");
        }
    }
    private function genarateotp($number, $temp = [])
    {
        $otpmodel = VerficationCodes::where('phone', $number);

        if ($otpmodel->count() > 10) {
            return false;
        }
        $checkotp = $otpmodel->latest()->first();
        $now = Carbon::now();

        if ($checkotp && $now->isBefore($checkotp->expire_at)) {

            $otp = $checkotp->otp;
            $checkotp->update([
                'temp' => json_encode($temp),
            ]);
        } else {
            $otp = rand('100000', '999999');
            //$otp = 123456;
            VerficationCodes::create([
                'temp' => json_encode($temp),
                'phone' => $number,
                'otp' => $otp,
                'expire_at' => Carbon::now()->addMinute(10)
            ]);
        };

        try {
            $response = Http::withHeaders([
                'authorization' => 'xHJicy25FB7MKaRVf6LwkYSIXoluUbOP43zTWCvp8019tgjeAdo90pJ5x6q32dE1ZrCP4aONUmsjtBlD',
                'accept' => '*/*',
                'cache-control' => 'no-cache',
                'content-type' => 'application/json'
            ])->post('https://www.fast2sms.com/dev/bulkV2', [
                "variables_values" => $otp,
                "route" => "otp",
                "numbers" => $number,
            ]);


            return true;
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }
    private function VerifyOTP($phone, $otp)
    {
        //this for test otp
        if ($otp == "913432") {
            $checkotp = VerficationCodes::where('phone', $phone)
                ->latest()->first();
            VerficationCodes::where('phone', $phone)->delete();
            return $checkotp;
        }
        //end for test otp
        $checkotp = VerficationCodes::where('phone', $phone)
            ->where('otp', $otp)->latest()->first();
        $now = Carbon::now();
        if (!$checkotp) {
            return 0;
        } elseif ($checkotp && $now->isAfter($checkotp->expire_at)) {

            return 0;
        } else {
            $device = 'Auth_Token';
            VerficationCodes::where('phone', $phone)->delete();
            return $checkotp;
        }
    }
}
