<?php

namespace EpisodeManage\Http\Controllers;


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
use EpisodeManage\Models\EpisodeChannel;
use EpisodeManage\Models\Episode;
use Sentinel;


class EpisodeController extends Controller
{
    private $programmeImagePath ;

    /**
     * @var ImageController
     */
    private $imageController;

    public function __construct()
    {
      $this->programmeImagePath = Config::get('filePaths.episode-images');
      $this->imageController = new ImageController();   
    }

    public function index()
    {
        $episodeContentPolicies=Policy::getEpisodeContentPolicies();
        $advertismentPolicies=Policy::getAdvertisementPolicies();
        // return $programmes=Programme::where('status',1)->get();
        $channels=Channel::where('status',1)->get();
        return view('EpisodeManage::add')
        ->with([
            'episodeContentPolicies'=>$episodeContentPolicies,
            'advertismentPolicies'=>$advertismentPolicies,
            'channels'=>$channels,
            ]);
    }

    public function programmeSearch(Request $request)
    {
        $search = $request->get('term');
        $program = [];
        if($search){
            $program =  Programme::where('programName', 'like', '%' . $search . '%')->where('status', 1)->limit(10)->orderBy('programName', 'asc')->get();
        }

        return $program;

    }
 
    public function store(Request $request)
    {

       
        // return $request->all();
        //Trailer On Validation
        $isTrailer=0;
        if($request->trailer=="on"){
            $isTrailer=1;
        }
        
        $episode=Episode::create([
            'episodeName'=>$request->episode_name_en,
            'description'=>$request->episode_description_en,
            // 'advertisementPolicy'=>$request->advertisment_policy,
            'video_quality'=>json_encode($request->video_quality),
            'status'=>1,
            'isTrailer'=>$isTrailer,
            'episodeDesc_si'=>$request->episode_description_si,
            'episodeDesc_ta'=>$request->episode_description_ta,
            'programId'=>$request->programme,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'publish_date'=>$request->publish_date,
            'search_tag'=>json_encode($request->tags)
        ]);

        if($episode){
            if(isset($request->channels)){
                foreach ($request->channels as $key => $channel) {
                    EpisodeChannel::create([
                        'episode_id'=>$episode->episodeId,
                        'channel_id'=>$channel,
                        'status'=>1
                    ]);
                }
            }

            // Insert to Content Policy Table
            if(isset($request->content_policies)){
                foreach ($request->content_policies as $key => $contentpolicy) {
                    ContentPolicy::create([
                        'ContentID'=>$episode->episodeId,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>4,
                        'Status'=>1,
                        'type'=>null
                    ]);
                }
            }

            
            //Thumb Image Upload
            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');
                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'episode-thumb-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");
                    MasterImage::create([
                        'parent_type'=>'episode',
                        'parent_id'=>$episode->episodeId,
                        'image_type'=>'thumb_image',
                        'file_name'=>$fileName
                    ]);
                }
                
            }

