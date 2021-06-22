<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;
use Auth;
use DB;

class RolesAndPermissionController extends Controller
{
   public function permissionsIndex(){
    return Permission::all();
    }
   public function permissionsCreate(request $req){
      try{
    $createPermission = new Permission;
       $createPermission->name = $req->name;
       $createPermission->save();
       return ['msg'=>'Created', 'color'=>'positive'];
      }catch(\Exception $e){
       return ['msg'=> $e->getMessage(), 'color'=>'negative'];
      }
   }
   public function permissionsEdit(request $req){
    return Permission::where('id', $req->id)->get()->first();
   }
   public function permissionsUpdate(request $req){
     try{
        Permission::find($req->id)->update([
        'name'=> $req->name
      ]);
      return ['msg'=>'Update Success', 'color'=>'positive'];
    }catch(\Exception $e){
      return ['msg'=> $e->getMessage(), 'color'=>'negative'];
    }
   }
   public function permissionsTrash(request $req){
     try{
       $i = [];
       foreach($req->trash as $trash){
         Permission::find($trash['id'])->delete();
       }
       return ['msg'=>'Deleted Success', 'color'=>'positive'];
      }catch(\Exception $e){
       return ['msg'=> $e->getMessage(), 'color'=>'negative'];
       
      }
   }
   public function rolesIndex(){
    return  $fetch = Role::with('permissions')->get();
    }
   public function rolesCreate(request $req){
     try{
      $role = new Role();
      $role->name = $req->name;
      $role->save();
      $role->syncPermissions($req->permissions);
      return ['msg'=>'Save Success', 'color'=>'positive'];
     }catch(\Exception $e){
      return ['msg'=> $e->getMessage(), 'color'=>'negative'];
     }
   }
   public function rolesEdit(request $req){
    
   }
   public function rolesUpdate(request $req){
     
    try{
      $role = Role::find($req->id);
      $role->name = $req->name;
      $role->update();
      $role->syncPermissions($req->permissions);
      return ['msg'=>'Update Success', 'color'=>'positive'];
  }catch(\Exception $e){
      return ['msg'=> $e->getMessage(), 'color'=>'negative'];
  }
   }
   public function rolesTrash(request $req){
     try{
        foreach($req->trash as $trashRole){
          if($trashRole['id'] !== 1){
            $delete = Role::find($trashRole['id'])->delete();
          }
        }
        return ['msg'=>'Delete Success', 'color'=>'positive'];
      }catch(\Exception $e){
       return ['msg'=> $e->getMessage(), 'color'=>'negative'];
      }
        
   }
}
