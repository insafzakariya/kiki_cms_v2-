<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigRequest;
use App\Models\Config;
use Illuminate\Http\Request;
use Validator;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('config');
    }

    /**
     * Show the form for updating resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'days_count' => 'numeric|min:0',
            'version' => 'regex:/^[a-zA-Z0-9_.\- ]+$/',
            'logo' => 'image|mimes:jpg,png',
            'watermark' => 'image|mimes:jpg,png',
            'contact' => 'regex:/^[0-9- ]+$/',
            'email' => 'email',
            'facebook' => 'url',
            'instagram' => 'url',
            'youtube' => 'url',
        ];

        $msgs = [
            'version.regex' => 'Version should only be contating letters, numbers, spaces, dots and hyphens',
            'contact.regex' => 'Contact number should only be contating numbers, spaces, and hyphens',
        ];

        $validator = Validator::make($request->all(), $rules, $msgs);

        if ($validator->fails()) {
            return redirect()->back()->with(["errors" => $validator->errors()]);
        }

        $image = new ImageController();

        if($request->has('id')){
            if($request->hasFile('logo')){
                $oldlogo = storage_path('/') . Config::find($request->input('id'))->logo;
                if(is_file($oldlogo)){
                    unlink($oldlogo);
                }
                $file = $request->file('logo');
                $extn =$file->getClientOriginalExtension();
                $project_fileName = 'logo-' .date('YmdHis') . '.' . $extn;
                $path=$image->upload_logo('logo', $file, $project_fileName,$request->input('id'));

                Config::find($request->input('id'))->update([
                    'logo' => $path . '/' . $project_fileName
                ]);
            }
            
            if($request->hasFile('watermark')){
                $oldwatermark = storage_path('/') . Config::find($request->input('id'))->watermark;
                if(is_file($oldwatermark)){
                    unlink($oldwatermark);
                }
                $file = $request->file('watermark');
                $path=$image->upload_logo('watermark', $file, 'sambole_watermark.png',$request->input('id'));

                Config::find($request->input('id'))->update([
                    'watermark' => $path . '/' . 'sambole_watermark.png'
                ]);
            }
            // return trim($request->input('instagram'));
            Config::find($request->input('id'))->update([
                'days_count' => $request->input('days_count'),
                'version' => trim($request->input('version')),
                'contact' => trim($request->input('contact')),
                'email' => trim($request->input('email')),
                'facebook' => trim($request->input('facebook')),
                'instagram' => trim($request->input('instagram')),
                'youtube' => trim($request->input('youtube')),
                'logo_width' => trim($request->input('logo_width')),
                'logo_height' => trim($request->input('logo_height')),
                'wt_height' => trim($request->input('wt_height')),
                'wt_width' => trim($request->input('wt_width'))
            ]);
        }

        $notification = array(
            'success' => true,
            'success.message' => 'Config Successfully updated',
            'success.title' => 'Well Done!'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Return Config data
     *
     * @return \Illuminate\Http\Response
     */
    public function getConfig()
    {
        return Config::find(1);
    }
}