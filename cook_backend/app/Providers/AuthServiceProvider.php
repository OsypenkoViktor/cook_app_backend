<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\CookProcess;
use App\Models\Dish;
use App\Policies\CommentPolicy;
use App\Policies\CookProcessPolicy;
use App\Policies\DishPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Product;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class=>ProductPolicy::class,
        CookProcess::class=>CookProcessPolicy::class,
        Dish::class=>DishPolicy::class,
        Comment::class=>CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
