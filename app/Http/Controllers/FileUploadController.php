<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    //
    public function uploadFile()
    {
        try {
            //This function saves the file to the storage, and saves the file's data to the database.
            //$uploadedFile becomes the file to be saves to storage
            $uploadedFile = request()->file('file');
            //$file is the data of the file that is saved in the database
            $file = new File();
            //$file->file_identifier is the unique identifier of the file, only one file can have the same identifier
            $file->file_identifier = Str::random(12);
            while (DB::table('files')->where('file_identifier', $file->file_identifier)->exists()) {
                $file->file_identifier = Str::random(12);
            }
            //Generic data, used for the database and returning the file as it was uploaded (same name)
            $file->name = $uploadedFile->getClientOriginalName();
            $file->extension = $uploadedFile->getClientOriginalExtension();
            $file->size = $uploadedFile->getSize();
            //$file->path is the path of the file, this is the path of the file in the storage, and looks like {file_identifier}.{extension}
            $file->path = $file->file_identifier . '.' . $file->extension;
            if (request()->hasHeader('CF-Connecting-IP')) {
                //The IP adress of the uploader is saved in the database, when the application is set up with Cloudflare, this IP adress is changed to Cloudflare's IP adress, but the IP adress of the client is passed through in a header, that's called 'CF-Connecting-IP'
                $file->uploader_ip = request()->header('CF-Connecting-IP');
            } else {
                //If the application is not set up with Cloudflare, getting the IP adress of the client is grabbed straight from the request
                $file->uploader_ip = request()->ip();
            }
            //This saves the file to the storage, this is where the file is actually saved. This is not a publically accessible path, but the file can be returned from the storage.
            Storage::putFileAs('dropspace/uploads/', $uploadedFile, $file->path);
            if(request()->passbool == 'true'){
                //If the file is protected, the password is saved in the database
                $file->is_protected = true;
                $file->password = sha1(request()->password);
            }
            else{
                //If the file is not protected, the password is set to null
                $file->is_protected = false;
                $file->password = '';
            }
            //This saves the file's data to the database
            $file->save();
            if($file->is_protected){
                //return redirect to download with password
                //This redirects the user to the download page, with the file's identifier and the password as parameters
                return redirect('/download/'.$file->file_identifier . '?hash='. $file->password);
            }
            else{
                //return redirect to download without password
                //This redirects the user to the download page, with the file's identifier.
                return redirect('/download/'.$file->file_identifier);
            }
        } 
        catch (Exception $e) {
            //If an error occurs, the error is returned to the user
            return view('download-error', ['error' => $e->getMessage() . ". Contact admin."]);
        }
    }
}