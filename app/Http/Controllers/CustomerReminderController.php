<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;
use Carbon\Carbon;
use App\Models\CustomerReminder;
use App\Models\PaymentTerms;
class CustomerReminderController extends Controller
{
    public function sync(){
        $out = [];
        $client = new Client();
        $req = $client->get('https://mocki.io/v1/a7b6f834-5ee4-44c1-b93e-5921a2a371b3');
        $response = json_decode($req->getBody());
        foreach($response as $res){
            $checkuser = DB::table('customer_reminders')->where('customercode', $res->customer_code)->get();
            if(!count($checkuser) > 0){
                $insert = new CustomerReminder;
                $insert->customercode = $res->customer_code;
                $insert->number = $res->number;
                $insert->name = $res->name;
                $insert->installment_terms = $res->installment_terms;
                $insert->posting_date = $res->posting_date;
                $insert->total_payment_due = $res->total_payment_due;
                $insert->save();
                $check = DB::table('payment_terms')->where('customercode', $res->customer_code)->get();
                    if(!count($check) > 0){
                        for ($x = 1; $x <= $res->installment_terms; $x++){
                            $c = new Carbon($res->posting_date);
                            $c->toDateString();
                            $c->addMonth($x);
                            $out[] = ['duedate'=> $c->toDateString()];
                            $due = new PaymentTerms;
                            $due->user_id = $insert->id;
                            $due->date = $c->toDateString();
                            $due->status = 0;
                            $due->customercode = $res->customer_code;
                            $due->total = (float)$res->total_payment_due / (float)$res->installment_terms;
                            $due->save();
                        }
                    }
            }
        }
        return response()->json($out);
    }
}
