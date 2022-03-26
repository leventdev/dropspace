<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use Illuminate\Support\Carbon;

class DeleteExpiredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropspace:remove-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all files from storage that have expired.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = File::all()->where('deleted_for_expiry', 0);
        foreach ($files as $file) {
            if ($file->expiry_date != null) {
                $date = Carbon::parse($file->expiry_date);
                if ($date->isPast()) {
                    Storage::delete('dropspace/uploads/' . $file->path);
                    $file->deleted_for_expiry = 1;
                    $file->save();
                    $this->info('Deleted file: ' . $file->path . ' for expiry (date): ' . $file->expiry_date);
                }
            }
            if($file->download_limit != 0) {
                if($file->download_count >= $file->download_limit) {
                    Storage::delete('dropspace/uploads/' . $file->path);
                    $file->deleted_for_expiry = 1;
                    $file->save();
                    $this->info('Deleted file: ' . $file->path . ' for reaching download limit: ' . $file->download_limit);
                }
            }
        }
        $this->info(' ');
        $this->info('Finished removing expired files.');
    }
}
