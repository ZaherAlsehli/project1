<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pdf;
use App\Models\Lesson;
use Illuminate\Http\Request;
use ErlandMuchasaj\LaravelFileUploader\FileUploader;

class PdfController extends Controller
{

    public function uploadFile(Request $request)
    {
        $max_size = (int) ini_get('upload_max_filesize') * 1000;

        $extensions = implode(',', FileUploader::images());

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf', // التحقق من أن الملف هو PDF
                'max:' . $max_size,
            ]
        ]);

        $file = $request->file('file');

        $response = FileUploader::store($file);

        return response()->json([
            'success' => 'File has been uploaded.',
            'file' => $response
        ]);
    }



}