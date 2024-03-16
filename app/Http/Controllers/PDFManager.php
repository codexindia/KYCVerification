<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PDFManager extends Controller
{
    public function download_ackw(Request $request)
    {
        $user = User::where('customer_id', $request->user()->customer_id)
        ->with('getAadharData')
        ->with('getBankData')->first();
        $user = json_decode($user, true);
        $pdf = Pdf::setOption(['dpi' => 150]);
       // return view('pdf.individual',compact('user'));
        $pdf->loadView('pdf.individual', compact('user'));
        return $pdf->stream($user['customer_id'] . '.pdf');
    }
    public function download_officeCopy(Request $request)
    {

        $user = User::where('customer_id', decrypt($request->id))
        ->with('getAadharData')
        ->with('getBankData')->first();
        $user = json_decode($user, true);
        $pdf = Pdf::setOption(['dpi' => 150]);
       // return view('pdf.individual',compact('user'));
        $pdf->loadView('pdf.individual', compact('user'));
        return $pdf->stream($user['customer_id'] . '.pdf');
    }
}
