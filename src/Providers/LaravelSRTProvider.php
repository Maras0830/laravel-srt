<?php
namespace Maras0830\LaravelSRT\Providers;

use Illuminate\Support\ServiceProvider;
use Maras0830\LaravelSRT\Commands\RepositoryCommandGenerator;
use Maras0830\LaravelSRT\Commands\ServiceCommandGenerator;
use Maras0830\LaravelSRT\Commands\SRTCommandGenerator;
use Maras0830\LaravelSRT\Commands\TransFormerCommandGenerator;

class LaravelSRTProvider extends ServiceProvider
{
    protected $commands = [
        'ServiceMake' => 'command.service.make',
        'RepositoryMake' => 'command.repository.make',
        'TransformerMake' => 'command.transformer.make',
        'SRTMake' => 'command.srt.make',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands($this->commands);
    }

    /**
     * Register the given commands.
     *
     * @param  array $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerServiceMakeCommand()
    {
        $this->app->singleton('command.service.make', function ($app) {
            return new ServiceCommandGenerator($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRepositoryMakeCommand()
    {
        $this->app->singleton('command.repository.make', function ($app) {
            return new RepositoryCommandGenerator($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerTransformerMakeCommand()
    {
        $this->app->singleton('command.transformer.make', function ($app) {
            return new TransFormerCommandGenerator($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSRTMakeCommand()
    {
        $this->app->singleton('command.srt.make', function () {
            return new SRTCommandGenerator();
        });
    }
}