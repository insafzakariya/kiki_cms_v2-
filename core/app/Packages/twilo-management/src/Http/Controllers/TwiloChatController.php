<?php

namespace TwiloManage\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TwilioController;
use Carbon\Carbon;
use Config;
use Datatables;
use Exception;
use File;
use Illuminate\Http\Request;
use Log;
use Response;
use Session;
use TwiloManage\Models\TwillioChannel;
use TwiloManage\Models\Viewer;
use TwiloManage\Models\ChatMember;
use TwiloManage\Models\ChatMemberChannel;
use ProgrammeManage\Models\Programme;

use Sentinel;


class TwiloChatController extends Controller
{
    private $channelImagePath ;

    /**
     * @var ImageController
     */
    private $imageController;
    private $twillioController;

    public function __construct()
    {
      $this->channelImagePath = Config::get('filePaths.channel-images');
      $this->imageController = new ImageController();   
      $this->twillioController = new TwilioController();   
    }

    public function createChannelView()
    {
        
        return view('TwiloManage::add');
    }
    
    public function channelStore(Request $request)
    {

       
        // return $request->all();
        $accountsid = getenv("TWILIO_ACCOUNT_SID");
        $twillioChannel=$this->twillioController->createChannel($request->unique_name,$request->friendly_name);
        $channel=TwillioChannel::create([
            'account_sid'=>$accountsid,
            'create_date'=>Date('Y-m-d H:i:s'),
            'created_by'=>'',
            'friendly_name'=>$request->friendly_name,
            'image_path'=>'',
            'service_sid'=>$twillioChannel->serviceSid,
            'sid'=>$twillioChannel->sid,
            'status'=>1,
            'unique_name'=>$request->unique_name,
            'updated_date'=>Date('Y-m-d H:i:s'),
           
        ]);

        if($channel){
            if($request->hasFile('channel_image')) {
                $aImage = $request->file('channel_image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'twillio-channel-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->Upload($this->channelImagePath, $aImage, $fileName, "-");
                $channel->image_path = $fileName;
            }
            
            $channel->save();
             

            return redirect('twillio/add')->with(['success' => true,
            'success.message' => 'Channel Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('twillio/add')->with([
                'error' => true,
                'error.message'=> 'Error adding new Channel. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
       
       
    }
   
   
    public function listView()
    {
        return view('TwiloManage::list');
    }
    public function listJson()
    {
        // try {
            $user = Sentinel::getUser();
            return Datatables::usingCollection(
                TwillioChannel::where('status','=',1)->select('id', 'friendly_name', 'unique_name')->get()
            )
                
                ->make(true);
        // }catch (\Throwable $exception){
        //     $exceptionId = rand(0, 99999999);
        //     Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
        //     return Datatables::of(collect())->make(true);
        // }
    }
    public function createChannelMemberView()
    {
        $twillioChannel=TwillioChannel::where('status',1)->get();
        return view('TwiloManage::new_member')->with(
            ['channels'=>$twillioChannel  ]
        );;
    }
    public function ChannelMemberStore(Request $request)
    {
        $members=$request->member;
        foreach ($members as $key => $value) {
            $fulltext=explode("_",$value);
            // return $value .'='.$request->c_picker[$key];
            $chatMember=ChatMember::create([
                'create_date'=>Date('Y-m-d H:i:s'),
                'created_by'=>'',
                'identity'=>$fulltext[0],
                'image_path'=>'',
                'name'=>$fulltext[1],
                'status'=>1,
                'updated_date'=>Date('Y-m-d H:i:s'),
                'chatRoleEntity_id'=>2,
                'viewerId'=>$fulltext[0],
                'colour'=>$request->c_picker[$key],
                'active'=>0,
            ]);
            if($chatMember){
                ChatMemberChannel::create([
                    'block'=>0,
                    'status'=>1,
                    'chatChannelEntity_id'=>$request->channel,
                    'chatMemberEntity_id'=>$chatMember->id,
                ]);
            }
            

        }
        return redirect('twillio/member/add')->with(['success' => true,
            'success.message' => 'Member Added successfully!',
            'success.title' => 'Well Done!']);
       
    }

    public function searchViwer(Request $request)
    {
        $search = $request->get('q');
        $program = [];
        if($search){
            // $program =  Programme::where('programName', 'like', '%' . $search . '%')->where('status', 1)->limit(10)->orderBy('programName', 'asc')->get();
            $program =  Viewer::where('ViewerID', 'like', '%' . $search . '%')->limit(10)->orderBy('ViewerID', 'asc')->get();
        }

        return $program;
    }
 
  
    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $channel = Channel::find($id);
        if ($channel) {
            $channel->status = $state;
            $channel->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
    public function deleteChannel(Request $request)
    {
        $id = $request->id;
    
        $channel = Channel::find($id);
        if ($channel) {
            $channel->status = 2;
            $channel->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
 
   
  

}
