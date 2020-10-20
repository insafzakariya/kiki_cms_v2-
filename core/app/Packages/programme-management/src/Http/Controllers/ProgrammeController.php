<?php

namespace ProgrammeManage\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\Policy;
use App\Models\MasterImage;
use App\Models\ContentPolicy;
use Carbon\Carbon;
use Config;
use Datatables;
use Exception;
use File;
use Illuminate\Http\Request;
use Log;
use Response;
use Session;
use ChannelManage\Models\Channel;
use ProgrammeManage\Models\Programme;
use ProgrammeManage\Models\ProgrammeChannel;
use Sentinel;


class ProgrammeController extends Controller
{
    private $programmeImagePath ;

    /**
     * @var ImageController
     */
    private $imageController;

    public function __construct()
    {
      $this->programmeImagePath = Config::get('filePaths.programme-images');
      $this->imageController = new ImageController();   
    }

    public function index()
    {
        $programmeContentPolicies=Policy::getProgrammeContentPolicies();
        $advertismentPolicies=Policy::getAdvertisementPolicies();
        $channels=Channel::where('status',1)->get();
        return view('ProgrammeManage::add')
        ->with([
            'programmeContentPolicies'=>$programmeContentPolicies,
            'advertismentPolicies'=>$advertismentPolicies,
            'channels'=>$channels,
            ]);
    }
 
