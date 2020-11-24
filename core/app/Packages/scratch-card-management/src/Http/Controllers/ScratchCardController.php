<?php

namespace ScratchCardManage\Http\Controllers;


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
use ScratchCardManage\Models\Package;
use ScratchCardManage\Models\ScratchCards;
use ScratchCardManage\Models\ScratchCardsCodes;
use Sentinel;



class ScratchCardController extends Controller
{



    public function __construct()
    {
        
    }
    private function generateCodes($quantity,$card_id)
    {
       $card_list=array();
        for($i=0;$i<$quantity;$i++){
            
            $total_length = 16;
            $time_value = time () + mt_rand ( 1000, 99999 );
            $time_value_length = strlen ( $time_value );
            $constant_value_length = 1; // For 2
            $incrementer_value_length = strlen ( $i );
            
            $balance_length = $total_length - ($time_value_length + $constant_value_length + $incrementer_value_length);       
            $min_mt = pow ( 10, ($balance_length - 1) );
            $max_mt = pow ( 10, $balance_length ) - 1;
            $mt_random_value = mt_rand ( $min_mt, $max_mt );
            
            $card_code = $time_value . $mt_random_value . "2" . $i;
            
            $card_rec = array(
                    'CardID' => $card_id,
                    'CardCode' => $card_code							
            );
            array_push($card_list,$card_rec);
                                    
        }
        ScratchCardsCodes::insert($card_list);
    }

    public function index()
    {
        $packages=Package::where('Status',1)->get();
        return view('ScratchCardManage::add')->with(['packages'=>$packages]);
    }
 
    public function store(Request $request)
    {
        // return $request->all();
        $card=ScratchCards::create([
            'PackageID'=>$request->get('package'),
            'CardType'=>$request->get('type'),
            'ActivityStartDate'=>$request->get('start_date'),
            'ActivityEndDate'=>$request->get('end_date')
      
        ]);
        if($card){
            if($request->get('type')==1){
                $this->generateCodes(1,$card->CardID);
            }else if($request->get('type')==2){
                $this->generateCodes($request->get('card_count'),$card->CardID);
            }
            
        }
    }
    // Programme Slider Edit View Load
    public function editView($id)
    {
        $exsist_programme_slider=ProgrammeSlider::find($id);
       
        $thumb_image = [];
        $thumb_image_config = [];

        if($exsist_programme_slider){
        
            array_push($thumb_image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.programme') . $exsist_programme_slider->image_path . "'>");
            array_push($thumb_image_config, array(
                'caption' => '',
                'type' => 'image',
                'key' => 0,
                'url' => url('programme/image-delete'),
            ));
 
        
            return view('ProgrammeSliderManage::edit')
            ->with(
                [
                'exsist_programme_slider'=>$exsist_programme_slider,
                'thumb_image'=>$thumb_image,
                'thumb_image_config'=>$thumb_image_config
                ]
            );

        }else{
            return "Programme Slider Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {
        //   return $request->all();
      
        $exsist_programme_slider=ProgrammeSlider::find($id);

        $exsist_programme_slider->name=$request->name;
        $exsist_programme_slider->programID=$request->programme;
        $exsist_programme_slider->start_date_time=$request->start_date;
        $exsist_programme_slider->end_date_time=$request->end_date;
        
        
        if($exsist_programme_slider){
        
            if($request->hasFile('thumb_image')) {
                $cover_images=$request->file('thumb_image');

                foreach ($cover_images as $key => $aImage) {
                    $ext = $aImage->getClientOriginalExtension();
                    $fileName = 'programme-thumb-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                    $filePath = $this->imageController->Upload($this->programmeSliderImagePath, $aImage, $fileName, "-");
                    $exsist_programme_slider->image_path=$fileName;;
                    
                }
            }else if($request->has('thumb_image_removed') && $request->get('thumb_image_removed') == 1){
                $exsist_programme_slider->image_path="";
            }
            $exsist_programme_slider->save();

            return redirect('programme-slider/'.$id.'/edit')->with(['success' => true,
            'success.message' => 'Programme Slider Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('programme-slider/'.$id.'/edit')->with([
                'error' => true,
                'error.message'=> 'Error adding Slidere Edit. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
      
       
    }
    public function listView()
    {
        $sliders=ProgrammeSlider::with(['getProgramme'])->orderBy('displayOrder', 'ASC')->where('status','!=',0)->get();
        return view('ProgrammeSliderManage::list',compact('sliders'));
    }
    public function updateOrder(Request $request)
    {
        foreach ($request->order as $order) {
            ProgrammeSlider::where('ID',$order['id'])->update(['displayOrder' => $order['position']]);
            
        }
        
        return response('Update Successfully.', 200);
    }

    public function listJson()
    {
        // try {
            $user = Sentinel::getUser();
            return Datatables::usingCollection(
                Programme::select('programId', 'programName', 'programmeName_si','programmeName_ta', 'kids','status')->get()
            )
                ->editColumn('status', function ($value){
                    if($value->status==1){
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="'.$value->programId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    }else{
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="' . $value->programId . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
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
                    if($user->hasAnyAccess(['programme.edit', 'admin'])){
                        return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('programme/'.$value->programId.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Channel"><i class="fa fa-pencil"></i></a></center>';
                    }else{
                        return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
                    }
                        
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
