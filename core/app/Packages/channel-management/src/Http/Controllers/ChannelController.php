<?php

namespace ChannelManage\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\Policy;
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
class ChannelController extends Controller
{
    private $channelImagePath ;

    /**
     * @var ImageController
     */
    private $imageController;

    public function __construct()
    {
      $this->channelImagePath = Config::get('filePaths.channel-images');
      $this->imageController = new ImageController();   
    }

    public function index()
    {
        $channelContentPolicies=Policy::getChannelContentPolicies();
        $advertismentPolicies=Policy::getAdvertisementPolicies();
        return view('ChannelManage::add')->with(['channelContentPolicies'=>$channelContentPolicies,'advertismentPolicies'=>$advertismentPolicies]);
    }
 
    public function store(Request $request)
    {

       
        // return $request->all();
        //Kids On Validation
        $kids=0;
        if($request->kids_channel=="on"){
            $kids=1;
        }
        
        $channel=Channel::create([
            'channelName'=>$request->channel_name_en,
            'channelDesc'=>$request->channel_description_en,
            'workingHours'=>"",
            'advertisementPolicy'=>$request->advertisment_policy,
            'status'=>1,
            'channel_order'=>0,
            'kids'=>$kids,
            'parentChannelId'=>"",
            'channelName_si'=>$request->channel_name_si,
            'channelName_ta'=>$request->channel_name_ta,
            'channelDesc_si'=>$request->channel_description_si,
            'channelDesc_ta'=>$request->channel_description_ta,
            'search_tag'=>json_encode($request->tags)
        ]);

        

       
        if($channel){
            if($request->hasFile('channel_image')) {
                $aImage = $request->file('channel_image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'channel-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->Upload($this->channelImagePath, $aImage, $fileName, "-");
                $channel->logoImage = $fileName;
            }
            if($request->hasFile('intro_vedio')) {
                $aImage = $request->file('intro_vedio');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'channel-intro-vedio-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->UploadVideo($this->channelImagePath, $aImage, $fileName, "-");
                $channel->introVideo = $fileName;
            }
            $channel->save();
             // Insert to Content Policy Table
            if(isset($request->content_policies)){
                foreach ($request->content_policies as $key => $contentpolicy) {
                    ContentPolicy::create([
                        'ContentID'=>$channel->channelId,
                        'PolicyID'=>$contentpolicy,
                        'ContentType'=>2,
                        'Status'=>1,
                        'type'=>null
                    ]);
                    
                }
            }

            return redirect('admin/channel')->with(['success' => true,
            'success.message' => 'Channel Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('admin/channel')->with([
                'error' => true,
                'error.message'=> 'Error adding new Channel. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
       
       

       
    }
    // Channel Edit View Load
    public function editView($id)
    {
        $exsist_channel=Channel::with(['getContentPolices.getPolicy'])->find($id);
        
        if($exsist_channel){
            $advertismentPolicies=Policy::getAdvertisementPolicies();
            $used_content_policy_ids = array_column(json_decode($exsist_channel->getContentPolices), 'PolicyID');
            $channelContentPolicies=Policy::getChannelContentPoliciesByFilterIds($used_content_policy_ids);
           
            return view('ChannelManage::edit')
            ->with(
                ['channelContentPolicies'=>$channelContentPolicies,
                'advertismentPolicies'=>$advertismentPolicies,
                'exsist_channel'=>$exsist_channel
                
                ]
            );

            return $exsist_channel;
        }else{
            return "Channel Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {
        return $request->all();
    }
 
   
  

}