    public function store(Request $request)
    {

       
        // return $request->all();
        //Kids On Validation
        $kids=0;
        if($request->kids_channel=="on"){
            $kids=1;
        }
        
        $programme=Programme::create([
            'programName'=>$request->programme_name_en,
            'description'=>$request->programme_description_en,
            'advertisementPolicy'=>$request->advertisment_policy,
            'status'=>1,
            'kids'=>$kids,
            'programmeName_si'=>$request->programme_name_si,
            'programmeName_ta'=>$request->programme_name_ta,
            'programmeDesc_si'=>$request->programme_description_si,
            'programmeDesc_ta'=>$request->programme_description_ta,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'subtitles'=>$request->subtitle,
            'likes'=>$request->likes,
            'search_tag'=>json_encode($request->tags),
            'programType'=>$request->programme_type,
        ]);

        if($programme){
            if(isset($request->channels)){
                foreach ($request->channels as $key => $channel) {
                    ProgrammeChannel::create([
                        'programme_id'=>$programme->programId,
                        'channel_id'=>$channel,
                        'status'=>1
                    ]);
                }
            }

            // Insert to Content Policy Table
            if(isset($request->content_policies)){
                foreach ($request->content_policies as $key => $contentpolicy) {
                    ContentPolicy::create([
                        'ContentID'=>$programme->programId,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>3,
                        'Status'=>1,
                        'type'=>null
                    ]);
                }
            }

            //Cover Image Upload
            if($request->hasFile('cover_image')) {
                $cover_images=$request->file('cover_image');
                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'programme-cover-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");
                    MasterImage::create([
                        'parent_type'=>'programme',
                        'parent_id'=>$programme->programId,
                        'image_type'=>'cover_image',
                        'file_name'=>$fileName
                    ]);
                }
                
            }
            //Cover Image Upload
            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');
                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'programme-thumb-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");
                    MasterImage::create([
                        'parent_type'=>'programme',
                        'parent_id'=>$programme->programId,
                        'image_type'=>'thumb_image',
                        'file_name'=>$fileName
                    ]);
                }
                
            }

        return redirect('programme/add')->with(['success' => true,
            'success.message' => 'Programme Created successfully!',
            'success.title' => 'Well Done!']);
           
        }else{
            return redirect('programme/add')->with([
                        'error' => true,
                        'error.message'=> 'Error adding new Programme. Please try again.',
                        'error.title' => 'Oops !!'
                    ]);
        }

        

       
        // if($channel){
        //     if($request->hasFile('channel_image')) {
        //         $aImage = $request->file('channel_image');
        //         $ext = $aImage->getClientOriginalExtension();
        //         $fileName = 'channel-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
        //         $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");
        //         $channel->logoImage = $fileName;
        //     }
        //     if($request->hasFile('intro_vedio')) {
        //         $aImage = $request->file('intro_vedio');
        //         $ext = $aImage->getClientOriginalExtension();
        //         $fileName = 'channel-intro-vedio-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
        //         $filePath = $this->imageController->UploadVideo($this->channelImagePath, $aImage, $fileName, "-");
        //         $channel->introVideo = $fileName;
        //     }
        //     $channel->save();
        //      // Insert to Content Policy Table
        //     if(isset($request->content_policies)){
        //         foreach ($request->content_policies as $key => $contentpolicy) {
        //             ContentPolicy::create([
        //                 'ContentID'=>$channel->channelId,
        //                 'PolicyID'=>$contentpolicy,
        //                 'ContentType'=>1,
        //                 'Status'=>1,
        //                 'type'=>null
        //             ]);
                    
        //         }
        //     }

        //     return redirect('admin/channel/add')->with(['success' => true,
        //     'success.message' => 'Channel Created successfully!',
        //     'success.title' => 'Well Done!']);
        // }else{
        //     return redirect('admin/channel/add')->with([
        //         'error' => true,
        //         'error.message'=> 'Error adding new Channel. Please try again.',
        //         'error.title' => 'Oops !!'
        //     ]);
        // }
       
       

       
    }
    // Channel Edit View Load
    public function editView($id)
    {
        $exsist_channel=Channel::with(['getContentPolices.getPolicy'])->find($id);
        $image = [];
        $image_config = [];
        $intro_vedio = [];
        $intro_vedio_config = [];
        
        if($exsist_channel){
            $advertismentPolicies=Policy::getAdvertisementPolicies();
            $used_content_policy_ids = array_column(json_decode($exsist_channel->getContentPolices), 'PolicyID');
            $channelContentPolicies=Policy::getChannelContentPoliciesByFilterIds($used_content_policy_ids);

            if ($exsist_channel->logoImage) {
                array_push($image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.channel') . $exsist_channel->logoImage . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $exsist_channel->channelId,
                    // 'url' => url('admin/channel/image-delete'),
                ));
            }
            if ($exsist_channel->introVideo) {
                array_push($intro_vedio, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.channel') . $exsist_channel->introVideo . "'>");
                array_push($intro_vedio_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $exsist_channel->channelId,
                    // 'url' => url('admin/channel/intro-vedio-delete'),
                ));
            }
         
            return view('ChannelManage::edit')
            ->with(
                ['channelContentPolicies'=>$channelContentPolicies,
                'advertismentPolicies'=>$advertismentPolicies,
                'exsist_channel'=>$exsist_channel,
                'image'=>$image,
                'image_config'=>$image_config,
                'intro_vedio'=>$intro_vedio,
                'intro_vedio_config'=>$intro_vedio_config
                
                ]
            );

            return $exsist_channel;
        }else{
            return "Channel Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {
        $exsist_channel=Channel::with(['getContentPolices.getPolicy'])->find($id);
        
        $kids=0;
        if($request->kids_channel=="on"){
            $kids=1;
        }

        $exsist_channel->channelName=$request->channel_name_en;
        $exsist_channel->channelDesc=$request->channel_description_en;
        $exsist_channel->advertisementPolicy=$request->advertisment_policy;
        $exsist_channel->kids=$kids;
        $exsist_channel->channelName_si=$request->channel_name_si;
        $exsist_channel->channelName_ta=$request->channel_name_ta;
        $exsist_channel->channelDesc_si=$request->channel_description_si;
        $exsist_channel->channelDesc_ta=$request->channel_description_ta;
        $exsist_channel->search_tag=json_encode($request->tags);

        $exsist_channel->save();

        if($exsist_channel){
            if($request->hasFile('channel_image')) {
                $aImage = $request->file('channel_image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'channel-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->Upload($this->channelImagePath, $aImage, $fileName, "-");
                $exsist_channel->logoImage = $fileName;
            }else if($request->has('image_removed') && $request->get('image_removed') == 1){
                $exsist_channel->logoImage =  null;
            }

            if($request->hasFile('intro_vedio')) {
                $aImage = $request->file('intro_vedio');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'channel-intro-vedio-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->UploadVideo($this->channelImagePath, $aImage, $fileName, "-");
                $exsist_channel->introVideo = $fileName;
            }else if($request->has('vedio_removed') && $request->get('vedio_removed') == 1){
                $exsist_channel->introVideo =  null;
            }
            $exsist_channel->save();
            ContentPolicy::where('status', 1)
                ->where('ContentID', $exsist_channel->channelId)
                ->where('ContentType', 1)
                ->update(['status' => 0]);
             // Insert to Content Policy Table
            // return $request->content_policies;
            if(isset($request->content_policies)){
                foreach ($request->content_policies as $key => $contentpolicy) {
                     ContentPolicy::create([
                        'ContentID'=>$exsist_channel->channelId,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>1,
                        'Status'=>1,
                        'type'=>null
                    ]);
                    
                }
                
            }

            return redirect('channel/'.$id.'/edit')->with(['success' => true,
            'success.message' => 'Channel Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('admin/channel/'.$id.'/edit')->with([
                'error' => true,
                'error.message'=> 'Error adding new Channel. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
      
       
    }
    public function listView()
    {
        return view('ChannelManage::list');
    }
    public function listJson()
    {
        // try {
            $user = Sentinel::getUser();
            return Datatables::usingCollection(
                Channel::select('channelId', 'channelName', 'channelName_si','channelName_ta', 'kids','status')->get()
            )
                ->editColumn('status', function ($value){
                    if($value->status==1){
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue channel-status-toggle " data-id="'.$value->channelId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    }else{
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue channel-status-toggle " data-id="' . $value->channelId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                ->editColumn('kids', function ($value){
                    if($value->kids == 1){
                        return '<center><i class="fa fa-check"></i><center>';
                    }else{
                        return '<center><i class="fa fa-remove"></i></center>';
                    }
                })
                ->addColumn('edit', function ($value) use ($user){
                    if($user->hasAnyAccess(['channel.edit', 'admin'])){
                        return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('channel/'.$value->channelId.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Channel"><i class="fa fa-pencil"></i></a></center>';
                    }else{
                        return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
                    }
                        
                })
                ->make(true);
        // }catch (\Throwable $exception){
        //     $exceptionId = rand(0, 99999999);
        //     Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
        //     return Datatables::of(collect())->make(true);
        // }
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
 
   
  

}