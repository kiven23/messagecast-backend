<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
        Route::get('/login', 'App\Http\Controllers\ExamsController@login')->name('login');
        Route::post('/register/user', 'App\Http\Controllers\API\AuthController@register');
        Route::post('/login/user', 'App\Http\Controllers\API\AuthController@login');
        Route::get('/messagecast/branch/index', 'App\Http\Controllers\MessageCastController@index');
        Route::post('/messagecast/marketing/send', 'App\Http\Controllers\MessageCastController@send');
        Route::get('/messagecast/contacts/index', 'App\Http\Controllers\MessageCastController@fetch_contact');
Route::middleware('auth:api')->group(function () {
        Route::post('/login/auth', 'App\Http\Controllers\API\AuthController@login_auth');
        Route::get('/branches/get', 'App\Http\Controllers\InventoryController@branches');
        Route::get('/inventory/index', 'App\Http\Controllers\InventoryController@inventories_list');
        Route::middleware('globalauthorize')->group(function(){
                //->Import Inventory
                //->Permissions
                Route::post('/permission/create', 'App\Http\Controllers\RolesAndPermissionController@permissionsCreate');
                Route::post('/permission/index', 'App\Http\Controllers\RolesAndPermissionController@permissionsIndex');
                Route::post('/permission/edit', 'App\Http\Controllers\RolesAndPermissionController@permissionsEdit');
                Route::post('/permission/update', 'App\Http\Controllers\RolesAndPermissionController@permissionsUpdate');
                Route::post('/permission/trash', 'App\Http\Controllers\RolesAndPermissionController@permissionsTrash');
                //->Roles
                Route::post('/role/create', 'App\Http\Controllers\RolesAndPermissionController@rolesCreate');
                Route::post('/role/index', 'App\Http\Controllers\RolesAndPermissionController@rolesIndex');
                Route::post('/role/edit', 'App\Http\Controllers\RolesAndPermissionController@rolesEdit');
                Route::post('/role/update', 'App\Http\Controllers\RolesAndPermissionController@rolesUpdate');
                Route::post('/role/trash', 'App\Http\Controllers\RolesAndPermissionController@rolesTrash');
                //->Branches
                Route::post('/Branches/create', 'App\Http\Controllers\Branches@BranchesCreate');
                Route::post('/Branches/index', 'App\Http\Controllers\Branches@BranchesIndex');
                Route::post('/Branches/edit', 'App\Http\Controllers\Branches@BranchesEdit');
                Route::post('/Branches/update', 'App\Http\Controllers\Branches@BranchesUpdate');
                Route::post('/Branches/trash', 'App\Http\Controllers\Branches@BranchesTrash');
                //->Users
                Route::post('/Users/create', 'App\Http\Controllers\API\AuthController@register');
                Route::post('/Users/index', 'App\Http\Controllers\API\AuthController@UsersIndex');
                Route::post('/Users/edit', 'App\Http\Controllers\API\AuthController@UsersEdit');
                Route::post('/Users/update', 'App\Http\Controllers\API\AuthController@UsersUpdate');
                Route::post('/Users/trash', 'App\Http\Controllers\API\AuthController@UsersTrash');
                //->GetUserLogin-Info
            
        });
        Route::post('/Users/Authorize', 'App\Http\Controllers\API\AuthController@GetUser');

        //->create customer
        Route::post('/messagecast/marketing/create', 'App\Http\Controllers\MessageCastController@createContact');
        //->update customer
        Route::post('/messagecast/marketing/update', 'App\Http\Controllers\MessageCastController@updateContact');
        //->Get Logs
        Route::post('/messagecast/marketing/logs', 'App\Http\Controllers\MessageCastController@logs');
        //->PerUser Sent
        Route::post('/messagecast/marketing/user/sent', 'App\Http\Controllers\MessageCastController@usersent');
        //->Clear Logs
        Route::post('/messagecast/marketing/clearlogs', 'App\Http\Controllers\MessageCastController@clearlogs');
        //->Delete
        Route::post('/messagecast/marketing/trash', 'App\Http\Controllers\MessageCastController@trashUser');
        
        
        //->PAYMENT REMINDER
    
       
        //->ContactList
        Route::get('/messagecast/paymentreminder/accountlist', 'App\Http\Controllers\CustomerReminderController@GetContacts');
        //GetReminders
        Route::post('/messagecast/paymentreminder/reminders', 'App\Http\Controllers\CustomerReminderController@GetReminders');
        
});
        //->SyncData
        Route::post('/messagecast/sync/data', 'App\Http\Controllers\CustomerReminderController@sync');
        //->SendTriggering
        Route::get('/messagecast/branch/triggering', 'App\Http\Controllers\MessageCastController@sendTigger');
        Route::get('/messagecast/sync/arinvoice', 'App\Http\Controllers\ArInvoice@index');