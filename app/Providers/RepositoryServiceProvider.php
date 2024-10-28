<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserManagerRepositoryInterface;
use App\Repositories\UserManagerRepository;
use App\Interfaces\MailServerRepositoryInterface;
use App\Repositories\MailServerRepository;
use App\Interfaces\EmailRecievedRepositoryInterface;
use App\Repositories\EmailRecievedRepository;
use App\Interfaces\EmailContentRepositoryInterface;
use App\Repositories\EmailContentRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserManagerRepositoryInterface::class, UserManagerRepository::class);
        $this->app->bind(MailServerRepositoryInterface::class, MailServerRepository::class);
        $this->app->bind(EmailRecievedRepositoryInterface::class, EmailRecievedRepository::class);
        $this->app->bind(EmailContentRepositoryInterface::class, EmailContentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
