<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        Log::info('Starting removal of expired files.');
        $files = File::all()->where('deleted_for_expiry', 0);
        foreach ($files as $file) {
            if ($file->expiry_date != null) {
                $date = Carbon::parse($file->expiry_date);
                if ($date->isPast()) {
                    Storage::disk(config('dropspace.ds_storage_type'))->delete('dropspace/uploads/' . $file->path);
                    $file->deleted_for_expiry = 1;
                    $file->save();
                    Log::info('Deleted file: ' . $file->path . ' for expiry (date): ' . $file->expiry_date);
                }
            }
            if($file->download_limit != 0) {
                if($file->download_count >= $file->download_limit) {
                    Storage::disk(config('dropspace.ds_storage_type'))->delete('dropspace/uploads/' . $file->path);
                    $file->deleted_for_expiry = 1;
                    $file->save();
                    Log::info('Deleted file: ' . $file->path . ' for reaching download limit: ' . $file->download_limit);
                }
            }
        }
        Log::info('Finished removing expired files.');
    }
}
