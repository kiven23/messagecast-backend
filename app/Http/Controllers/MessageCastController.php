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
        return "test";
    }
}