<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\File;

class ClearUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropspace:clear-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all files from storage and clears the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = File::all();
        foreach ($files as $file) {
            if(config('dropspace.ds_storage_type') == 's3') {
                Storage::disk('s3')->delete('dropspace/uploads/' . $file->path);
            } else {
                Storage::delete('dropspace/uploads/' . $file->path);
            }
            $this->info('['.config('dropspace.ds_storage_type').'] Deleted file: ' . $file->name . ' saved at ' . $file->path . '.');
        }
        DB::table('files')->delete();
        $this->info(' ');
        $this->info('All files have been deleted.');
        return 'Command completed.';
    }
}
