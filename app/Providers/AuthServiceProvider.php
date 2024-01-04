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
        
        Gate::define('view-purchase', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-purchase');
        });

        Gate::define('view-master-item', function ($user) {
            $data = $user->role->permissions->pluck('permission');
            return $data->contains('view-master-item');
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
