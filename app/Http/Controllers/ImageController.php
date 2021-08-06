<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(ImageUploadRequest $request){

        $file = $request->image;
        $filename = Str::random(10);
        $url = url(Storage::putFileAs('images', $file, "{$filename}.{$file->extension()}"));

        return [
            'url' => $url
        ];
    }
}
