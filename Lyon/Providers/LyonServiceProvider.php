<?php

namespace Lyon\Providers;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\ServiceProvider;
use Lyon\Console\GenerateTokenCommand;

/**
 * Class LyonServiceProvider
 * @package Lyon\Providers
 */
class LyonServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider
     */
    public function register()
    {
        $this->registerTokenCommand();

        $this->commands('lyon.jwt.token');
    }

    /**
     * Register the console command to generate the JWT token.
     */
    protected function registerTokenCommand()
    {
        $this->app->bind('lyon.jwt.token', function() {
            return new GenerateTokenCommand($this->app->make(AuthManager::class));
        });
    }
}