<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\ShareCode;
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
     * It checks for expired files and sharecodes and deletes them
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
            if ($file->download_limit != 0) {
                if ($file->download_count >= $file->download_limit) {
                    Storage::disk(config('dropspace.ds_storage_type'))->delete('dropspace/uploads/' . $file->path);
                    $file->deleted_for_expiry = 1;
                    $file->save();
                    Log::info('Deleted file: ' . $file->path . ' for reaching download limit: ' . $file->download_limit);
                }
            }
        }
        Log::info('Finished removing expired files.');
        Log::info('Starting removal of expired sharecodes.');
        $sharecodes = ShareCode::all();
        foreach ($sharecodes as $sharecode) {
            $date = Carbon::parse($sharecode->expiry_date);
            if ($date->isPast()) {
                $sharecode->delete();
                Log::info('Deleted sharecode: ' . $sharecode->code . ' for expiry (date): ' . $sharecode->expiry_date);
            }
            if($sharecode->used == 1) {
                $sharecode->delete();
                Log::info('Deleted sharecode: ' . $sharecode->code . ' because it has been used.');
            }
        }

        //Check for expired files based on ds_auto_expiry_days no matter if the file has an expiry date specified
        if(config('dropspace.ds_auto_expiry')){
            Log::info('Starting removal of expired files (for no-expiry options).');
            $files = File::all()->where([
                ['expiry_date', null],
                ['deleted_for_expiry', 0],
                ['download_limit', 0]
            ]);
            foreach ($files as $file) {
                $date = Carbon::parse($file->created_at);
                $date->addDays(config('dropspace.ds_auto_expiry_days'));
                if ($date->isPast()) {
                    Storage::disk(config('dropspace.ds_storage_type'))->delete('dropspace/uploads/' . $file->path);
                    $file->deleted_for_expiry = 1;
                    $file->save();
                    Log::info('Deleted file: ' . $file->path . ' for expiry (server set default)');
                }
            }

        }
    }
}
