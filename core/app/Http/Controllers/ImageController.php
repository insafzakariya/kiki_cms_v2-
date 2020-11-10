<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

/*USAGE LIBRARY*/
use File;
use Illuminate\Http\Request;
use Image;
use Storage;
use View;

// use Intervention\Image\ImageManager;

class ImageController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Image Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /*
    $folder => folder name
    $file => upload file
    $file_name =>name of save file
     */
    public function main_path()
    {
        // return url('') . '/core/storage/';
        return Config('constants.bucket.url');

    }
    public function Upload($folder, $file, $file_name, $id)
    {
        $upload_path = env('IMAGE_UPLOAD_PATH', '');

        $disk = Storage::disk('gcs');

        $path = $upload_path . '/' . $folder;

        /*start*/
        // $watermark = Image::make(storage_path('uploads/sambole_watermark.png'));
        $img_orginel = Image::make($file);
        $img_orginel = Image::make($file)->exif();

        $degree = 0;
        if (isset($img_orginel['Orientation'])) {
            $degree = $this->orientation_to_degree($img_orginel['Orientation']);
        }

        $img_orginel = Image::make($file)->rotate($degree);

        $disk->put($path . '/' . $file_name, $img_orginel->stream());
        return $path;

    }
    public function UploadVideo($folder, $file, $file_name, $id = null)
    {
        $upload_path = env('IMAGE_UPLOAD_PATH', '');
        $disk = Storage::disk('gcs');

        // $path = $upload_path . '/' . $folder . '/' . $id;
        $path = $upload_path . '/' . $folder;
        $audionFile = file_get_contents($file);

        $disk->put($path . '/' . $file_name, $audionFile);
        return $path;

    }

    public function UploadAudio($folder, $file, $file_name, $id = null)
    {
        $upload_path = env('IMAGE_UPLOAD_PATH', '');
        $disk = Storage::disk('gcs2');

        // $path = $upload_path . '/' . $folder . '/' . $id;
        $path = $upload_path . '/' . $folder;
        $audionFile = file_get_contents($file);

        $disk->put($path . '/' . $file_name, $audionFile);
        return $path;

    }
    public function UploadSmilWithContent($folder, $contents, $file_name, $id = null)
    {
        $upload_path = env('IMAGE_UPLOAD_PATH', '');
        // $upload_path = 'test';
        $disk = Storage::disk('gcs');

        // $path = $upload_path . '/' . $folder . '/' . $id;
        $path = $upload_path . '/' . $folder;
        // $smilFile=file_put_contents($id.".smil",$contents);
        // $slimFile = file_get_contents('newfile.smil');
       
        $disk->put($path . '/' . $file_name, $contents);
        return $path;

    }
    public function UploadSmil($folder, $file, $file_name, $id = null)
    {
        $upload_path = env('SMIL_UPLOAD_PATH', '');
        // $upload_path = '';
        // $upload_path = 'test';
        $disk = Storage::disk('gcs3');

        // $path = $upload_path . '/' . $folder . '/' . $id;
        $path = $upload_path . '/' . $folder;
        $slimFile = file_get_contents('newfile.smil');

        $disk->put($path . '/' . $file_name, $slimFile);
        return $path;

    }
    public function Upload_slider($folder, $file, $file_name)
    {
        $upload_path = env('IMAGE_UPLOAD_PATH', '');

        $disk = Storage::disk('gcs');
        $path = $upload_path . '/' . $folder . '/';
        $slider_image = Image::make($file);
        $disk->put($path . '/' . $file_name, $slider_image->stream());
        return $path;

    }

    public function upload_logo($folder, $file, $file_name, $id)
    {
        $upload_path = env('IMAGE_UPLOAD_PATH', '');
        $disk = Storage::disk('gcs');
        $path = $upload_path . '/' . $folder . '/' . $id;
        /*start*/

        $img_orginel = Image::make($file);

        $disk->put($path . '/' . $file_name, $img_orginel->stream());
        return $path;

    }
    public function read()
    {
        # code...
    }

    public function upload_google_bucket_form()
    {
        return view('front.test');

        $disk = Storage::disk('gcs');
        $disk->put('$file_name', $file);
        // $exists = $disk->exists('cloudlogin.txt');
        // print_r($exists);

        // create a file
        // return $time = $disk->lastModified('ppl.sql');
    }
    public function upload_google_bucket_post_form(Request $request)
    {

        $project_files = $request->file('product_image');
        if ($project_files) {
            $i = 0;
            foreach ($project_files as $key => $project_file) {
                if (File::exists($project_file)) {
                    $file = $project_file;

                    $extn = $file->getClientOriginalExtension();
                    $project_fileName = 'product-' . date('YmdHis') . '-' . $i . '.' . $extn;
                    /*IMAGE UPLOAD*/
                    /*
                    $path=$image->upload('FOLDERNAME',FILE,FILE NAME);
                     */
                    $this->upload_google_bucket('product', $file, $project_fileName);

                }
            }
        }

        // $exists = $disk->exists('cloudlogin.txt');
        // print_r($exists);

        // create a file
        // return $time = $disk->lastModified('ppl.sql');
    }
    public function get_image_bucket()
    {
        return $this->get_google_bucket('uploads/images/product', 'product-20180719120734-0.jpg');

    }

    public function upload_google_bucket($folder, $file, $file_name)
    {
        $disk = Storage::disk('gcs');
        $path = 'uploads/images/' . $folder;
        $homepage = file_get_contents($file);
        $disk->put($path . '/' . $file_name, $homepage);
        return $path;

    }
    public function get_google_bucket($folder, $file_name)
    {
        $disk = Storage::disk('gcs');
        $path = 'uploads/images/' . $folder;
        return $url = $disk->url($path . '/' . $file_name);
    }
    public function orientation_to_degree($oriention_no)
    {
        if ($oriention_no == 3) {
            return 180;
        } else if ($oriention_no == 4) {
            return -180;
        } else if ($oriention_no == 5) {
            return 90;
        } else if ($oriention_no == 6) {
            return -90;
        } else if ($oriention_no == 7) {
            return 270;
        } else if ($oriention_no == 8) {
            return -270;
        } else {
            return 0;
        }

    }

}
