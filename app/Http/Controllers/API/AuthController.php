<?php

namespace App\Http\Controllers\API;

use App\Models\InventoryBranch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
 
class AuthController extends Controller
{
    public function register(Request $request)
    {
       
        try{
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'branch' => 'required',
            'roles' => 'required',
        ]);
      // $validatedData['password'] = bcrypt($request->password);
       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->password = Hash::make($request->password);
       $user->branch_id = $request->branch;
       $user->save();
       $user->assignRole($request->roles);
       $accessToken = $user->createToken('authToken')->accessToken;
            return response([ 'user' => $user, 'access_token' => $accessToken, 'msg'=>'Update Success', 'color'=>'positive']);
        }catch(\Exception $e){
            return ['msg'=> $e->getMessage(), 'color'=>'negative'];
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }
    public function login_auth(request $req){
        return $req;
    }
    public function UsersIndex(){
        return user::with('roles')->with('branches')->get();
      }
      public function UsersEdit(request $req){
    
      }
      public function UsersUpdate(request $request){
 
          if($request->branch['value']){
              $branch = $request->branch['value'];
          }else{
              $branch = $request->branch;
          }
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'branch' => 'required',
            'roles' => 'required',
        ]);
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->branch_id = $branch;
        $user->update();
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        $user->assignRole($request->roles);
        return ['msg'=>'Update Success', 'color'=>'positive'];
      }
      public function UsersTrash(request $req){
        foreach($req->id as $user){
            $delete = User::find($user['id'])->delete();
        }
        return ['msg'=> 'users is already deleted..!', 'color'=>'positive'];
      }
 
}
