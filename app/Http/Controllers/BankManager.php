<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\BankData;

class BankManager extends Controller
{
    public function bank_data(Request $request)
    {
        if($request->user()->getBankData != null)
        {
            return redirect(route('final_page'));
        }
        return view('bank');
    }
    public function bank_data_submit(Request $request)
    {
        $request->validate([
            'ifsc' => 'required',
            'account_number' => 'required|numeric'
        ]);
        try {
            $response = Http::withHeaders([
                'x-api-key' => env('SANDBOX_API_KEY'),
                'authorization' => env('SANDBOX_ACCESS_TOKEN'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get('https://api.sandbox.co.in/bank/' . $request->ifsc . '/accounts/' . $request->account_number . '/verify');
            $response = json_decode($response);

            if (isset($response->data->account_exists) && !$response->data->account_exists) {
                return back()->withErrors($response->data->message);
            }
        } catch (Exception $e) {

            return back()->withErrors('Something Went Wrong');
        }
        BankData::create([
            'user_id' => $request->user()->id,
            'ifsc_code' => $request->ifsc,
            'account_number' => $request->account_number,
            'api_transaction_id' => $response->transaction_id,
            'timestamp' => $response->timestamp,
        ]);
        return redirect(route('final_page'));
    }
    public function final_page(Request $request)
    {
return view('final');
    }
}
