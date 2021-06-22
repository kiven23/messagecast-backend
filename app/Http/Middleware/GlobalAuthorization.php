<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class GlobalAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
 
        if($request->is('api/permission/create')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/permission/index')){
            if(Auth::user()->hasAnyPermission('Authorize User','Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/permission/edit')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/permission/update')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/permission/trash')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }

        //-->ROLE MIDDLEWARE
        if($request->is('api/role/create')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/role/index')){
            if(Auth::user()->hasAnyPermission('Authorize User','Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/role/edit')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/role/update')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        if($request->is('api/role/trash')){
            if(Auth::user()->hasAnyPermission('Authorization Access')){
                return $next($request); 
            }
        }
        //-->END
        //-->BRANCHES MIDDLEWARE
        if($request->is('api/Branches/create')){
            if(Auth::user()->hasAnyPermission(['Create Branch','Delete Branch','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Branches/index')){
            if(Auth::user()->hasAnyPermission(['Create Branch','Delete Branch','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Branches/edit')){
            if(Auth::user()->hasAnyPermission(['Create Branch','Delete Branch','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Branches/update')){
            if(Auth::user()->hasAnyPermission(['Create Branch','Delete Branch','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Branches/trash')){
            if(Auth::user()->hasAnyPermission(['Create Branch','Delete Branch','Access Settings'])){
                return $next($request); 
            }
        }
        //-->END
        //-->USERS MIDDLEWARE
        if($request->is('api/Users/create')){
            if(Auth::user()->hasAnyPermission(['Create User','Delete User','Authorize User','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Users/index')){
            if(Auth::user()->hasAnyPermission(['Create User','Delete User','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Users/update')){
            if(Auth::user()->hasAnyPermission(['Create User','Delete User','Authorize User','Access Settings'])){
                return $next($request); 
            }
        }
        if($request->is('api/Users/trash')){
            if(Auth::user()->hasAnyPermission(['Create User','Delete User','Access Settings'])){
                return $next($request); 
            }
        }
 
        //-->END
        //INVENTORY MIDDLEWARE
        if($request->is('api/inventory/index')){
            if(Auth::user()->can('View Inventory Reconcile')){
                return $next($request); 
            }
        }
        if($request->is('api/inventory/index/update')){
            if(Auth::user()->can('Edit Inventory Reconcile')){
                return $next($request); 
            }
        }
        // if($request->is('api/import/inventory')){
        //     if(Auth::user()->can('Import Inventory')){
        //         return $next($request); 
        //     }
        // }
        //-->END
        return abort(404, 'Unauthorized');
    }
}
