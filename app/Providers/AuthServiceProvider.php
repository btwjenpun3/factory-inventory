<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Policies\PurchasePolicy;
use App\Http\Controllers\System\SettingController;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin-only', function ($user) {            
            return $user->role->name == 'admin';
        }); 
        
        Gate::define('has-activated', function ($setting) {
            $callFunction   = new SettingController;
            $keyValue       = $callFunction->keyId();
            $checkKey       = Setting::where('setting', 'activation_code')->first();
            return $checkKey->value == $keyValue;
        });  

       /**
        * Dibawah ini adalah Gate yang terhubung dengan Permission
        */

        /**
         * Gate untuk Edit
         */

        Gate::define('edit-approval-order-plan', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('edit-approval-order-plan');
        });

        Gate::define('edit-purchase-purchasing', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('edit-purchase-purchasing');
        });

        Gate::define('edit-master-allocation', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('edit-master-allocation');
        });

        Gate::define('edit-master-buyer', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('edit-master-buyer');
        });

        Gate::define('edit-master-supplier', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('edit-master-supplier');
        });

        Gate::define('edit-warehouse-received', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('edit-warehouse-received');
        });

        /**
         * Gate untuk Delete
         */
        
        Gate::define('delete-approval-order-plan', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('delete-approval-order-plan');
        });

        Gate::define('delete-master-allocation', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('delete-master-allocation');
        });

        Gate::define('delete-master-buyer', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('delete-master-buyer');
        });

        Gate::define('delete-master-supplier', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('delete-master-supplier');
        });        

        /**
         * Gate untuk View
         */
        
        Gate::define('view-approval-order-plan', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-approval-order-plan');
        }); 

        Gate::define('view-purchase-purchasing', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-purchase-purchasing');
        }); 

        Gate::define('view-master-allocation', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-master-allocation');
        });     
        
        Gate::define('view-master-item', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-master-item');
        });

        Gate::define('view-merchandiser-order-plan', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-merchandiser-order-plan');
        });

        Gate::define('view-merchandiser-production-card', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-merchandiser-production-card');
        });

        Gate::define('view-warehouse-received', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-warehouse-received');
        });

        Gate::define('view-graphic', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-graphic');
        });

        Gate::define('view-export', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-export');
        });
    }
}
