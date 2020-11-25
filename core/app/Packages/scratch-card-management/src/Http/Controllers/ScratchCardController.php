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
            return redirect('scratch-card/add')->with(['success' => true,
            'success.message' => 'Scratch Card Created successfully!',
            'success.title' => 'Well Done!']);
        }else{
            return redirect('scratch-card/add')->with([
                'error' => true,
                'error.message'=> 'Error adding new Scratch Card. Please try again.',
                'error.title' => 'Oops !!'
            ]);
        }
    }
    // Scrach Card  Edit View Load
    public function editView($id)
    {
        $exsist_scratch_card=ScratchCards::with(['getCodes'])->find($id);
        
        if($exsist_scratch_card){   
            $scratch_codes_count=ScratchCardsCodes::where('CardID',$exsist_scratch_card->CardID)->count();
            $packages=Package::where('Status',1)->get(); 
            return view('ScratchCardManage::edit')
            ->with(
                [
                'exsist_scratch_card'=>$exsist_scratch_card,
                'packages'=>$packages,
                'scratch_codes_count'=>$scratch_codes_count
                ]
            );

        }else{
            return "Scrath Card Not Found.";
        }
       
    }

    public function edit(Request $request,$id )
    {
        
        
        $exsist_scratch_card=ScratchCards::find($id);
        if($exsist_scratch_card){
            $exsist_scratch_card->PackageID=$request->package;
            $exsist_scratch_card->ActivityStartDate=$request->start_date;
            $exsist_scratch_card->ActivityEndDate=$request->end_date;
            $exsist_scratch_card->save();
    
            return redirect('scratch-card/'.$id.'/edit')->with(['success' => true,
                'success.message' => 'Scratch Card Created successfully!',
                'success.title' => 'Well Done!']);
           
        }
       
       
    }
    public function listView()
    {
        $scratchCard=ScratchCards::where('status','!=',0)->get();
        return view('ScratchCardManage::list');
    }

    public function listJson()
    {
        $user = Sentinel::getUser();
        $query=ScratchCards::with(['getPackage'])->where('tbl_scratch_cards.status',1)->orderBy('CardID','Desc')->select('tbl_scratch_cards.*');

            return Datatables::eloquent($query)

            ->addColumn('package', function (ScratchCards $value) {

                return  $value->getPackage ? $value->getPackage->Description : "-";

            })
            ->addColumn('type', function (ScratchCards $value) {
                if($value->CardType ==1){
                    return "Single";
                }else if($value->CardType ==2){
                    return "Bulk";
                }
               

            })

            ->addColumn('viewCode', function (ScratchCards $value) use ($user){
                if($user->hasAnyAccess(['scratch-card.edit', 'admin'])){
                    return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('scratch-card/'.$value->CardID.'/code').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Episode"><i class="fa fa-eye"></i></a></center>';
                }else{
                    return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-eye"></i></a></center>';
                }
                    
            })
            ->addColumn('edit', function (ScratchCards $value) use ($user){
                if($user->hasAnyAccess(['scratch-card.edit', 'admin'])){
                    return '<center><a href="#" class="blue" onclick="window.location.href=\''.url('scratch-card/'.$value->CardID.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Episode"><i class="fa fa-pencil"></i></a></center>';
                }else{
                    return '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a></center>';
                }
                    
            })
            ->editColumn('delete', function ($value){
                if($value->status==1){
                    return '<center><a href="javascript:void(0)" form="noForm" class="blue card-delete-toggle " data-id="'.$value->CardID.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-trash"></i></a></><center>';
                }else{
                    return '<center><a href="javascript:void(0)" form="noForm" class="blue card-delete-toggle " data-id="' . $value->CardID . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-trash"></i></a></><center>';
                }
               
            })
            ->make(true);
    }
    
    public function codeListJson(Request $request,$id )
    {
        
        // return $request->all('card_ID');
        $user = Sentinel::getUser();
        $query=ScratchCardsCodes::where('CardID',$id)->select();

            return Datatables::eloquent($query)
            ->addColumn('currentStatus', function (ScratchCardsCodes $value) {
                if($value->CardStatus ==1){
                    return "Active";
                }else if($value->CardStatus ==2){
                    return "Used";
                }
               

            })
            ->make(true);
    }

    public function codeView(Request $request,$id)
    {
        return view('ScratchCardManage::codeList')->with(['id'=>$id]);
    }
    public function delete(Request $request)
    {
        $id = $request->id;
       

        $scratchCards = ScratchCards::find($id);
        if ($scratchCards) {
            $scratchCards->Status = 0;
            $scratchCards->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
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
