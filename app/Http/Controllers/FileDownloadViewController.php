<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendFileShare;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class FileDownloadViewController extends Controller
{
    //
    public function sendMail()
    {
        /*$fileid = $request->file_id;
        $email = $request->email;
        $file = DB::table('files')->where('file_identifier', $fileid)->first();
        $filename = $file->name . '.' . $file->extension;
        $url = url('/file/download/' . $fileid . '?hash=' . $request->hash);
        Mail::to($email)->send(new \App\Mail\sendFileShare($url, $filename));*/
        $fileid = request()->file_id;
        $email = request()->email;
        $file = File::where('file_identifier', $fileid)->first();
        $url = secure_url('/download/' . $file->file_identifier . '?hash=' . $file->password);
        Mail::to($email)->send(new SendFileShare($url, $file->name));
    }

    public function returnFile($file_id)
    {
        try {

            //This function returns the download view of a file
            //Check if the file exists in the database based on the file_id 
            if (File::where('file_identifier', $file_id)->exists()) {
                //Load file data into $file
                $file = File::where('file_identifier', $file_id)->first();
                //Check if file is saved in storage
                if (Storage::exists('dropspace/uploads/' . $file->path)) {
                    //Check if the file is protected
                    if ($file->is_protected == 1) {
                        //If the file is protected, check if the request has a hash
                        if (request()->has('hash') || request()->has('password')) {
                            //If the request has a hash, check if the hash is correct
                            if ($file->password == request()->hash || $file->password == sha1(request()->password)) {
                                //If the hash is correct, return the download view
                                $downloadURL = secure_url('/download-file/' . $file->file_identifier . '?hash=' . $file->password);
                                //File's name without extension
                                $shortName = str_replace('.' . $file->extension, '', $file->name);
                                $hasDownloadLimit = false;
                                $hasExpiryDate = false;
                                $expiryDate = null;
                                $canExpire = false;
                                $expiryType = null;
                                $downloadLimitAmount = null;
                                if ($file->download_limit != 0) {
                                    $canExpire = true;
                                    $hasDownloadLimit = true;
                                    $expiryType = 'download';
                                    $downloadLimitAmount = $file->download_limit - $file->download_count . ' downloads';
                                }
                                if ($file->expiry_date != null) {
                                    $canExpire = true;
                                    $hasExpiryDate = true;
                                    $expiryType = 'date';
                                    //Return time until expiry in 'x days, y hours' format
                                    $date = Carbon::parse($file->expiry_date); // now date is a carbon instance
                                    //Return time until expiry in 
                                    //Return time until expiry in 'x days, y hours' format
                                    //Example: '2 days, 3 hours'
                                    $expiryDate = $date->diffForHumans('now', true, false, 2, true);
                                }
                                if ($hasDownloadLimit && $hasExpiryDate) {
                                    $expiryType = 'both';
                                }
                                //Check if the file is expired or not
                                if ($canExpire == true) {
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
                                }
                                return view('download', ['fileNameTag' => $file->name, 'fileURL' => $downloadURL, 'fileName' => $shortName, 'fileExtension' => $file->extension, 'uploadDate' => $file->created_at, 'fileShareURL' => secure_url('/download/' . $file->file_identifier), 'fileID' => $file->file_identifier, 'password_protected' => true, 'hash' => $file->password, 'canExpire' => $canExpire, 'expiryType' => $expiryType, 'expiryDate' => $expiryDate, 'downloadLimitAmount' => $downloadLimitAmount]);
                            } else {
                                //If the hash is incorrect, return the download error view
                                if (request()->has('hash')) {
                                    return view('download-error', ['error' => 'An error occured with the authorization hash of this download. Please try again or contact the admin.']);
                                } else {

                                    if (request()->has('password')) {
                                        return view('password', ['fileURL' => secure_url('/download/' . $file->file_identifier)]);
                                    } else {
                                        return view('download-error', ['error' => 'An error occured with the authorization of the password or the hash of this download. please try again or contact the admin.']);
                                    }
                                }
                            }
                        } else {
                            //File is protected but a hash hasnt been provided, check for password
                            return view('password', ['fileURL' => secure_url('/download/' . $file->file_identifier)]);
                        }
                    } else {
                        //If the file is not protected, the download view is returned
                        $downloadURL = secure_url('/download-file/' . $file->file_identifier);
                        //File's name without extension
                        $shortName = str_replace('.' . $file->extension, '', $file->name);
                        $hasDownloadLimit = false;
                        $hasExpiryDate = false;
                        $expiryDate = null;
                        $canExpire = false;
                        $expiryType = null;
                        $downloadLimitAmount = null;
                        if ($file->download_limit != 0) {
                            $canExpire = true;
                            $hasDownloadLimit = true;
                            $expiryType = 'download';
                            $downloadLimitAmount = $file->download_limit - $file->download_count . ' downloads';
                        }
                        if ($file->expiry_date != null) {
                            $canExpire = true;
                            $hasExpiryDate = true;
                            $expiryType = 'date';
                            //Return time until expiry in 'x days, y hours' format
                            $date = Carbon::parse($file->expiry_date); // now date is a carbon instance
                            //Return time until expiry in 
                            //Return time until expiry in 'x days, y hours' format
                            //Example: '2 days, 3 hours'
                            $expiryDate = $date->diffForHumans('now', true, false, 2, true);
                        }
                        if ($hasDownloadLimit && $hasExpiryDate) {
                            $expiryType = 'both';
                        }
                        //Check if the file is expired or not
                        if ($canExpire == true) {
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
                        }
                        return view('download', ['fileNameTag' => $file->name, 'fileURL' => $downloadURL, 'fileName' => $shortName, 'fileExtension' => $file->extension, 'uploadDate' => $file->created_at, 'fileShareURL' => secure_url('/download/' . $file->file_identifier), 'fileID' => $file->file_identifier, 'password_protected' => false, 'canExpire' => $canExpire, 'expiryType' => $expiryType, 'expiryDate' => $expiryDate, 'downloadLimitAmount' => $downloadLimitAmount]);
                    }
                } else {
                    //If file is not saved in storage, return error page
                    if ($file->deleted_for_expiry == 1) {
                        return view('download-error', ['error' => 'This file has been deleted for expiring.']);
                    } else {
                        return view('download-error', ['error' => 'The file you are trying to download does not exist.']);
                    }
                }
            } else {
                //If the file does not exist, the user is redirected to an error page
                return view('download-error', ['error' => 'The file you are trying to download does not exist.']);
            }
        } catch (Exception $e) {
            //If an error occurs, the user is redirected to an error page
            return view('download-error', ['error' => $e->getMessage() . '. Contact admin.']);
        }
    }
}
