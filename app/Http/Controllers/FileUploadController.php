<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    //
    public function uploadFile(){
        try{
            //save file
            $file = request()->file('file');
            $fileActualname = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $fileShortName = str_replace('.' . $fileExtension, '', $fileActualname);
            $uploadDate = date("Y.m.d H:i");
            $fileIdentifier = Str::random(12);
            while (DB::table('files')->where('file_identifier', $fileIdentifier)->exists()) {
                $fileIdentifier = Str::random(12);
            }
            $file->storeAs('public/files/uploads/', $fileIdentifier . '.' . $fileExtension);
            $fileName =  $fileIdentifier . '.' . $fileExtension;
            $ip = request()->header('CF-CONNECTING-IP');
            $ip = $ip ? $ip : request()->ip();
            if (request()->passbool == 'true') {
                //make sha1 hash of password
                $password = sha1(request()->get('password'));
                $isProtected = true;
            } else {
                $isProtected = false;
                $password = null;
            }
            DB::table('files')->insert(
                ['file_identifier' => $fileIdentifier, 'path' => $fileName, 'name' => $fileActualname, 'extension' => $fileExtension, 'size' => $fileSize, 'upload_date' => $uploadDate, 'uploader_ip' => $ip, 'is_protected' => $isProtected, 'password' => $password]
            );
            if(request()->passbool == 'true'){
                return redirect('/download/' . $fileIdentifier . '?password=' . request()->get('password'));
            }else{
                return redirect('/download/' . $fileIdentifier);
            }
        }
        catch(Exception $e){
            return view('download-error', ['error' => $e->getMessage() . ". Contact admin."]);
        }
    }
}