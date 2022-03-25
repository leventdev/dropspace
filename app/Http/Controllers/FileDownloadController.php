<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    //
    public function returnFile($file_id){
        try
        {
            //This function returns the file from the storage.
            //$file is the data of the file stored in the database
            $file = File::where('file_identifier', $file_id)->first();
            if ($file->is_protected == 1){
                //If the file is protected, the hash of the password is checked
                if($file->password == request()->hash){
                    //If the hash of the password is correct, the file is returned
                    return Storage::download('dropspace/uploads/'.$file->path, $file->name);
                }
                else{
                    //If the hash of the password is incorrect, the user is redirected to an error page
                    return view('download-error', ['error' => 'An error occured with the authorization hash of this download. Please try again or contact the admin.']);
                }
            }else{
                //If the file is not protected, the file is returned
                return Storage::download('dropspace/uploads/'.$file->path, $file->name);
            }
        }
        catch (Exception $e) 
        {
            //If an error occurs, the user is redirected to an error page
            return view('download-error', ['error' => $e->getMessage().'. Contact admin.']);
        }
    }
}
