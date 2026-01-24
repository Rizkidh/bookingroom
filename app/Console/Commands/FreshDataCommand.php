<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class FreshDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all inventory data, activity logs, and related files while preserving users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('no-interaction') && !$this->confirm('This will delete ALL inventory items, units, logs, and photos. Are you sure?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Starting fresh data cleanup...');

        // 1. Clear Database Tables
        $this->info('Clearing database tables...');
        Schema::disableForeignKeyConstraints();

        $tables = [
            'activity_logs',
            'inventory_units',
            'inventory_items',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->comment("Truncating table: {$table}");
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
        $this->info('Database tables cleared successfully.');

        // 2. Clear Storage Files
        $this->info('Clearing storage files...');
        
        $directories = [
            'public/unit_photos',
            'public/qrcodes',
        ];

        foreach ($directories as $dir) {
            $path = base_path($dir);
            if (File::exists($path)) {
                $this->comment("Cleaning directory: {$dir}");
                
                // Get all files in directory
                $files = File::allFiles($path);
                
                foreach ($files as $file) {
                    // Skip .gitignore if it exists
                    if ($file->getFilename() === '.gitignore') {
                        continue;
                    }
                    File::delete($file->getRealPath());
                }
            }
        }

        $this->info('Storage files cleared successfully.');
        $this->info('Fresh data reset complete! Users and system settings have been preserved.');
    }
}
