<?php

namespace App\Http\Controllers;

class TrackController extends Controller
{

    public function main_path()
    {
        // return url('') . '/core/storage/';
        return Config('constants.bucket.url');

    }

    public function upload_google_bucket($file,$file_name)
    {
        $disk = Storage::disk('gcs');
        $path='uploads/songs';
        $homepage = file_get_contents($file);
        $disk->put($path.'/'.$file_name, $homepage);

    }

}
