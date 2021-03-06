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
use Config;
use Illuminate\Support\Facades\Validator;
use NotificationManage\Models\UserGroup;
use NotificationManage\Models\UserGroupsViewer;
use NotificationManage\Models\FcmNotification;
use NotificationManage\Models\Viewers;
use NotificationManage\Models\Program;
use NotificationManage\Models\Episode;
use GuzzleHttp\Client as GuzzleClient;
use App\Http\Controllers\ImageController;
use KikiServiceManage\Models\KikiService;




class NotificationController extends Controller
{
    private $client;
    private $imagePath;
    private  $URL;
    private $notificationImagePath;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->URL =env('APP_URL');
        $this->notificationImagePath = Config::get('filePaths.notification-images');
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
    public function searchservice(Request $request){

        $search = $request->get('term');
        $service = [];
        if($search){
            $service =  KikiService::where('name', 'like', '%' . $search . '%')->where('status', 1)->limit(20)->orderBy('name', 'asc')->get();
        }

        return $service;


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
       

        $URL=env('NOTIFICATION_URL');
        $ImageUpLoadPath=env('IMAGE_UPLOAD_PATH');
        $folderName=env('FOLDER_NAME');
       
        // return $request['content_type'];     
            //  return $request->all();
            // return $filename = $_FILES['file']['name'];
        // return $request['section'];
       //   $si_filename =$request->file('si-image');
          $image_filename =$request->file('image_upload');    
        //    return $image_filename;
            $usergrp= $request['user_group'];
            $contentType= $request['content_type'];
            $sub_type= $request['sub_type'];
            $contentid= $request['content_id'];
            $typeDesc=$request['section'];
            $type=null;
            $notifydate=$request['notification_time'];
            
            $en_image_path=null;
            $si_image_path=null;
            $ta_image_path=null;     

            $fcm_notification = FcmNotification::create([
                'user_group' => $request['user_group'],
                'section' => $request['section'],
                'content_type' =>  $request['content_type'],
                'content_id' =>  $request['content_id'],
                'notification_time' => $request['notification_time'],
                'all_audiance' => 1,
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
            $image=null;
            $description=null;
            $title=null;

             if($request->hasFile('image_upload')){
                 
                $aImage = $image_filename;
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'notification-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $imageup->upload('notification', $aImage, $fileName,$fcm_notification->id );               
             
             }
            
             
             if($request['english_title']!="null"){

                $en_image_path= $fileName;
                $description=$request['english_description'];
                $title=$request['english_title'];
                $fcm_notification->update([
                    'english_image' => $en_image_path
                ]);
            }
            if($request['sinhala_title']!="null"){

                // return $request['sinhala_title'];

                $si_image_path= $fileName;
                $description=$request['sinhala_description'];
                $title=$request['sinhala_title'];
                $fcm_notification->update([
                    'sinhala_image' => $si_image_path
                ]);
            }
            if($request['tamil_title']!="null"){

                // return $request['tamil_title'];

                $ta_image_path= $fileName;
                $description=$request['tamil_description'];
                $title=$request['tamil_title'];
                $fcm_notification->update([
                    'tamil_image' => $ta_image_path
                ]);
            }
            //----START DEVICE ID
            // $sql = "SELECT viewer_id FROM user_groups_viewers where user_group_id='$usergrp'";
            // $viewer_ids = DB::select($sql);
            // Log::info($viewer_ids);

            // $devices =array();
            // foreach ($viewer_ids as $viewer_id_ob) {
            //     $viewer_id = $viewer_id_ob->viewer_id;
            //     $sql = "SELECT DeviceID FROM viewers where ViewerID='$viewer_id'";
            //     $device_ids = DB::select($sql);
            //     Log::info($device_ids);
            //     foreach ($device_ids as $device_id_ob) {
            //         Log::info($device_id_ob->DeviceID);
            //         array_push($devices, $device_id_ob->DeviceID);
            //     }
            // }
            // Log::info($devices);
               //----END DEVICE ID

            

            if($typeDesc === "MUSIC"){
                $type="1";
                if($sub_type =="playlist"){
                    $contentType="playlistid";
                }else if($sub_type =="song"){
                    $contentType="songid";
                }else if($sub_type =="album"){
                    $contentType="albumid";
                }
                // return $sub_type;
               
                // $contentType="songid";
            }
            if($typeDesc === "VIDEO"){
                $type="0";
                if($contentid==''){
                    $contentid=$contentType;
                    $contentType="programid";
                }else{
                    $contentType="episodeid";
                }
                
            }
            if($typeDesc === "GENERAL"){
                $type="2";
                $contentType="";
            }
            if($typeDesc === "SERVICE"){
                $type="2";
                $contentType="serviceid";
            }
            
            $showimage = [];
            $image_config = [];
           
            if ($fileName) {
                array_push($showimage, Config('constants.bucket.url') .Config('filePaths.front.notification') .$fileName );
              
            }
            $first=$showimage[0];
             //return $first;

            //----------------------GET DEVICE TOKENS-------------------------------------------
            
// return $contentType."ddd";
            // //Get All Viwers Devices ID
            if($request['all_viewers'] =='yes'){
                Viewers::groupBy('DeviceID')->chunk(200, function ($viewers) use ($title, $description,$first,$type,$contentType,$contentid,$notifydate,$URL) {
                    $devices_chunk=array();
                    foreach ($viewers as $viewer) {
                        if(isset($viewer->DeviceID) && $viewer->DeviceID !=null && $viewer->DeviceID !=''){
                            array_push($devices_chunk, $viewer->DeviceID);
                        }
                    }
                    $finel_array_chunk=array(
                        "deviceid" =>$devices_chunk,
                        "title"  =>$title,
                        "body" => $description,
                        "image_url" => $first,
                        "type" => $type,
                        "content_type" => $contentType,
                        "content_id" =>  $contentid,
                        "date_time" =>$notifydate
                    );

                    $response =  $this->client->request('POST', $URL , [
                        'headers' => [     
                            'Accept' => 'application/json',
                        ], 'json' => $finel_array_chunk,
                    ]);
                    $contents = $response->getBody();

                });
                return "Sucessfully Bulk Updated";
                //Induvigual Chunk
                // return $viwer_details_chunk=Viewers::distinct()->limit(4)->chunk(300)->unique('DeviceID');;;
                // return $viwer_details_chunk=Viewers::distinct()->get(['DeviceID']);
                // foreach ($viwer_details_chunk as $viwer_details_slot){
                //     foreach ($viwer_details_slot as $viwer_detail) {
                //         if(isset($viwer_detail->DeviceID)){
                //             array_push($devices, $viwer_detail->DeviceID);
                //         }
                //     }
                // }
            }else{

                $devices =array();
                //Get Selected Group viwers Devices
                $viwer_group_details=UserGroupsViewer::with(['getViewer'])->where('user_group_id',$usergrp)->get();
                foreach ($viwer_group_details as $viwer_group_detail) {
                    if(isset($viwer_group_detail->getViewer->DeviceID) && $viwer_group_detail->getViewer->DeviceID !=null &&  $viwer_group_detail->getViewer->DeviceID !=''){
                        array_push($devices, $viwer_group_detail->getViewer->DeviceID);
                    }
                }
                if (empty($devices)) {
                    return "No devices Found";
                }else{
                    $finel_array=array(
                        "deviceid" =>$devices,
                        "title"  =>$title,
                        "body" => $description,
                        "image_url" => $first,
                        "type" => $type,
                        "content_type" => $contentType,
                        "content_id" =>  $contentid,
                        "date_time" =>$notifydate
                    );
    
                    $response =  $this->client->request('POST', $URL , [
                        'headers' => [     
                            'Accept' => 'application/json',
                        ], 'json' => $finel_array,
                    ]);
                    $contents = $response->getBody();
                    return $contents = json_decode($contents);
                }
                
            }
     
            
            //  Log::info($contents);

            //  return view('NotificationManage::notification-add');
            // return view('notification-add');
    
          
    }

}
