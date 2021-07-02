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
        $req = $client->get('http://10.10.10.38:8006/api/messagecast/sync/arinvoice');
        $response = json_decode($req->getBody());
        foreach($response as $res){
            $checkuser = DB::table('customer_reminders')->where('customercode', $res->customer_code)->get();
            if(!count($checkuser) > 0){
               
                $val = array("(",")","-"," ");
                $number = str_replace($val, "", $res->number);
                if(strlen($number) == 11){
                    $postDate = new Carbon($res->posting_date);
                    $insert = new CustomerReminder;
                    $insert->customercode = $res->customer_code;
                    $insert->number = $number;
                    $insert->name = $res->name;
                    $insert->installment_terms = $res->installment_terms;
                    $insert->posting_date = $postDate->format('Y-m-d');
                    $insert->total_payment_due = '₱ '.round($res->total_payment_due, 2);
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
        }
        return response()->json($out);
    }
    public function GetContacts(){
       return DB::table('customer_reminders')->get();
    }
    public function GetReminders(request $req){
        return DB::table('payment_terms')->where('user_id', $req->id)->get();
    }
}
