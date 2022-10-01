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
//Route to default page. Login page if not authenticated, upload page if authenticated or security not enabled.
Route::get('/', 'App\Http\Controllers\LoginController@goToUpload')->name('upload');


/* New routes for DropSpace Chunker */
//Route for uploading the chunks
Route::post('/chunker/upload/chunks', 'App\Http\Controllers\FileUploadController@uploadChunk')->name('uploadChunk');
//Route for processing the chunker
Route::post('/chunker/upload/process', 'App\Http\Controllers\FileUploadController@processChunks')->name('processChunks');



//Route for uploading chunks of files.
//Route::post('/upload-chunks', 'App\Http\Controllers\FileUploadController@uploadChunks');
//This route was used by Resumable.js
//Since implementing DropSpace Chunker, it is no longer used.

//Route to updating the expiry of a file
Route::post('/update-expiry', 'App\Http\Controllers\FileDownloadController@updateExpiry');
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
//Route to getting a new ShareCode
Route::post('/generate-sharecode', 'App\Http\Controllers\ShareCodeController@generateShareCode');
//Route to accessing a file from ShareCode
Route::get('/sharecode', 'App\Http\Controllers\ShareCodeController@inputScreen');
//Route for looking up a ShareCode
Route::get('/sharecode/{id}', 'App\Http\Controllers\ShareCodeController@findShareCode');
//Route for logging in (POST)
Route::post('/login', 'App\Http\Controllers\LoginController@authenticate')->name('login.post');
//Route for logging out
Route::get('/logout', 'App\Http\Controllers\LoginController@logout')->name('logout');
//Route for settings
Route::get('/settings', 'App\Http\Controllers\LoginController@settings')->name('settings');
//Route for saving settings
Route::post('/update-settings', 'App\Http\Controllers\LoginController@updateSettings')->name('update-settings');
//Route for redeeming an invite
Route::get('/invite/{id}', 'App\Http\Controllers\LoginController@invite')->name('invite');
//Route for using an invite
Route::post('/use-invite', 'App\Http\Controllers\LoginController@useInvite')->name('use-invite');