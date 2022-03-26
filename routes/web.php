<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('upload');
});
Route::get('/test', function () {
    return view('test');
});


//Route to uploading a file (Not the view, the actual file upload)
Route::post('/upload-file', 'App\Http\Controllers\FileUploadController@uploadFile');
//Route to setting file download limit, expiry date, password, etc.
Route::get('/set-file-details/{file_id}', 'App\Http\Controllers\FileUploadController@setFileDetails');
//Route to save the file's settings and save them to the database and return the file
Route::post('/save-file-details/{file_id}', 'App\Http\Controllers\FileUploadController@saveFileDetails');
//Route to the file
Route::get('/download-file/{file_id}', 'App\Http\Controllers\FileDownloadController@returnFile');
//Route to downloading a file (The view, not the actual file)
Route::get('/download/{file_id}', 'App\Http\Controllers\FileDownloadViewController@returnFile');
//Route to send a file to an email
Route::post('/send-mail-file', 'App\Http\Controllers\FileDownloadViewController@sendMail');