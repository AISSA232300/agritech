<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the database with migrations and seeders';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up the database...');

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Running seeders...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Database setup completed successfully!');

        return 0;
    }
}
