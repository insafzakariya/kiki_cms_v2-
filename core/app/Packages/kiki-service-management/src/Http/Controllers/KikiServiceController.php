<?php

namespace KikiServiceManage\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Carbon\Carbon;
use Config;
use Datatables;
use Exception;
use File;
use Illuminate\Http\Request;
use Log;
use Response;
use Session;
use Sentinel;
use KikiServiceManage\Models\KikiService;



class KikiServiceController extends Controller
{
    private $serviceImagePath ;

    /**
     * @var ImageController
     */
    private $imageController;

    public function __construct()
    {
      $this->serviceImagePath = Config::get('filePaths.kiki-service');
      $this->imageController = new ImageController();   
    }

    public function index()
    {
        $kiki_services=KikiService::where('status',1)->get();
        return view('KikiServiceManage::add')->with(['kiki_services'=>$kiki_services]);
    }
 
    public function store(Request $request)
    {
        $full_screen=0;
        $portrait=0;
        if($request->full_screen=='on'){
            $full_screen=1;
        }
        if($request->portrait=='on'){
            $portrait=1;
        }
       
        $Kiki_service_max = KikiService::where('status', 1)->max('rslt_order');
        $kikiService=KikiService::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'parent_id'=>$request->parent_service,
            'url'=>$request->url,
            'landing_url'=>$request->landing_url,
            'referance'=>$request->reference,
            'bridgeid'=>$request->bridge_id,
            'status'=>1,
            'rslt_order'=>$Kiki_service_max + 1,
            'portrait'=>$portrait,
            'full_screen'=>$full_screen,
        ]);

        if($kikiService){
            //Slider Image Image Upload
            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');
                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                   
                    if($request->parent_service == ''){
                        $fileName ='kiki-service-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    }else{
                        $parent= KikiService::find($request->parent_service);
                        $fileName = $parent->name.'/'.'kiki-service-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    }
                    $filePath = $this->imageController->Upload($this->serviceImagePath, $aImage, $fileName, "-");
                  
                    $kikiService->image_path=$fileName;
                }
                $kikiService->save();
                
            }

        return redirect('service/add')->with(['success' => true,
            'success.message' => 'Kiki Service Created successfully!',
            'success.title' => 'Well Done!']);
           
        }else{
            return redirect('service/add')->with([
                'error' => true,
                'error.message'=> 'Error adding new Kiki Service. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
       
    }
    // Kiki Service Edit View Load
    public function editView($id)
    { 
        $exsist_kiki_service=KikiService::find($id);
        $kiki_services=KikiService::where('status',1)->where('id', '!=' , $id)->get();
       
        $thumb_image = [];
        $thumb_image_config = [];

        if($exsist_kiki_service){
        
            array_push($thumb_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.kiki_service') . $exsist_kiki_service->image_path . "'>");
            array_push($thumb_image_config, array(
                'caption' => '',
                'type' => 'image',
                'key' => 0,
                'url' => url('service/image-delete'),
            ));
 
        
            return view('KikiServiceManage::edit')
            ->with(
                [
                'exsist_kiki_service'=>$exsist_kiki_service,
                'thumb_image'=>$thumb_image,
                'thumb_image_config'=>$thumb_image_config,
                'kiki_services'=>$kiki_services
                ]
            );

        }else{
            return "Kiki Services Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {
        //   return $request->all();
        $full_screen=0;
        $portrait=0;
        if($request->full_screen=='on'){
            $full_screen=1;
        }
        if($request->portrait=='on'){
            $portrait=1;
        }
        $exsist_kiki_service=KikiService::find($id);

        $exsist_kiki_service->name=$request->name;
        $exsist_kiki_service->description=$request->description;
        $exsist_kiki_service->parent_id=$request->parent_service;
        $exsist_kiki_service->url=$request->url;
        $exsist_kiki_service->landing_url=$request->landing_url;
        $exsist_kiki_service->referance=$request->reference;
        $exsist_kiki_service->bridgeid=$request->bridge_id;
        $exsist_kiki_service->full_screen=$full_screen;
        $exsist_kiki_service->portrait=$portrait;
       
        if($exsist_kiki_service){
        
            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');

                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    if($request->parent_service == ''){
                        $fileName ='kiki-service-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    }else{
                        $parent= KikiService::find($request->parent_service);
                        $fileName = $parent->name.'/'.'kiki-service-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    }

                    $filePath = $this->imageController->Upload($this->serviceImagePath, $aImage, $fileName, "-");
                    $exsist_kiki_service->image_path=$fileName;;
                    
                }
            }else if($request->has('thumb_image_removed') && $request->get('thumb_image_removed') == 1){
                $exsist_kiki_service->image_path="";
            }
            $exsist_kiki_service->save();

            return redirect('service/'.$id.'/edit')->with(['success' => true,
            'success.message' => 'Kiki Service Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('service/'.$id.'/edit')->with([
                'error' => true,
                'error.message'=> 'Error adding Kiki Service Edit. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
      
       
    }
    public function listView()
    {
        $services=KikiService::with(['getService'])->orderBy('rslt_order', 'ASC')->where('status','!=',0)->get();
        return view('KikiServiceManage::list',compact('services'));
    }
    public function updateOrder(Request $request)
    {
        foreach ($request->order as $order) {
            KikiService::where('id',$order['id'])->update(['rslt_order' => $order['position']]);
            
        }
        
        return response('Update Successfully.', 200);
    }

    // public function listJson()
    // {
    //     // try {
    //         $user = Sentinel::getUser();
    //         return Datatables::usingCollection(
    //             Programme::select('programId', 'programName', 'programmeName_si','programmeName_ta', 'kids','status')->get()
    //         )
    //             ->editColumn('status', function ($value){
    //                 if($value->status==1){
    //                     return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="'.$value->programId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
    //                 }else{
    //                     return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="' . $value->programId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
    //                 }
    //                 return $value->status == 1 ? 'Activated' : 'Inactivated';
    //             })
    //             ->editColumn('kids', function ($value){
    //                 if($value->kids == 1){
    //                     return '<center><i class="fa fa-check"></i><center>';
    //                 }else{
    //                     return '<center><i class="fa fa-remove"></i></center>';
    //                 }
    //             })
    //             ->addColumn('edit', function ($value) use ($user){
    //                 if($user->hasAnyAccess(['programme.edit', 'admin'])){
    //                     return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('programme/'.$value->programId.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Channel"><i class="fa fa-pencil"></i></a></center>';
    //                 }else{
    //                     return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
    //                 }
                        
    //             })

    //             ->addColumn('bulk-update', function ($value) use ($user){
    //                 if($user->hasAnyAccess(['programme.edit', 'admin'])){
    //                     return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('programme/'.$value->programId.'/policy').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Channel"><i class="fa fa-universal-access"></i></a></center>';
    //                 }else{
    //                     return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
    //                 }
                        
    //             })
    //             ->make(true);
    //     // }catch (\Throwable $exception){
    //     //     $exceptionId = rand(0, 99999999);
    //     //     Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
    //     //     return Datatables::of(collect())->make(true);
    //     // }
    // }
  
    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $kiki_service = KikiService::find($id);
        if ($kiki_service) {
            $kiki_service->status = $state;
            $kiki_service->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    public function deleteSlider(Request $request)
    {
        $id = $request->id;
        $kiki_service = KikiService::find($id);
        if ($kiki_service) {
            $kiki_service->status = 0;
            $kiki_service->save();
            
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
