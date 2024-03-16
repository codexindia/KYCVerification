<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CompanyData;

class CompanyManager extends Controller
{
    public function company_details(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($user->getCompanyData != null)
            return redirect(route('aadhar_details'));
        return view('company');
    }
    public function company_details_submit(Request $request)
    {
        $request->validate([
            'gstin' => 'required|min:15|max:15'
        ]);
        try {
            $api = Http::withHeaders([
                'x-api-key' => env('SANDBOX_API_KEY'),
                'authorization' => env('SANDBOX_ACCESS_TOKEN'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get('https://api.sandbox.co.in/gsp/public/gstin/' . $request->gstin);
            $response = json_decode($api);
            if (isset($response->data->error_code)) {
                return back()->withErrors($response->data->message);
            }
            if (!$api->ok()) {
                return back()->withErrors($response->message);
            }
            CompanyData::create([
                'user_id' => $request->user()->id,
                'company_name' => $response->data->tradeNam,
                'gst_number' => $request->gstin,
                'gst_api_data' => json_encode($response),
            ]);
            return redirect(route('aadhar_details'));
        } catch (Exception $e) {
            return back()->withErrors('Something Went Wrong');
        }
    }
}
