<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShareCode;
use App\Models\File;

class ShareCodeController extends Controller
{
    //
    public function inputScreen()
    {
        return view('sharecode-search');
    }

    public function findShareCode($id)
    {
        $sharecode = ShareCode::where('code', strtoupper($id))->first();
        if ($sharecode) {
            //Check if the sharecode is expired
            if ($sharecode->expiry_date < date('Y-m-d H:i:s')) {
                return view('download-error')->with('error', 'This ShareCode has expired.');
            }
            if ($sharecode->used == 1) {
                return view('download-error')->with('error', 'This ShareCode has already been used.');
            }
            $file = File::where('file_identifier', $sharecode->file_identifier)->first();
            //return response()->json(['file' => $file, 'sharecode' => $sharecode]);
            if($file->is_protected == true) {
                $sharecode->used = 1;
                $sharecode->save();
                return redirect('/download/'.$file->file_identifier.'?hash=' . $file->password);
            }else{
                $sharecode->used = 1;
                $sharecode->save();
                return redirect('/download/'.$file->file_identifier);
            }
        }else{
            return view('download-error')->with('error', 'Could not find ShareCode.');
        }
    }

    public function generateShareCode(Request $request)
    {
        //
        //Find file from identifier
        $file = File::where('file_identifier', $request->file_id)->first();
        if($file == null)
        {
            return response()->json(['error' => 'File not found'], 404);
        }
        if($file->is_protected == true)
        {
            if($request->hash != $file->password){
                return response()->json(['error' => 'Wrong password'], 401);
            }
            else{
                $shareCode = new ShareCode();
                $shareCode->file_identifier = $request->file_id;
                $shareCode->used = false;
                $shareCode->expiry_date = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                //Generate random code that can contain letters and at least two numbers
                $shareCode->code = substr(str_shuffle(str_repeat('123456789abcdefghjklmnpqrstuvwxyz', mt_rand(1, 10))), 1, 6);
                $shareCode->save();
                return response()->json(['code' => $shareCode->code, 'expiry_date' => $shareCode->expiry_date]);
            }
        } else{
            $shareCode = new ShareCode();
            $shareCode->file_identifier = $request->file_id;
            $shareCode->used = false;
            $shareCode->expiry_date = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            //Generate random code that can contain letters and at least two numbers, length should be 6
            $shareCode->code = strtoupper(substr(str_shuffle(str_repeat('123456789abcdefghjklmnpqrstuvwxyz', mt_rand(1, 10))), 1, 6));
            $shareCode->save();
            return response()->json(['code' => $shareCode->code, 'expiry_date' => $shareCode->expiry_date]);
        }
    }
}
