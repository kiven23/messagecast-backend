<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryBranch;

class Branches extends Controller
{
       public function BranchesIndex(){
         return InventoryBranch::all();
        }
       public function BranchesCreate(request $req){
              try{
                     $insert = new InventoryBranch();
                     $insert->branch = $req->branches;
                     $insert->whscode = $req->warehouse;
                     $insert->save();
               return ['msg'=>$req->branches.' is created', 'color'=>'positive'];
              }catch(\Exception $e){

               return ['msg'=> $e->getMessage(), 'color'=>'negative'];
              }
          
       }
       public function BranchesUpdate(request $req){
           
              try{
                   $update = InventoryBranch::find($req->branchID);
                   $update->branch = $req->branch;
                   $update->whscode = $req->warehouse;
                   $update->update();
                    return ['msg'=>$req->branch.' is updated', 'color'=>'positive'];
              }catch(\Exception $e){
                    return ['msg'=> $e->getMessage(), 'color'=>'negative']; 
              }
       }
       public function BranchesTrash(request $req){
              
             try{
              foreach($req->trash as $id){
                     InventoryBranch::find($id['id'])->delete();
                     
              }
              return ['msg'=> 'Branch is already deleted', 'color'=>'positive'];
             }catch(\Exception $e){
              return ['msg'=> $e->getMessage(), 'color'=>'negative']; 
             }
            
            
              
             
       }
}
