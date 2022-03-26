<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FileDownloadController extends Controller
{
    //
    public function returnFile($file_id)
    {
        try {
            //This function returns the file from the storage.
            //$file is the data of the file stored in the database
            $file = File::where('file_identifier', $file_id)->first();
            //Check if the file can expire

            if ($file->expiry_date != null) {
                $date = Carbon::parse($file->expiry_date); // now date is a carbon instance
                if ($date->isPast()) {
                    //Check how long ago the file expired
                    $date = Carbon::parse($file->expiry_date); // now date is a carbon instance
                    return view('download-error', ['error' => 'This file is past the expiration date. This file expired ' . $date->diffForHumans()]);
                }
            }
            if ($file->download_limit != 0) {
                if ($file->download_count >= $file->download_limit) {
                    return view('download-error', ['error' => 'This file has reached its download limit.']);
                }
            }
            if ($file->is_protected == 1) {
                //If the file is protected, the hash of the password is checked
                if ($file->password == request()->hash) {
                    //If the hash of the password is correct, the file is returned
                    $file->download_count = $file->download_count + 1;
                    $file->save();
                    return Storage::download('dropspace/uploads/' . $file->path, $file->name);
                } else {
                    //If the hash of the password is incorrect, the user is redirected to an error page
                    return view('download-error', ['error' => 'An error occured with the authorization hash of this download. Please try again or contact the admin.']);
                }
            } else {
                //If the file is not protected, the file is returned
                $file->download_count = $file->download_count + 1;
                $file->save();
                return Storage::download('dropspace/uploads/' . $file->path, $file->name);
            }
        } catch (Exception $e) {
            //If an error occurs, the user is redirected to an error page
            return view('download-error', ['error' => $e->getMessage() . '. Contact admin.']);
        }
    }
}
