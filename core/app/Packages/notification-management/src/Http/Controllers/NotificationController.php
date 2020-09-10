<?php


namespace NotificationManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use LyricistManage\Models\Lyricist;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Log;
use DB;
use Datatables;
use Response;
use Session;
use Sentinel;
use Illuminate\Support\Facades\Validator;
use NotificationManage\Models\UserGroup;
use NotificationManage\Models\UserGroupsViewer;
use NotificationManage\Models\FcmNotification;
use NotificationManage\Models\Program;
use NotificationManage\Models\Episode;
use GuzzleHttp\Client as GuzzleClient;
use App\Http\Controllers\ImageController;




class NotificationController extends Controller
{
    private $client;
    private $imagePath;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function listView()
    {
        return view('NotificationManage::notification-list');
    }

    public function jsonList(Request $request)
    {

        Log::info('called');

        try {

            $searchField = $request->get('field_name');
            $searchParam = $request->get('search_param');

            //return $searchParam.'---'.$searchField;

            $dataQuery = FcmNotification::select([
                'id', 'user_group', 'section', 'content_type', 'content_id', 'notification_time',
                'language', 'sinhala_title', 'english_title', 'tamil_title', 'status'
            ])->with([
                'userGroup']);

        
            
            if ($searchField and $searchParam) {
                switch ($searchField) {
                    case 'section':
                        $dataQuery->where('section', 'like', '%' . $searchParam . '%');
                        break;
                    case 'content_type':
                        $dataQuery->where('content_type', 'like', '%' . $searchParam . '%');
                        break;
                    case 'content_id':
                        $dataQuery->where('content_id', 'like', '%' . $searchParam . '%');
                        break;
                    case 'language':
                        $dataQuery->where('language', 'like', '%' . $searchParam . '%');
                        break;
                    case 'title':
                        $dataQuery->where('sinhala_title', 'like', '%' . $searchParam . '%') 
                        ->orWhere('english_title', 'like', '%' . $searchParam . '%')
                        ->orWhere('tamil_title', 'like', '%' . $searchParam . '%');
                        break;
                    default:
                }
            }

            $dataTables = Datatables::eloquent($dataQuery)
                ->addColumn('title', function ($value) {
                    $title = $value->english_title ? $value->english_title : '';
                    $title .= $value->sinhala_title ?  "<br>" . $value->sinhala_title : '';
                    $title .= $value->tamil_title ? "<br>" . $value->tamil_title : '';
                    return $title ;
                })
                ->addColumn('user_group', function ($value) {
                    return $value->userGroup ? $value->userGroup->name : '';
                })
                ->addColumn('action', function ($value) {
                    if ($value->status == 1) {
                        return '<center>
                            <a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->id . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate">
                            <i class="fa fa-toggle-on"></i>
                            </a>
                            </center>';
                    } else {
                        return '<center>
                            <a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate">
                            <i class="fa fa-toggle-off"></i>
                            </a>
                            </center>';
                    }
                })
                ->addColumn('edit', function ($value) {
                    return '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('admin/song/step-1/' . $value->songId) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Song"><i class="fa fa-pencil"></i></a></center>';
                });

            $dataTables->smart(false);

            Log::info('setp 1');


            //->toJson();
            return $dataTables->make(true);
        } catch (Exception $exception) {
            Log::error(" User Group list view| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return $exception->getMessage();
        }        
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $notification = FcmNotification::where('id', $id)->first();
        if ($notification) {
            $notification->status = $state;
            $notification->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    public function addView(Request $request)
    {
        return view('NotificationManage::notification-add');
    }


    public function searchprogram(Request $request){

        $search = $request->get('term');
        $program = [];
        if($search){
            $program =  Program::where('programName', 'like', '%' . $search . '%')->where('status', 1)->limit(20)->orderBy('programName', 'asc')->get();
        }

        return $program;

    }

    public function searchepisode(Request $request){

        $search = $request->get('term');
        $programId = $request->get('programId');
        $episode = [];
        if($search){
            $episode =  Episode::where('episodeName', 'like', '%' . $search . '%')->where('status', 1)->where('programId', $programId)->limit(20)->orderBy('episodeName', 'asc')->get();
        }

        return $episode;

    }
    

    public function searchuser(Request $request){

        $search = $request->get('term');
        $user = [];
        if($search){
            $user =  UserGroup::where('name', 'like', '%' . $search . '%')->where('status', 1)->limit(20)->orderBy('name', 'asc')->get();
        }

        return $user;

    }

    public function addNotification(Request $request){

        // return $request['section'];
          $si_filename =$request->file('si-image');
          $si_filename->getClientOriginalExtension();;
          $en_filename = $_FILES['en-image']['name'];
          $ta_filename = $_FILES['ta-image']['name'];
          $aImage = $request->file('image');

            Log::info('php');
            $usergrp= $request['user_group'];
            Log::info($usergrp);
            $contentType= $request['content_type'];
            $contentid= $request['content_id'];
            $type=$request['section'];
            $notifydate=$request['notification_time'];


            $fcm_notification = FcmNotification::create([
                'user_group' => $request['user_group'],
                'section' => $request['section'],
                'content_type' =>  $request['content_type'],
                'content_id' =>  $request['content_id'],
                'notification_time' => $request['notification_time'],
                'all_audiance' => $request['all_audiance'],
                'language' =>  $request['language'],
                'english_title' =>  $request['english_title'],
                'english_description' =>  $request['english_description'],              
                'sinhala_title' =>  $request['sinhala_title'],
                'sinhala_description' =>  $request['sinhala_description'],               
                'tamil_title' =>  $request['tamil_title'],
                'tamil_description' =>  $request['tamil_description'],
                'status' => $request['status'],

            ]);


            $imageup = new ImageController();
            $ext=null;
            $fileName=null;
            $path=null;

             if($request->hasFile('si-image')){
                $aImage = $si_filename;
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'notification-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $imageup->upload('notification', $aImage, $fileName,$fcm_notification->id );

                $fcm_notification->update([
                    'english_image' => $fileName
                ]);
             }
             if($request->hasFile('en-image')){
                $aImage = $si_filename;
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'notification-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $imageup->upload('notification', $aImage, $fileName,$fcm_notification->id );

                $fcm_notification->update([
                    'english_image' => $fileName
                ]);
             }
             if($request->hasFile('en-image')){
                $aImage = $si_filename;
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'notification-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $imageup->upload('notification', $aImage, $fileName,$fcm_notification->id );

                $fcm_notification->update([
                    'english_image' => $fileName
                ]);
             }

            
            $sql = "SELECT viewer_id FROM susila_db.user_groups_viewers where user_group_id='$usergrp'";
            $viewer_ids = DB::select($sql);
            Log::info($viewer_ids);
            // //get viewer ids to arry
            // //want to get viewer table -> devise id to arry
            $devices =array();
            foreach ($viewer_ids as $viewer_id_ob) {
                $viewer_id = $viewer_id_ob->viewer_id;
                $sql = "SELECT DeviceID FROM susila_db.viewers where ViewerID='$viewer_id'";
                $device_ids = DB::select($sql);
                Log::info($device_ids);
                foreach ($device_ids as $device_id_ob) {
                    Log::info($device_id_ob->DeviceID);
                    
                    array_push($devices, $device_id_ob->DeviceID);
                }
            }
            Log::info($devices);
            $image=null;
            $description=null;
            $title=null;
            if($request['english_image']!=null){
                $image=$request['english_image'];
                $description=$request['english_description'];
                $title=$request['english_title'];
            }
            if($request['sinhala_image']!=null){
                $image=$request['sinhala_image'];
                $description=$request['sinhala_description'];
                $title=$request['sinhala_title'];
            }
            if($request['tamil_image']!=null){
                $image=$request['tamil_image'];
                $description=$request['tamil_description'];
                $title=$request['tamil_title'];
            }


            $finel_array=array(
                "deviceid" =>$devices,
                "title"  =>$title,
                "image_url" =>$image,
                "type" => $type,
                "body" => $description,
                "content_type" => $contentType,
	            "content_id" =>  $contentid,
                "date_time" =>$notifydate
            );
            

            $response =  $this->client->request('POST', 'http://localhost:3000/fcm/v1/message', [
                'headers' => [
                   
                    'Accept' => 'application/json',
                ], 'json' => $finel_array,
            ]);
            $contents = $response->getBody();
            $contents = json_decode($contents);
            return $contents;
    


          
    }

}
