<?php

namespace App\Providers;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Enums\UserRole;
use App\Infrastructure\Events\EventDispatcherInterface;
use App\Infrastructure\Events\PasswordResetRequested;
use App\Infrastructure\Events\SimpleEventDispatcher;
use App\Infrastructure\Events\UserRegistered;
use App\Infrastructure\Listeners\SendPasswordResetEmail;
use App\Infrastructure\Listeners\SendWelcomeEmail;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Infrastructure\Persistence\MongoUserRepository;
use App\Mail\ResetPasswordMail;
use App\Repositories\BookRepository;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Services\BookService;
use App\Services\Contracts\BookServiceInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelEventDispatcher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);
        // Bind the Interface to the Eloquent Implementation
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        // $this->app->bind(UserRepositoryInterface::class, MongoUserRepository::class);
        $this->app->singleton(EventDispatcherInterface::class, LaravelEventDispatcher::class);


        // Create a single instance of the dispatcher (Singleton)
        $this->app->singleton(SimpleEventDispatcher::class, function ($app) {
            $dispatcher = new SimpleEventDispatcher();

            // Register your Listeners here
            $dispatcher->listener(UserRegistered::class, function ($event) {
                    (new SendWelcomeEmail())->handle($event);
                }
            );


            return $dispatcher;
        });
        // // Bind the Event Dispatcher as a Singleton
        // $this->app->singleton(SimpleEventDispatcher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // If the user is an Admin, grant all permissions immediately
        Gate::before(function ($user, $ability) {
            return $user->role === UserRole::ADMIN ? true : null;
        });
        // Define 'admin-only' once using your Enum logic
        Gate::define('admin-only', fn($user) => $user->role === UserRole::ADMIN);

        // Define 'update-books' for both Admins and Librarians
        Gate::define(
            'update-books',
            fn($user) =>
            in_array($user->role, [
                UserRole::ADMIN,
                UserRole::LIBRARIAN
            ])
        );
        // LINK THE EVENT TO THE LISTENER HERE
        Event::listen(
            PasswordResetRequested::class,
            SendPasswordResetEmail::class,
        );
    }
}
