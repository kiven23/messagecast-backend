<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ContactList;
use App\Models\ActivityLogs;
use DB;
class MessageCastController extends Controller
{
    public function index(){
        $get =   DB::table('contact_lists')->get();
        $client = new Client();
        $response = $client->get('https://mocki.io/v1/c8b6109e-47dc-4783-94bc-b1cf460eb5b8');
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
            // $logs = new ActivityLogs;
            // $logs->logs = json_encode($arr);
            // $logs->save();
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
            $insert->contact_number = $number;
            $insert->location = $req->location;
            $insert->update();
            return ['msg'=> 'Success Update', 'color'=> 'positive'];
    }
   
    public function logs(){
      return  DB::table('activity_logs')->get();
    }
}