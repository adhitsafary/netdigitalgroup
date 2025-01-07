<?php

namespace App\Console\Commands;

use App\Http\Controllers\IsolirController;
use Illuminate\Console\Command;

class CleanUpIsolir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'isolir:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up isolir customers inactive for more than 60 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new IsolirController();
        $controller->cleanUp();

        $this->info('Isolir cleanup completed.');
    }
}
