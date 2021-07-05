<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ContactList;
use App\Models\ActivityLogs;
use App\Models\CustomerReminder;
use App\Models\PaymentTerms;
use DB;
use Carbon\Carbon;
class MessageCastController extends Controller
{
    public function index(){
        $get =   DB::table('contact_lists')->get();
        $client = new Client();
        $response = $client->get('https://mocki.io/v1/7b9376f7-2a13-4c06-b5fd-219307b6774e');
        $res =  json_decode($response->getBody());
        foreach($res as $v1){
            $arr1[] = $v1;
        }
        foreach($get as $v){
            $arr2 = ['number'=> $v->contact_number, 'name'=> $v->name];
            array_push($arr1, $arr2);
        }
       return  response()->json($arr1);
    }
    public function send(request $req){
        
        if($req->inden == 1){
            foreach($req->stringOptions as $send){
                $client = new Client;
                $full_link = 'http://mcpro1.sun-solutions.ph/mc/send.aspx?user=ADDESSA&pass=MPoq5g7y&from=ADDESSA&to='.$send['val'].'&msg='.$req->message.'';
                $response = $client->request('GET', $full_link);
                if($response->getBody()){
                    $arr[] = ['msg'=> $req->message,'name'=>$send['label'],'number'=> $send['val'], 'response'=> 'Success Send..!'];
                    $logs = new ActivityLogs;
                    $logs->msg = $req->message;
                    $logs->name = $send['label'];
                    $logs->number =  $send['val'];
                    $logs->response = 'Sent';
                    $logs->save();
                }
            }
            return  ['logs'=>$arr];
        }else{
            foreach($req->contact as $send){
               $client = new Client;
               $full_link = 'http://mcpro1.sun-solutions.ph/mc/send.aspx?user=ADDESSA&pass=MPoq5g7y&from=ADDESSA&to='.$send['val'].'&msg='.$req->message.'';
               $response = $client->request('GET', $full_link);
               if($response->getBody()){
                $arr[] = ['msg'=> $req->message,'name'=>$send['label'], 'number'=> $send['val'], 'response'=> 'Success Send..!'];
                $logs = new ActivityLogs;
                $logs->msg = $req->message;
                $logs->name = $send['label'];
                $logs->number =  $send['val'];
                $logs->response = 'Sent';
                $logs->save();
            } 
        }
        //    $logs = new ActivityLogs;
        //    $logs->logs = json_encode($arr);
        //    $logs->save();
            return  ['logs'=>$arr];
        }
        // $full_link = 'http://mcpro1.sun-solutions.ph/mc/send.aspx?user=ADDESSA&pass=MPoq5g7y&from=ADDESSA&to=09152212673&msg=addessamessagecast';
        // $client = new Client;
        // $response = $client->request('GET', $full_link);
        // return $response = $response->getBody();
    
    }
    public function fetch_contact(){
   return DB::table('contact_lists')->get();
    }
    public function createContact(request $req){
        $val = array("(",")","-"," ");
        $number = str_replace($val, "", $req->number);
       $check = ContactList::where('contact_number', $number)->get()->first();
        if($check){
            return ['msg'=> 'Already Exist '.$number.'', 'color'=> 'negative'];
        }else{
            $insert = new ContactList();
            $insert->name = $req->name;
            $insert->contact_number = $number;
            $insert->location = $req->location;
            $insert->save();
            return ['msg'=> 'Success Save', 'color'=> 'positive'];
        }
    }
    public function updateContact(request $req){
      
            $val = array("(",")","-"," ");
            $number = str_replace($val, "", $req->phone);
            $insert = ContactList::find($req->id);
            $insert->name = $req->name;
            $check = ContactList::where('contact_number', $number)->get()->first();
            if(!$check){
             $insert->contact_number = $number;
            }
            $msg = ['msg'=> 'Duplicate Phone Number', 'color'=> 'negative'];
            $insert->location = $req->location;
            $insert->update();
            return ['msg'=> 'Success Update', 'color'=> 'positive'];
    }
   
    public function logs(){
      return  DB::table('activity_logs')->get();
    }
    public function usersent(request $req){
        $client = new Client;
        $full_link = 'http://mcpro1.sun-solutions.ph/mc/send.aspx?user=ADDESSA&pass=MPoq5g7y&from=ADDESSA&to='.$req['contact']['contact_number'].'&msg='.$req['message'].'';
        $response = $client->request('GET', $full_link);
        if($response->getBody()){
         $arr[] = ['msg'=> $req['message'],'name'=> $req['contact']['name'], 'number'=> $req['contact']['contact_number'], 'response'=> 'Success Send..!'];
         $logs = new ActivityLogs;
         $logs->msg = $req['message'];
         $logs->name =  $req['contact']['name'];
         $logs->number =  $req['contact']['contact_number'];
         $logs->response = 'Sent';
         $logs->save();
     } 
    return $req['contact']['contact_number'];
    }
    public function clearlogs(){
        ActivityLogs::truncate();
    }
    public function trashUser(request $req){
        ContactList::find($req->id)->delete();
    }
    public function sendTigger(){
         
      $check = DB::table('payment_terms')
                            ->join('customer_reminders', 'payment_terms.user_id','=','customer_reminders.id')
        ->get();
        foreach($check as $res){
            //$response[] = '₱ '.round($res->total, 2);
            $duedate = $res->date;
            $dt = new \DateTime($duedate);
            $carbon = Carbon::instance($dt)->subDays(7)->toDateString();
            $today = Carbon::now()->toDateString();
            if( '2021-09-08' == $carbon){
                if($res->status == 0){
                    $dueDay = new Carbon($res->date);
                    //$dueDay->addDay(7);
                    $message = 'Hi '.$res->name.' This is to remind you that payment of an invoice for an account number ('.$res->customercode.') will be due on '.$dueDay->toDateString().'. The total amount is ('.round($res->total, 2).' - pesos). Please make your payments to the account number specified on the invoice.';
                    $full_link = 'http://mcpro1.sun-solutions.ph/mc/send.aspx?user=ADDESSA&pass=MPoq5g7y&from=ADDESSA&to='.$res->number.'&msg='.$message.'';
                    $client = new Client;
                    $response = $client->request('GET', $full_link);
                    $msg[] =  ['msg'=> $message,'date'=> $carbon, 'response'=> $response->getBody(), 'userid'=> $res->id];
                    DB::table('reminder_logs')->insert([
                        'customer'=> $res->customercode,
                        'name'=> $res->name,
                        'duedate'=> $dueDay->toDateString(),
                        'sentdate'=> $carbon,
                        'message'=>$message
                    ]);
                    //$msg[] =  ['msg'=> 'proccessing','date'=> $carbon , 'status'=>  $res->status];
                    DB::table('payment_terms')->where('user_id', $res->id)->where('date', $res->date)->update([
                        'status'=> 1
                    ]);
                }
            }
            $msg[] =  ['msg'=> 'proccessing','date'=> $carbon , 'status'=> 0, 'userid'=> $res->id];
        }
        return $msg;
        
    }
    public function urlFunction(){
        $client = new Client;
        $response = $client->request('GET', '');
    }
    
}