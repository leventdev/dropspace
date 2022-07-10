<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileUploadController extends Controller
{
    //

    /**
     * Welp..... This is the most important function in DropSpace.
     * This handles the file uploading.
     * 
     * In detail:
     * 1. Check if auth is enabled, and authorize the user.
     * 2. Get important data from the request:
     *  - $totalChunks: The total number of chunks the file is broken into.
     *  - $chunkNumber: The number of the chunk that is being processed.
     *  - $resumableIdentifier: The identifier of the file assigned by Resumablejs.
     *  - $clientFilename: The name of the file on the client.
     * 
     * 3. Check if there is a maximum file limit set. If there is, check if the file is too large.
     * 4. Check if there is enough space on the server to store the file.
     * 
     *    Here we come to a the branch that handles file uploads with one chunk (When $totalChunks = 1)
     *    5. Create new File (model) and set it's details.
     *    6. Store the file depending on the storage driver.
     *    7. Save the file to the database.
     *    8. Return the file's ID.
     * 
     *    Here we come to the other branch that handles file uploads with multiple chunks (When $totalChunks > 1)
     *    5. Save chunk to storage.
     *    6. Check if $totalChunks == $chunkNumber. If so, create new File (model) and set it's details.
     *    7. Generate an ID for the file.
     *    7. Assemble the file from the chunks.
     *    8. Store the file depending on the storage driver.
     *    9. Save the file to the database.
     *    10. Return the file's ID.
     *    
     * 
     * @return The response is a JSON object, containing the following:
     * - success: true/false
     * - identifier: The file identifier of the file that was uploaded
     * - chunkNumber: The chunk number of the chunk that was uploaded
     * - totalChunks: The total number of chunks of the file that was uploaded
     * 
     */
    public function uploadChunks()
    {
        //This is gonna be a blast to write
        //Check if auth is required
        if (config('dropspace.ds_security_enabled') == true) {
            //Check if the user is logged in
            if (Auth::check()) {
            } else {
                //If the user is not logged in, we can't continue
                return response()->json(['error' => 'Uploading requires you to be signed in. If this is an error, please contact the admin.'], 400);
            }
        }

        $totalChunks = request()->resumableTotalChunks;
        $chunkNumber = request()->resumableChunkNumber;
        $resumableIdentifier = request()->resumableIdentifier;
        $clientFilename = request()->resumableFilename;

        if (config('dropspace.ds_max_file_size') != 0) {
            Log::info('Max file size is set to: ' . $this->byteConvert(config('dropspace.ds_max_file_size')));
            if (request()->resumableTotalSize > config('dropspace.ds_max_file_size')) {
                $maxServerFileSize = $this->byteConvert(config('dropspace.ds_max_file_size'));
                Log::info('File is larger than the max allowed file size');
                return response()->json(['error' => 'File size exceeds maximum file size. Max file size: ' . $maxServerFileSize], 400);
            }
        }
        //Check available space on server
        if (config("dropspace.ds_storage_type") == 'local') {
            $free_space = disk_free_space(storage_path('app/'));
            if ($free_space < request()->resumableTotalSize) {
                Log::info('Not enough space on server');
                Log::info('Free space on server: ' . $free_space);
                Log::info('Total size of chunks: ' . request()->resumableTotalSize);
                return response()->json(['error' => 'Not enough space on server'], 400);
            }
        }

        if ($totalChunks == 1) {
            //Save file to storage and database
            Log::info('Received 1 chunk long file. Saving file.');
            $file = new File;
            $file->name = $clientFilename;
            $file->extension = pathinfo($clientFilename, PATHINFO_EXTENSION);
            $file->size = request()->resumableTotalSize;
            if (request()->hasHeader('CF-Connecting-IP')) {
                //The IP adress of the uploader is saved in the database, when the application is set up with Cloudflare, this IP adress is changed to Cloudflare's IP adress, but the IP adress of the client is passed through in a header, that's called 'CF-Connecting-IP'
                $file->uploader_ip = request()->header('CF-Connecting-IP');
            } else {
                //If the application is not set up with Cloudflare, getting the IP adress of the client is grabbed straight from the request
                $file->uploader_ip = request()->ip();
            }
            $file->file_identifier = Str::random(12);
            while (DB::table('files')->where('file_identifier', $file->file_identifier)->exists()) {
                $file->file_identifier = Str::random(12);
            }
            Log::info('Generated file data. Saving file [' . $file->file_identifier . '] to storage.');
            if (config('dropspace.ds_storage_type') == 's3') {
                Log::info('Uploading file to S3');
                //Updated using streams
                Storage::putFileAs('dropspace/temp/', request()->file('file'), $file->file_identifier . '.' . $file->extension);
                $stream = Storage::disk('local')->readStream('dropspace/temp/' . $file->file_identifier . '.' . $file->extension);
                Storage::disk('s3')->put('dropspace/uploads/' . $file->file_identifier . '.' . $file->extension, $stream);
                Storage::delete('dropspace/temp/' . $file->file_identifier . '.' . $file->extension);
                //End using streams
                Log::info('Uploaded file to S3');
            } else {
                Log::info('Moving file to local storage');
                Storage::putFileAs('dropspace/uploads/', request()->file('file'), $file->file_identifier . '.' . $file->extension);
                Log::info('Moved file to local storage');
            }
            $file->path = $file->file_identifier . '.' . $file->extension;
            Log::info('Saved file to storage. Saving file [' . $file->file_identifier . '] to database.');
            $file->save();
            Log::info('Saved file to database. Sending response.');
            try {
                //This post call updates the number of total files uploaded to all DropSpace instances. This number is going to be used on the GitHub page of DropSpace.
                //Unless you have to disable this, we appreciate if you don't.
                Http::timeout(5)->post('https://leventdev.me/api/dropspace/file-uploaded');
            } catch (Exception $e) {
                Log::info('Could not update uploaded files');
                Log::info('This isn\'t a big deal, but we like to see how many people use DropSpace, so please report this in an issue on GitHub (leventdev/dropspace)');
                Log::info('Error: ' . $e->getMessage());
            }
            return response()->json(['success' => true, 'identifier' => $file->file_identifier]);
        }
        //If this is the first chunk, create a new file
        //Save file to storage
        Log::info('Received chunk [' . $chunkNumber . '] of [' . $totalChunks . '] for file [' . $resumableIdentifier . ']. Saving chunk.');
        Storage::putFileAs('dropspace/chunks/' . $resumableIdentifier, request()->file('file'), $chunkNumber . '-' . $resumableIdentifier);
        //If the chunk number is the same as the total chunks, combine the chunks and save the file
        if ($chunkNumber == $totalChunks) {
            //Create new file
            Log::info('Received last chunk of [' . $totalChunks . '] for file [' . $resumableIdentifier . ']. Generating database entry.');
            $file = new File;
            $file->name = $clientFilename;
            //get extension from clientFilename
            $file->extension = pathinfo($clientFilename, PATHINFO_EXTENSION);
            $file->size = request()->resumableTotalSize;
            if (request()->hasHeader('CF-Connecting-IP')) {
                //The IP adress of the uploader is saved in the database, when the application is set up with Cloudflare, this IP adress is changed to Cloudflare's IP adress, but the IP adress of the client is passed through in a header, that's called 'CF-Connecting-IP'
                $file->uploader_ip = request()->header('CF-Connecting-IP');
            } else {
                //If the application is not set up with Cloudflare, getting the IP adress of the client is grabbed straight from the request
                $file->uploader_ip = request()->ip();
            }

            if (config('dropspace.ds_security_enabled')) {
                //Save user's email to database
                $file->uploader = Auth::user()->email;
            }

            //generate file_identifier
            $file->file_identifier = Str::random(12);
            while (DB::table('files')->where('file_identifier', $file->file_identifier)->exists()) {
                $file->file_identifier = Str::random(12);
            }
            //make empty file in storage
            Log::info('Generated file data. Saving file [' . $file->file_identifier . '] to storage.');

            Storage::put('dropspace/temp/' . $resumableIdentifier . '-' . $clientFilename, '');
            Log::info('Created temp file: ' . $resumableIdentifier . '-' . $clientFilename);
            $fp = fopen('../storage/app/dropspace/temp/' . $resumableIdentifier . '-' . $clientFilename, 'w'); //opens file in append mode  
            for ($i = 1; $i <= $totalChunks; $i++) {
                //Get the chunk file
                $chunkFile = Storage::get('dropspace/chunks/' . $resumableIdentifier . '/' . $i . '-' . $resumableIdentifier);
                //Get client filename
                $clientFilename = request()->resumableFilename;
                //Storage::append('dropspace/chunks/'.$resumableIdentifier.'-'.$clientFilename, $chunkFile, null);
                fwrite($fp, $chunkFile);
                Log::info('Appended chunk: ' . $i . ' from file:' . 'dropspace/chunks/' . $resumableIdentifier . '/' . $i . '-' . $resumableIdentifier);
                //Delete the chunk file
                //Storage::delete('dropspace/chunks/'.$resumableIdentifier.'/'.$i.'-'.$resumableIdentifier);
            }
            fclose($fp);
            Log::info('Finished appending chunks to file: ' . $resumableIdentifier . '-' . $clientFilename);
            Storage::deleteDirectory('dropspace/chunks/' . $resumableIdentifier);
            Log::info('Deleted chunks directory: dropspace/chunks/' . $resumableIdentifier);
            if (config('dropspace.ds_storage_type') == 's3') {
                Log::info('Uploading file to S3');
                //Updated using streams
                $stream = Storage::disk('local')->readStream('dropspace/temp/' . $resumableIdentifier . '-' . $clientFilename);
                Storage::disk('s3')->put('dropspace/uploads/' . $file->file_identifier . '.' . $file->extension, $stream);
                Storage::delete('dropspace/temp/' . $resumableIdentifier . '-' . $clientFilename);
                //End using streams
                Log::info('Uploaded file to S3');
            } else {
                Log::info('Moving file to local storage');
                Storage::move('dropspace/temp/' . $resumableIdentifier . '-' . $clientFilename, 'dropspace/uploads/' . $file->file_identifier . '.' . $file->extension);
                Log::info('Moved file to local storage');
            }
            $file->path = $file->file_identifier . '.' . $file->extension;
            $file->save();
            Log::info('Saved file to database');
            Log::info('Finished uploading file ' . $file->file_identifier . '.' . $file->extension);
            try {
                //This post call updates the number of total files uploaded to all DropSpace instances. This number is going to be used on the GitHub page of DropSpace.
                //Unless you have to disable this, we appreciate if you don't.
                Http::timeout(5)->post('https://leventdev.com/api/dropspace/file-uploaded');
            } catch (Exception $e) {
                Log::info('Could not update uploaded files');
                Log::info('This isn\'t a big deal, but we like to see how many people use DropSpace, so please report this in an issue on GitHub (leventdev/dropspace)');
                Log::info('Error: ' . $e->getMessage());
            }
            return response()->json(['success' => true, 'identifier' => $file->file_identifier]);
        }
        return response()->json(['success' => true, 'chunkNumber' => $chunkNumber, 'totalChunks' => $totalChunks]);
    }

    /**
     * It takes a number of bytes and returns a human readable string
     * 
     * @param bytes The number of bytes to convert.
     * 
     * @return the size of the file in bytes.
     */
    function byteConvert($bytes)
    {
        if ($bytes == 0)
            return "0.00 B";

        $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $e), 2) . $s[$e];
    }

    /**
     * It checks if the user is logged in if they need to be logged in, 
     * If the file exists and waits to have settings configured, redirect to the upload-settings page, else redirect them to an error page.
     * 
     * @param id The file's identifier
     * 
     * @return The view 'upload-settings' is being returned.
     */
    public function setFileDetails($id)
    {
        //Check if auth is required
        if (config('dropspace.ds_security_enabled') == true) {
            //Check if the user is logged in
            if (Auth::check()) {
            } else {
                //If the user is not logged in, we can't continue
                return view('download-error', ['error' => "You need to be signed in to do that."]);
            }
        }


        //This function is called when the user wants to set the file's details, this is the view that is shown to the user
        $file = File::where('file_identifier', $id)->first();
        if ($file == null) {
            //If the file doesn't exist, the user is redirected to the error page
            return view('download-error', ['error' => "File doesn't exist."]);
        }
        if ($file->finished_uploading == true) {
            return view('download-error', ['error' => "This file has already been uploaded before, you can't modify the settings."]);
        }
        //Filesize in human readable format from $file->size
        return view('upload-settings', ['fileName' => $file->name, 'uploadDate' => $file->created_at, 'fileID' => $file->file_identifier, 'size' => $this->byteConvert($file->size)]);
    }

    /**
     * It saves the file's additional details to the database
     * 
     * @param id The file's identifier
     * @param Request request The request object, which contains the POST data.
     * 
     * @return a redirect to the download page, with the file's identifier and the password as
     * parameters.
     */
    public function saveFileDetails($id, Request $request)
    {
        //Check if auth is required
        if (config('dropspace.ds_security_enabled') == true) {
            //Check if the user is logged in
            if (Auth::check()) {
            } else {
                //If the user is not logged in, we can't continue
                return view('download-error', ['error' => "You need to be signed in to do that."]);
            }
        }
        $file = File::where('file_identifier', $id)->first();
        if ($file == null) {
            return view('download-error', ['error' => "File doesn't exist."]);
        }
        if ($file->finished_uploading == true) {
            return view('download-error', ['error' => "This file has already been uploaded before, you can't modify the settings."]);
        }
        //Saves the file's additional details to the database
        $file->download_limit = $request->input('dlimit');
        $file->download_count = 0;
        $expiryDate = $request->input('expiry');
        //cases for the expiry date, never, 1 week, 1 month, 1 year
        if ($expiryDate == "never") {
            $file->expiry_date = null;
        } elseif ($expiryDate == "1-day") {
            $file->expiry_date = date('Y-m-d H:i:s', strtotime('+1 day'));
        } elseif ($expiryDate == "1-week") {
            $file->expiry_date = date('Y-m-d H:i:s', strtotime('+1 week'));
        } elseif ($expiryDate == "1-month") {
            $file->expiry_date = date('Y-m-d H:i:s', strtotime('+1 month'));
        } elseif ($expiryDate == "1-year") {
            $file->expiry_date = date('Y-m-d H:i:s', strtotime('+1 year'));
        }
        if (request()->passbool == "true") {
            $file->is_protected = true;
            $file->password = sha1($request->input('password'));
        } else {
            $file->is_protected = false;
            $file->password = null;
        }
        $file->finished_uploading = true;
        $file->save();
        if ($file->is_protected) {
            //return redirect to download with password
            //This redirects the user to the download page, with the file's identifier and the password as parameters
            return redirect('/download/' . $file->file_identifier . '?hash=' . $file->password);
        } else {
            //return redirect to download without password
            //This redirects the user to the download page, with the file's identifier.
            return redirect('/download/' . $file->file_identifier);
        }
    }
}
