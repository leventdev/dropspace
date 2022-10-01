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
     * Rewrite of uploadChunks() function to be compatible with the DropSpace Chunker
     * DropSpace Chunker is my self-made replacement of Resumable.js
     * 
     * DropSpace Chunker uses two POST calls to upload a file.
     * The first post call uploads the chunks of the file.
     * 
     * The second post call happens after the chunker uploaded all the chunks.
     * This post calls for the server to process the chunks. Combine them into a single file, and do any post-processing.
     * 
     * Parameters of the first post call:
     * 'file' - The chunk of the file
     * 'chunk' - The current chunk number
     * 'chunks' - The total number of chunks
     * 'filename' - The client-side filename
     * 'filesize' - The client-side filesize
     * '_token' - The CSRF token. Authorized by the middleware
     * 
     * 'dropid' - Explained below
     * DropID replaces the 'resumableIdentifier' used by Resumable.js
     * DropID's are a random, client side generated, pseudo-random identifiers built up from a a combination of the client-side filename, the current timestamp and a random string.
     * You can use the below code to generate a DropID (JavaScript):
     * var DropID = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + '-' + fileName + '-' + Date.now();
     * 
     * 
     * Parameters of the second post call:
     * 'fileName' - The client-side filename
     * 'totalChunks' - The total number of chunks
     * 'fileSize' - The client-side filesize
     * '_token' - The CSRF token. Authorized by the middleware
     * 'dropid' - Explained above, in the first call.
     */


    /**
     * Comparison of the two chunkers, and their back-end implementation.
     *
     * Resumable.js:
     * -------------
     * Resumable.js has a single POST call to upload a file.
     * Once the chunk being uploaded, and the total amount of chunks equals. The server will process the chunks.
     * Resumable.js is written by 23, and hasn't been maintained in a while. (Last commit over a year ago as of 2022, Sept.)
     * As of commit 2781f3b, the Resumable.js chunker implementation in DropSpace is broken.
     * Investigating back-end reports. There is a problem with the uploaded file.
     * I was unable to reproduce the issue on my local setup, but it seems to be a problem on production deployments.
     * 
     * 
     * DropSpace Chunker:
     * ------------------
     * The DropSpace Chunker is my self-made replacement of Resumable.js
     * DropSpace Chunker uses two POST calls to upload a file.
     * The first post call uploads the chunks of the file.
     * After the chunker uploaded all the chunks, it sends a second post call to the server.
     * This post calls for the server to process the chunks. Combine them into a single file, and do any post-processing.
     */

    public function uploadChunk()
    {
        //Auth
        if (config('dropspace.ds_security_enabled') == true) {
            //Check if the user is logged in
            if (Auth::check()) {
            } else {
                //If the user is not logged in, we can't continue
                return response()->json(['error' => 'Uploading requires you to be signed in. If this is an error, please contact the admin.'], 400);
            }
        }

        //Preprocessing
        if (config('dropspace.ds_max_file_size') != 0) {
            Log::info('Max file size is set to: ' . $this->byteConvert(config('dropspace.ds_max_file_size')));
            if (request()->filesize > config('dropspace.ds_max_file_size')) {
                $maxServerFileSize = $this->byteConvert(config('dropspace.ds_max_file_size'));
                Log::info('File is larger than the max allowed file size');
                return response()->json(['error' => 'File size exceeds maximum file size. Max file size: ' . $maxServerFileSize], 400);
            }
        }

        if (config("dropspace.ds_storage_type") == 'local') {
            $free_space = disk_free_space(storage_path('app/'));
            if ($free_space < request()->filesize) {
                Log::info('Not enough space on server');
                Log::info('Free space on server: ' . $free_space);
                Log::info('Total size of chunks: ' . request()->filesize);
                return response()->json(['error' => 'Not enough space on server'], 400);
            }
        }

        //Chunk saving
        $chunkNumber = request()->chunk;
        $totalChunks = request()->chunks;
        $dropid = request()->dropid;
        Log::info('Received chunk [' . $chunkNumber . '] of [' . $totalChunks . '] for file [' . $dropid . ']. Saving chunk.');
        Storage::putFileAs('dropspace/chunks/' . $dropid, request()->file('file'), $chunkNumber . '.part');
        //Get file size of the chunk we just received
        $chunkSize = request()->file('file')->getSize();
        //Get the file size of the chunk we just saved to storage
        $chunkSizeOnStorage = Storage::size('dropspace/chunks/' . $dropid . '/' . $chunkNumber . '.part');
        Log::info('[' . $chunkNumber . '] Received chunk size: ' . $chunkSize . ' bytes. Saved chunk size: ' . $chunkSizeOnStorage . ' bytes');
    }


    public function processChunks()
    {
        //Auth
        if (config('dropspace.ds_security_enabled') == true) {
            //Check if the user is logged in
            if (Auth::check()) {
            } else {
                //If the user is not logged in, we can't continue
                return response()->json(['error' => 'Uploading requires you to be signed in. If this is an error, please contact the admin.'], 400);
            }
        }

        //Preprocessing
        if (config('dropspace.ds_max_file_size') != 0) {
            Log::info('Max file size is set to: ' . $this->byteConvert(config('dropspace.ds_max_file_size')));
            if (request()->filesize > config('dropspace.ds_max_file_size')) {
                $maxServerFileSize = $this->byteConvert(config('dropspace.ds_max_file_size'));
                Log::info('File is larger than the max allowed file size');
                return response()->json(['error' => 'File size exceeds maximum file size. Max file size: ' . $maxServerFileSize], 400);
            }
        }


        
        if (config("dropspace.ds_storage_type") == 'local') {
            $free_space = disk_free_space(storage_path('app/'));
            if ($free_space < request()->filesize) {
                Log::info('Not enough space on server');
                Log::info('Free space on server: ' . $free_space);
                Log::info('Total size of chunks: ' . request()->filesize);
                return response()->json(['error' => 'Not enough space on server'], 400);
            }
        }


        $file = new File;
        $file->name = request()->fileName;
        //get extension from clientFilename
        $file->extension = pathinfo(request()->fileName, PATHINFO_EXTENSION);
        $file->size = request()->fileSize;
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

        Storage::makeDirectory('dropspace/uploads/temp');
        $fp = fopen(Storage::path('dropspace/uploads/temp/' . request()->dropid), 'w'); //opens file in append mode
        for ($i = 0;$i <= request()->totalChunks;$i++) {
            $chunkFile = Storage::get('dropspace/chunks/' . request()->dropid . '/' . $i . '.part');
            fwrite($fp, $chunkFile);
            Log::info('Appended chunk: ' . $i . ' from file:' . 'dropspace/chunks/' . request()->dropid . '/' . $i . '.part');
        }
        Storage::deleteDirectory('dropspace/chunks/' . request()->dropid);
        Log::info('Deleted chunks directory: dropspace/chunks/' . request()->dropid);

        if (config('dropspace.ds_storage_type') == 's3') {
            Log::info('Uploading file to S3');
            //Updated using streams
            $stream = Storage::disk('local')->readStream('dropspace/temp/' . request()->dropid);
            Storage::disk('s3')->put('dropspace/uploads/' . $file->file_identifier . '.' . $file->extension, $stream);
            Storage::delete('dropspace/uploads/temp/' . request()->dropid);
            //End using streams
            Log::info('Uploaded file to S3');
        } else {
            Log::info('Moving file to local storage');
            Storage::move('dropspace/uploads/temp/' . request()->dropid, 'dropspace/uploads/' . $file->file_identifier . '.' . $file->extension);
            Log::info('Moved file to local storage');
        }
        $file->path = $file->file_identifier . '.' . $file->extension;
        $file->save();


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
