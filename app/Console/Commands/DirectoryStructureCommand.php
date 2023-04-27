<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DirectoryStructureCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'directory:structure {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the directory structure in a formatted way';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path');
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);


        if (!File::exists($path)) {
            $this->error("Path '{$path}' does not exist.");
            return false;
        }

        if (!File::isDirectory($path)) {
            $this->error("Path '{$path}' is not a directory.");
            return false;
        }

        return $this->displayDirectoryStructure($path);
    }

    private function displayDirectoryStructure($path, $indent = ''): bool
    {
        $entries = File::directories($path);

        foreach ($entries as $entry) {
            $dirName = basename($entry);
            $this->info($indent . $dirName . '/');
            $this->displayDirectoryStructure($entry, $indent . '  ');
        }

        $files = File::files($path);
        foreach ($files as $file) {
            $this->info($indent . '  ├── ' . basename($file));
        }
        return true;
    }

}
