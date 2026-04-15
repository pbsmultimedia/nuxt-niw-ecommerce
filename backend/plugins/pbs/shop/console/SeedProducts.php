<?php namespace Pbs\Shop\Console;

use Illuminate\Console\Command;
use Pbs\Shop\Updates\PbsShopSeeder;

class SeedProducts extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'products:seed';

    /**
     * @var string The console command description.
     */
    protected $description = 'Seed the database with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding database...');
        
        $seeder = new PbsShopSeeder();
        $seeder->run();
        
        $this->info('Database seeded successfully!');
    }
}