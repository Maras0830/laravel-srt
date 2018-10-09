<?php
namespace Maras0830\LaravelSRT\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SRTCommandGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:srt {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Service-Repository-Transformer class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('make:service', [
            'name' => $this->argument('name') . 'Service'
        ]);

        Artisan::call('make:repository', [
            'name' => $this->argument('name') . 'Repository'
        ]);

        Artisan::call('make:transformer', [
            'name' => $this->argument('name') . 'Transformer'
        ]);
    }
}
