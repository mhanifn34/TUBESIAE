<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // <--- INI DIA YANG BENER
use App\Models\Post; // Import model Post lo
use App\Models\User; // Import model User lo

use Illuminate\Support\Facades\View;     // <--- TAMBAHIN INI
use Illuminate\Support\Facades\Cache;    // <--- TAMBAHIN INI
use App\Models\Community;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ... (biarin aja isinya kalo udah ada)
    
        Gate::define('update-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
            // Kalo mau admin juga bisa:
            // return $user->id === $post->user_id || $user->isAdmin(); 
        });
    
        Gate::define('delete-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });


        View::composer('*', function ($view) {
            
           // ⬇️ AMBIL DATA LANGSUNG ⬇️
    $modalCommunities = Community::select('id', 'name')->orderBy('name')->get();
    // ⬆️ SELESAI ⬆️

    $view->with('modalCommunities', $modalCommunities);
});

    }
    
}
