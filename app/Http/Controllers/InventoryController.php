<?php

namespace App\Http\Controllers;
use App\Imports\InventoryImport;
use Illuminate\Http\Request;
use App\Models\InventoryBranch;
use App\Models\InventoryBrand;
use App\Models\InventoryPartname;
use App\Models\Inventory;
use Excel;
use Auth;
use App\Models\User;
 
class InventoryController extends Controller
{
   public function import(request $req){
     
    $check = Inventory::where('branch_id', $req->branch)->where('as_of', $req->date)->get()->first();
    if($check){
      return ['msg'=>'Already Inserted'];
    }else{
       
      $Invty = new Inventory();
      $Invty->branch_id = $req->branch;
      $Invty->user_id = Auth::user()->id;
      $Invty->as_of = $req->date;
      $Invty->save();
      $InvtyUpdate = Inventory::find($Invty->id);
      $InvtyUpdate->control_id = $Invty->id;
      $InvtyUpdate->update();
      $control_id = ['control_id'=>$Invty->id];
       
      $path1 = $req->file('file_path');
      $data = \Excel::import(new InventoryImport($req,$control_id),$path1);
    }
    
   }
   public function branches(){
      return InventoryBranch::all();
   }
   public function inventories_list(){
      return $inventory = Inventory::with('user')->with('branch')->with('invty_list')->get();
   }
   public function inventories_getItems(request $req){
      return $inventoryGetItems = InventoryPartname::where('branch_id', $req->branch)->where('as_of', $req->asof)->get();
   }
   public function inventories_Item_Update(request $req){
        if($req->phy){
         $en = ((int)$req->sap - (int)$req->phy);
         $total = ((float)$req->unitP * (int)$en);
         $update = InventoryPartname::find($req->id);
         $update->me = $en;
         $update->phy = $req->phy;
         $update->total = $total;
         $update->update();
        }else{
         $update = InventoryPartname::find($req->id);
         $update->me = null;
         $update->phy = null;
         $update->total = null;
         $update->update();
        }
      return ['msg'=>'Updated'];
    
   }
 
}
