<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LaraforgePublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraforge:publish {module : The name of the generated module (e.g. Products)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a generated CRUD module from codegenerator/ into the live application codebase';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $module = ucfirst($this->argument('module'));
        $folderPath = base_path("codegenerator/{$module}");

        if (!File::isDirectory($folderPath)) {
            $this->error("Generated folder for module '{$module}' does not exist inside codegenerator/.");
            return Command::FAILURE;
        }

        $this->info("Publishing module '{$module}' to application codebase...");

        File::copyDirectory($folderPath, base_path());

        $this->info("✅ Module '{$module}' successfully published to app/ and resources/!");
        return Command::SUCCESS;
    }
}
