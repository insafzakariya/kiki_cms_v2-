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
use EpisodeManage\Models\Episode;
use Sentinel;
use Validator;


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
                'thumb_image'=>1,
                'cover_image'=>1
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
                            'ContentType'=>2,
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
    

        

       
       
    }
    // Programme Edit View Load
    public function editView($id)
    {
        $exsist_programme=Programme::with([
            'getContentPolices.getPolicy',
            'getProgrammeThumbImages',
            'getProgrammeCoverImages',
            'getProgrammeChannels.getChannel'
            ])
        ->find($id);
        $channels=Channel::where('status',1)->get();
        $thumb_image = [];
        $thumb_image_config = [];
        $cover_image = [];
        $cover_image_config = [];
       
        
        if($exsist_programme){
            $advertismentPolicies=Policy::getAdvertisementPolicies();
            $used_channel_ids=array_column(json_decode($exsist_programme->getProgrammeChannels), 'channel_id');
            $used_content_policy_ids = array_column(json_decode($exsist_programme->getContentPolices), 'PolicyID');
            $programmeContentPolicies=Policy::getProgrammeContentPoliciesByFilterIds($used_content_policy_ids);
            if($exsist_programme->thumb_image){
                if ($exsist_programme->getProgrammeThumbImages) {
                    foreach ($exsist_programme->getProgrammeThumbImages as $key => $thumb_image_value) {
                        array_push($thumb_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.programme') . $thumb_image_value->file_name . "'>");
                        array_push($thumb_image_config, array(
                            'caption' => '',
                            'type' => 'image',
                            'key' => $thumb_image_value->id,
                            'url' => url('programme/image-delete'),
                        ));
                    }
                    
                }
            }else{
                array_push($thumb_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.programme') . $exsist_programme->logo . "'>");
                array_push($thumb_image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => 0,
                    'url' => url('programme/image-delete'),
                ));
            }

            if($exsist_programme->cover_image){
                if ($exsist_programme->getProgrammeCoverImages) {
                    foreach ($exsist_programme->getProgrammeCoverImages as $key => $cover_image_value) {
                        array_push($cover_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.programme') . $cover_image_value->file_name . "'>");
                        array_push($cover_image_config, array(
                            'caption' => '',
                            'type' => 'image',
                            'key' => $cover_image_value->id,
                            'url' => url('programme/image-delete')
                        ));
                    }
                    
                }
            }else{
                array_push($cover_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.programme') . $exsist_programme->coverImage . "'>");
                array_push($cover_image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => 0,
                    'url' => url('programme/image-delete'),
                ));
            }
        
            return view('ProgrammeManage::edit')
            ->with(
                ['programmeContentPolicies'=>$programmeContentPolicies,
                'advertismentPolicies'=>$advertismentPolicies,
                'exsist_programme'=>$exsist_programme,
                'thumb_image'=>$thumb_image,
                'thumb_image_config'=>$thumb_image_config,
                'cover_image'=>$cover_image,
                'cover_image_config'=>$cover_image_config,
                'channels'=>$channels,
                'used_channel_ids'=>$used_channel_ids
                
                ]
            );

            return $exsist_channel;
        }else{
            return "Channel Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {
        //   return $request->all();
      
        $exsist_programme=Programme::with(['getContentPolices.getPolicy'])->find($id);
        
        $kids=0;
        if($request->kids_programme=="on"){
            $kids=1;
        }
        

        $exsist_programme->programName=$request->programme_name_en;
        $exsist_programme->description=$request->programme_description_en;
        $exsist_programme->advertisementPolicy=$request->advertisment_policy;
        $exsist_programme->kids=$kids;
        $exsist_programme->programmeName_si=$request->programme_name_si;
        $exsist_programme->programmeName_ta=$request->programme_name_ta;
        $exsist_programme->programmeDesc_si=$request->programme_description_si;
        $exsist_programme->programmeDesc_ta=$request->programme_description_ta;
        $exsist_programme->start_date=$request->start_date;
        $exsist_programme->end_date=$request->end_date;
        $exsist_programme->subtitles=$request->subtitle;
        $exsist_programme->likes=$request->likes;
        $exsist_programme->programType=$request->programme_type;
        $exsist_programme->search_tag=json_encode($request->tags);
        
        if($exsist_programme){
            if(isset($request->channels)){
                // return $request->channels;
                ProgrammeChannel::whereNotIn('channel_id',$request->channels)->where('programme_id',$exsist_programme->programId)->update(['status' => 0]);
                foreach ($request->channels as $key => $channel) {
                    ProgrammeChannel::firstOrCreate(['programme_id' =>$exsist_programme->programId,'channel_id'=>$channel,'status'=>1]);
                }
            }
            if($request->hasFile('cover_image')) {
                $cover_images=$request->file('cover_image');

                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'programme-cover-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");

                    MasterImage::create([
                        'parent_type'=>'programme',
                        'parent_id'=>$exsist_programme->programId,
                        'image_type'=>'cover_image',
                        'file_name'=>$fileName
                    ]);
                }
            }else if($request->has('cover_image_removed') && $request->get('cover_image_removed') == 1){
                MasterImage::where('status', 1)
                ->where('parent_type', "programme")
                ->where('parent_id', $exsist_programme->programId)
                ->where('image_type', "cover_image")
                ->update(['status' => 0]);
            }

            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');

                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'programme-thumb-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");
                    
                    MasterImage::create([
                        'parent_type'=>'programme',
                        'parent_id'=>$exsist_programme->programId,
                        'image_type'=>'thumb_image',
                        'file_name'=>$fileName
                    ]);
                }
            }else if($request->has('thumb_image_removed') && $request->get('thumb_image_removed') == 1){
                MasterImage::where('status', 1)
                ->where('parent_type', "programme")
                ->where('parent_id', $exsist_programme->programId)
                ->where('image_type', "thumb_image")
                ->update(['status' => 0]);
            }
            //Old Image Save to Master Image Table
            if($exsist_programme->thumb_image==0){
                if( !in_array( "s",json_decode($request->thumb_image_preview_deleted)) )
                {
                    MasterImage::create([
                        'parent_type'=>'programme',
                        'parent_id'=>$exsist_programme->programId,
                        'image_type'=>'thumb_image',
                        'file_name'=>$exsist_programme->logo
                    ]);
                    
                }

                $exsist_programme->thumb_image=1;
            }
            if($exsist_programme->cover_image==0){
                if( !in_array( "s",json_decode($request->cover_image_preview_deleted)) )
                {
                    MasterImage::create([
                        'parent_type'=>'programme',
                        'parent_id'=>$exsist_programme->programId,
                        'image_type'=>'cover_image',
                        'file_name'=>$exsist_programme->logo
                    ]);
                    
                }
                $exsist_programme->cover_image=1;
            }
            

            //Image Delete
            
            if(isset($request->cover_image_preview_deleted) & $request->cover_image_preview_deleted !=""){
                foreach (json_decode($request->cover_image_preview_deleted) as $key => $image) {
                    MasterImage::where('id', $image)
                        ->update(['status' => 0]);
                }
            }

            if(isset($request->thumb_image_preview_deleted) & $request->thumb_image_preview_deleted !=""){
                foreach (json_decode($request->thumb_image_preview_deleted) as $key => $image) {
                    if($image>0){
                        MasterImage::where('id', $image)
                        ->update(['status' => 0]);
                    }
                    
                }
            }

            
            ContentPolicy::where('status', 1)
                ->where('ContentID', $exsist_programme->programId)
                ->where('ContentType', 2)
                ->update(['status' => 0]);
             // Insert to Content Policy Table
            // return $request->content_policies;
            if(isset($request->content_policies)){
                foreach ($request->content_policies as $key => $contentpolicy) {
                     ContentPolicy::create([
                        'ContentID'=>$exsist_programme->programId,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>2,
                        'Status'=>1,
                        'type'=>null
                    ]);
                    
                }
                
            }
            $exsist_programme->save();

            return redirect('programme/'.$id.'/edit')->with(['success' => true,
            'success.message' => 'Channel Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('programme/'.$id.'/edit')->with([
                'error' => true,
                'error.message'=> 'Error adding new Channel. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
      
       
    }
    public function listView()
    {
        return view('ProgrammeManage::list');
    }
    public function listJson()
    {
        // try {
            $user = Sentinel::getUser();
        //    return Programme::with(['getProgrammeChannels'])->where('status','!=',2)->get();
            return Datatables::usingCollection(
                Programme::with(['getProgrammeChannels.getChannel'])->where('status','!=',2)->select('programId', 'programName', 'start_date','end_date','duration','programType', 'status')->get()
            )
                ->editColumn('status', function ($value){
                    if($value->status==1){
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="'.$value->programId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    }else{
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="' . $value->programId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                ->addColumn('edit', function ($value) use ($user){
                    if($user->hasAnyAccess(['programme.edit', 'admin'])){
                        $url=url('programme/'.$value->programId.'/edit');
                        return '<center><a href="'.$url.'" class="blue"  data-toggle="tooltip" data-placement="top" title="View/ Edit Channel"><i class="fa fa-pencil"></i></a></center>';
                    }else{
                        return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
                    }
                        
                })
                ->addColumn('channels', function (Programme $value) {
                    $channels='';
                    foreach($value->getProgrammeChannels AS $chanel){
                        $channels.=$chanel->getChannel->channelName.",";
                    }
                    return rtrim($channels, ',');
    
                })
                ->addColumn('viewEpisode', function ($value) use ($user){
                    if($user->hasAnyAccess(['programme.edit', 'admin'])){
                        $url=url('episode/'.$value->programId.'/programme/episode');
                        return '<center><a href="'.$url.'" class="blue" data-toggle="tooltip" data-placement="top" title="View Episode"><i class="fa fa-television"></i></a></center>';
                    }else{
                        return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="View Disabled"><i class="fa fa-television"></i></a></center>';
                    }
                        
                })
                ->editColumn('delete', function ($value){
                    return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-delete " data-id="'.$value->programId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></><center>'; 
                })

                ->addColumn('bulk-update', function ($value) use ($user){
                    if($user->hasAnyAccess(['programme.edit', 'admin'])){
                        return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('programme/'.$value->programId.'/policy').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Channel"><i class="fa fa-universal-access"></i></a></center>';
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

        $programme = Programme::find($id);
        if ($programme) {
            $programme->status = $state;
            $programme->save();
           
            ProgrammeChannel::where('programme_id',$programme->programId)
            ->update(['status'=>$state]);
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
    public function programmeDelete(Request $request)
    {
        $id = $request->id;
    
        $programme = Programme::find($id);
        if ($programme) {
            $programme->status = 2;
            $programme->save();
           
            ProgrammeChannel::where('programme_id',$programme->programId)
            ->update(['status'=>2]);
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    public function sortView()
    {
        $channels=Channel::get();
        return view('ProgrammeManage::sort')->with(['channels'=>$channels]);
    }

    public function getUnsortedList(Request $request)
    {
        $channel_id= $request->get('channel_id');
        $unsortedProgrammes=ProgrammeChannel::with(['getProgramme'])
                                    ->where('channel_id',$channel_id)
                                    ->where('order',0)
                                    ->where('status',1)
                                    ->get();
        if($unsortedProgrammes){
            return response($unsortedProgrammes, 200);
        }else{
            return response(null, 200);
        }
    }
    public function getsortedList(Request $request)
    {
        $channel_id= $request->get('channel_id');
        $sortedProgrammes=ProgrammeChannel::with(['getProgramme'])
                                    ->where('channel_id',$channel_id)
                                    ->where('order','!=',0)
                                    ->where('status',1)
                                    ->orderBy('order','asc')
                                    ->get();
        if($sortedProgrammes){
            return response($sortedProgrammes, 200);
        }else{
            return response(null, 200);
        }
    }

    //Update Sorted & Unsorted List to DB
    public function updateSortedProgrammes(Request $request)
    {
        $sorted_list=$request->get('sorted_list');
        $unsorted_list=$request->get('unsorted_list');
        ProgrammeChannel::with(['getProgramme'])
            ->whereIn('id',$unsorted_list)
            ->update(['order'=>0]);
        foreach ($sorted_list as $key => $value) {  
            ProgrammeChannel::with(['getProgramme'])
            ->where('id',$value)
            ->update(['order'=>$key+1]);
        }
       
       
    }
    public function policyView($id)
    {
        $programme=Programme::find($id);
        $episode_count= Episode::where('programId', $id)->count();
        if($episode_count>0){
            $episodeContentPolicies=Policy::getEpisodeContentPolicies();
            
            return view('ProgrammeManage::bulkPolicyUpdate')
            ->with(
                [
                'episodeContentPolicies'=>$episodeContentPolicies,
                'programme'=>$programme,
                'episode_count'=>$episode_count
                
                ]
            );

        }else{
            return redirect('programme')->with('programme-error-details', "No Episodes for :".$programme->programName . "[".$programme->programId."]");
        }
        
    }

    public function policy(Request $request,$id)
    {
        $episode_ids=Episode::where('programId', $id)->where('status',1)->lists('episodeId')->toArray();
        $content_array=array();
        

        if(isset($request->content_policies)){
            ContentPolicy::where('status', 1)
                ->whereIn('ContentID', $episode_ids)
                ->where('ContentType', 3)
                ->delete();
            foreach($episode_ids AS $single_episode){
                foreach ($request->content_policies as $key => $contentpolicy) {
                    $single_array=array(
                        'ContentID'=>$single_episode,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>3,
                        'Status'=>1,
                        'type'=>null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    array_push($content_array,$single_array);
                    
                }
            }
        }
        // return $content_array;
        ContentPolicy::insert($content_array);
        return redirect('programme')->with('programme-details', "Policy Added Sucessfully");
    }

    public function deleteImage(Request $request)
    {
        return $request->key;
    }
 
   
  

}