        return redirect('episode/add')->with(['success' => true,
            'success.message' => 'Episode Created successfully!',
            'success.title' => 'Well Done!']);
           
        }else{
            return redirect('episode/add')->with([
                'error' => true,
                'error.message'=> 'Error adding new Episode. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }

       
       
    }
    // Programme Edit View Load
    public function editView($id)
    {
        $video_qualities=array('auto'=>'Auto','720p'=>'720p','480p'=>'480p','360p'=>'360p','240p'=>'240p','144p'=>'144p');
        
        $exsist_episode=Episode::with([
            'getProgramme',
            'getContentPolices.getPolicy',
            'getEpisodeThumbImages',
            'getEpisodeChannels.getChannel'
            ])
        ->find($id);
        $channels=Channel::where('status',1)->get();
        $thumb_image = [];
        $thumb_image_config = [];
        
        if($exsist_episode){
            $advertismentPolicies=Policy::getAdvertisementPolicies();
            $used_channel_ids=array_column(json_decode($exsist_episode->getEpisodeChannels), 'channel_id');
            $used_content_policy_ids = array_column(json_decode($exsist_episode->getContentPolices), 'PolicyID');
            $episodeContentPolicies=Policy::getEpisodeContentPoliciesByFilterIds($used_content_policy_ids);

            if ($exsist_episode->getEpisodeThumbImages) {
                foreach ($exsist_episode->getEpisodeThumbImages as $key => $thumb_image_value) {
                    array_push($thumb_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.episode') . $thumb_image_value->file_name . "'>");
                    array_push($thumb_image_config, array(
                        'caption' => '',
                        'type' => 'image',
                        'key' => $thumb_image_value->id,
                        // 'url' => url('admin/channel/image-delete'),
                    ));
                }
                
            }
           
        //  return $thumb_image;
            return view('EpisodeManage::edit')
            ->with(
                ['episodeContentPolicies'=>$episodeContentPolicies,
                // 'advertismentPolicies'=>$advertismentPolicies,
                'exsist_episode'=>$exsist_episode,
                'thumb_image'=>$thumb_image,
                'thumb_image_config'=>$thumb_image_config,
                'channels'=>$channels,
                'used_channel_ids'=>$used_channel_ids,
                'video_qualities'=>$video_qualities
                
                ]
            );

           
        }else{
            return "Episode Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {

        //   return $request->all();
        $exsist_episode=Episode::with(['getContentPolices.getPolicy'])->find($id);
        
        //Trailer On Validation
        $isTrailer=0;
        if($request->trailer=="on"){
            $isTrailer=1;
        }

        $exsist_episode->episodeName=$request->episode_name_en;
        $exsist_episode->description=$request->episode_description_en;
        $exsist_episode->video_quality=json_encode($request->video_quality);
        $exsist_episode->isTrailer=$isTrailer;
        $exsist_episode->episodeDesc_si=$request->episode_description_si;
        $exsist_episode->episodeDesc_ta=$request->episode_description_ta;
        $exsist_episode->programId=$request->programme;
        $exsist_episode->start_date=$request->start_date;
        $exsist_episode->end_date=$request->end_date;
        $exsist_episode->publish_date=$request->publish_date;
        $exsist_episode->search_tag=json_encode($request->tags);

        $exsist_episode->save();

        if($exsist_episode){
            if(isset($request->channels)){
                // return $request->channels;
                EpisodeChannel::whereNotIn('channel_id',$request->channels)->where('episode_id',$exsist_episode->episodeId)->update(['status' => 0]);
                foreach ($request->channels as $key => $channel) {
                    EpisodeChannel::firstOrCreate(['episode_id' =>$exsist_episode->episodeId,'channel_id'=>$channel,'status'=>1]);
                }
            }
            
            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');
                MasterImage::where('status', 1)
                    ->where('parent_type', "episode")
                    ->where('parent_id', $exsist_episode->episodeId)
                    ->where('image_type', "thumb_image")
                    ->update(['status' => 0]);

                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'episode-thumb-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeImagePath, $aImage, $fileName, "-");
                    
                    MasterImage::create([
                        'parent_type'=>'episode',
                        'parent_id'=>$exsist_episode->episodeId,
                        'image_type'=>'thumb_image',
                        'file_name'=>$fileName
                    ]);
                }
            }else if($request->has('thumb_image_removed') && $request->get('thumb_image_removed') == 1){
                MasterImage::where('status', 1)
                ->where('parent_type', "programme")
                ->where('parent_id', $exsist_episode->episodeId)
                ->where('image_type', "thumb_image")
                ->update(['status' => 0]);
            }
            
            ContentPolicy::where('status', 1)
                ->where('ContentID', $exsist_episode->episodeId)
                ->where('ContentType', 4)
                ->update(['status' => 0]);
             // Insert to Content Policy Table
            // return $request->content_policies;
            if(isset($request->content_policies)){
                foreach ($request->content_policies as $key => $contentpolicy) {
                     ContentPolicy::create([
                        'ContentID'=>$exsist_episode->episodeId,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>4,
                        'Status'=>1,
                        'type'=>null
                    ]);
                    
                }
                
            }

            return redirect('episode/'.$id.'/edit')->with(['success' => true,
            'success.message' => 'Episode Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('episode/'.$id.'/edit')->with([
                'error' => true,
                'error.message'=> 'Error adding new Episode. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
      
       
    }
    public function listView()
    {
        return view('EpisodeManage::list');
    }
    public function listJson()
    {
        // try {
            $user = Sentinel::getUser();
            return Datatables::usingCollection(
                Episode::select('episodeId', 'episodeName','programId','status')->get()
            )
                ->editColumn('status', function ($value){
                    if($value->status==1){
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue episode-status-toggle " data-id="'.$value->episodeId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    }else{
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue episode-status-toggle " data-id="' . $value->episodeId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                
                ->addColumn('edit', function ($value) use ($user){
                    if($user->hasAnyAccess(['episode.edit', 'admin'])){
                        return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('episode/'.$value->episodeId.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Episode"><i class="fa fa-pencil"></i></a></center>';
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

        $episode = Episode::find($id);
        if ($programme) {
            $programme->status = $state;
            $programme->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    // public function sortView()
    // {
    //     $channels=Channel::get();
    //     return view('ProgrammeManage::sort')->with(['channels'=>$channels]);
    // }

    // public function getUnsortedList(Request $request)
    // {
    //     $channel_id= $request->get('channel_id');
    //     $unsortedProgrammes=ProgrammeChannel::with(['getProgramme'])
    //                                 ->where('channel_id',$channel_id)
    //                                 ->where('order',0)
    //                                 ->where('status',1)
    //                                 ->get();
    //     if($unsortedProgrammes){
    //         return response($unsortedProgrammes, 200);
    //     }else{
    //         return response(null, 200);
    //     }
    // }
    // public function getsortedList(Request $request)
    // {
    //     $channel_id= $request->get('channel_id');
    //     $sortedProgrammes=ProgrammeChannel::with(['getProgramme'])
    //                                 ->where('channel_id',$channel_id)
    //                                 ->where('order','!=',0)
    //                                 ->where('status',1)
    //                                 ->orderBy('order','asc')
    //                                 ->get();
    //     if($sortedProgrammes){
    //         return response($sortedProgrammes, 200);
    //     }else{
    //         return response(null, 200);
    //     }
    // }

    // //Update Sorted & Unsorted List to DB
    // public function updateSortedProgrammes(Request $request)
    // {
    //     $sorted_list=$request->get('sorted_list');
    //     $unsorted_list=$request->get('unsorted_list');
    //     ProgrammeChannel::with(['getProgramme'])
    //         ->whereIn('id',$unsorted_list)
    //         ->update(['order'=>0]);
    //     foreach ($sorted_list as $key => $value) {  
    //         ProgrammeChannel::with(['getProgramme'])
    //         ->where('id',$value)
    //         ->update(['order'=>$key+1]);
    //     }
       
       
    // }
 
   
  

}
