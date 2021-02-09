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
      $this->channelImagePath = Config::get('filePaths.twillio-channel-images');
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
        
            $user = Sentinel::getUser();  
            $query=TwillioChannel::where('status',1)->select('id', 'friendly_name', 'unique_name');
            return Datatables::eloquent($query)
    
            ->editColumn('delete', function (TwillioChannel $value){
                return '<center><a href="javascript:void(0)" form="noForm" class="blue channel-delete " data-id="'.$value->id.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></><center>'; 
            })
            ->editColumn('chat', function ($value){
                return '<center><a href="javascript:void(0)" form="noForm" class="blue chat-backup " data-id="'.$value->id.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Backup"><i class="fa fa-refresh"></i></a></><center>'; 
            })
            ->make(true);
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
 
  
    public function block(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $member = ChatMemberChannel::find($id);
        if ($member) {
            $member->block = $state;
            $member->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
    public function deleteMember(Request $request)
    {
        $id = $request->id;
    
        $member = ChatMemberChannel::find($id);
        if ($member) {
            $member->status = 0;
            $member->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
    public function deleteChannel(Request $request)
    {
        $id = $request->id;
    
        $channel = TwillioChannel::find($id);
       
       $twillioChannel=$this->twillioController->deleteChannel($channel->sid);
        if ($channel) {
            $channel->status = 0;
            $channel->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
    public function backupChannel(Request $request)
    {
        $id = $request->id;
    
        $channel = TwillioChannel::find($id);
       
       $this->twillioController->getAllChat($channel->sid,$channel->id);
       return response()->json(['status' => 'success']);
    }

    public function memberListView()
    {
        return view('TwiloManage::member-list');
    }
    public function memberListJson()
    {
       
        $user = Sentinel::getUser();
        $query=ChatMemberChannel::with(['getMemeber.getMemeberDetails'])->where('status',1)->select('chat_member_channel.*');

        return Datatables::eloquent($query)

        // ->editColumn('checklist', function (Episode $value){
            
        //         return '<center><input  type="checkbox" class="form-check-input episode-check"  value="'.$value->episodeId.'"><center>';
           
        // })
        ->editColumn('status', function (ChatMemberChannel $value){
            if($value->status==1){
                return '<center><a href="javascript:void(0)" form="noForm" class="blue episode-status-toggle " data-id="'.$value->id.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-trash"></i></a></><center>';
            }else{
                return '<center><a href="javascript:void(0)" form="noForm" class="blue episode-status-toggle " data-id="' . $value->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-trash"></i></a></><center>';
            }
            return $value->status == 1 ? 'Activated' : 'Inactivated';
        })
        ->addColumn('ViewerId', function (ChatMemberChannel $value) {

            return  $value->getMemeber ? $value->getMemeber->viewerId : "-";

        })
        ->addColumn('ViewerName', function (ChatMemberChannel $value) {

            return  $value->getMemeber->getMemeberDetails ? $value->getMemeber->getMemeberDetails->Name : "-";

        })
        ->addColumn('ViewerMobileNo', function (ChatMemberChannel $value) {

            return  $value->getMemeber->getMemeberDetails ? $value->getMemeber->getMemeberDetails->MobileNumber : "-";

        })
        ->editColumn('delete', function ($value){
            return '<center><a href="javascript:void(0)" form="noForm" class="blue member-delete " data-id="'.$value->id.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></><center>'; 
        })
        ->addColumn('block', function ($value) {
            if ($value->block == 1) {
                return '<center>
                    <a href="javascript:void(0)" form="noForm" class="blue member-status-toggle " data-id="' . $value->id . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="un-block">
                    <i class="fa fa-toggle-on"></i>
                    </a>
                    </center>';
            } else {
                return '<center>
                    <a href="javascript:void(0)" form="noForm" class="blue member-status-toggle " data-id="' . $value->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="blocked">
                    <i class="fa fa-toggle-off"></i>
                    </a>
                    </center>';
            }
        })

        // ->addColumn('edit', function (Episode $value) use ($user){
        //     if($user->hasAnyAccess(['episode.edit', 'admin'])){
        //         $url =url('episode/'.$value->episodeId.'/edit');
        //         return '<center><a href="'.$url.'" class="blue"  data-toggle="tooltip" data-placement="top" title="View/ Edit Episode"><i class="fa fa-pencil"></i></a></center>';
        //     }else{
        //         return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
        //     }
                
        // })
        ->make(true);
       
    }
 
   
  

}
