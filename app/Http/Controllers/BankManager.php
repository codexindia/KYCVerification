<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\BankData;

class BankManager extends Controller
{
    public function bank_data(Request $request)
    {
        if ($request->user()->getBankData != null) {
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
        $receiverNumber =  "919230128456";
        $message = $request->user()->name . ' KYC Document Link ' . route('download_officeCopy', encrypt($request->user()->customer_id));

        try {
            Http::post('https://wpsender.nexgino.com/api/create-message', [
                'appkey' => '175e1921-7d4a-4d1c-93a3-14411d027550',
                'authkey' => 'ZWkn8L2VlIOBLX5pl7omqUdkjR7RDfz6WW8ZSUzjXpy5y974DQ',
                'to' => $receiverNumber,
                'message' => $message,
            ]);
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
        BankData::create([
            'user_id' => $request->user()->id,
            'account_holder_name' => $response->data->name_at_bank,
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
