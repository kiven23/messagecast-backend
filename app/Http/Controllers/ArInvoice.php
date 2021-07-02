<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ArInvoice extends Controller
{
    public function index(){
        $Oinv = \DB::connection('sqlsrv')->table('oinv')
                        ->join('ocrd', 'oinv.CardCode', '=','ocrd.CardCode')
                        ->select(DB::raw('oinv.CardCode as customer_code')
                        ,'ocrd.Cellular as number'
                        ,'oinv.CardName as name'
                        ,'oinv.DocDate as posting_date'
                        ,'oinv.DocTotal as total_payment_due'
                        ,'oinv.installmnt as installment_terms')
                        ->where('oinv.installmnt', '!=' , 1)
                        ->whereNotNull('ocrd.Cellular')
                        ->where('oinv.DocStatus', 'O')
                        ->get();
        return response()->json($Oinv);
    }
}
