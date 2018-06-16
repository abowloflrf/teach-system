<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $filename = auth()->user()->name . '-' . auth()->user()->id . '-' . $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs(
            'upload',
            $filename
        );
        return array(
            'status' => 'OK',
            'path' => $path
        );
    }

    public function download(Request $request)
    {
        if (Storage::exists($request->key))
            return Storage::download($request->key);
        else
            return response()->json(['msg' => 'File not found'], 404);
    }
}
