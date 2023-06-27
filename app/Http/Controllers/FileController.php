<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    public function index() {
        $files = File::all();
        return response()->json($files, 200);
    }

    public function uploadFile(Request $request) {
        $validator = \Validator::make($request->all(),
        [
            'title' => 'required|unique:files|min:6|max:25',
            'file' => 'required|file'
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $file = new File();
        $fileName = $request->title . '-'.$request->file->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('uploads',$fileName,'public');
        $file->title = $fileName;
        $file->file_path= '/storage/app/public/' . $filePath;
        $file->save();
        return response()->json("File has been uploaded", 200);
    }
}
