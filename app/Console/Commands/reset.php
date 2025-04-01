<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate reset';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Artisan::call('migrate:reset --force');
    }
}
