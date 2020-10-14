<?php
namespace MoodManage\Http\Controllers;

use App\Http\Controllers\Controller;
use Config;
use Datatables;
use File;
use Illuminate\Http\Request;
use Log;
use MoodManage\Models\Mood;
use Sentinel;

class MoodController extends Controller {

    public function __construct()
    {
    }

    public function index()
    {
        return view( 'MoodManage::list' );
    }

    public function getMoods(){
        try {
            $user = Sentinel::getUser();
            return Datatables::usingCollection(
                Mood::select('id', 'name', 'description', 'status')->get()
            )
                ->editColumn('status', function ($value){
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                ->editColumn('toggle-status', function ($value){
                    if($value->status == 1){
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue mood-status-toggle " data-id="'.$value->id.'"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    }else{
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue mood-status-toggle " data-id="'.$value->id.'"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                })
                ->addColumn('edit', function ($value) use ($user){
                    if($user->hasAnyAccess(['admin.moods.show', 'admin']))
                        return '<center><a href="#" class="blue" onclick="window.location.href=\''.route('admin.moods.show', $value->id).'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Mood"><i class="fa fa-pencil"></i></a></center>';
                })
                ->make(true);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return Datatables::of(collect())->make(true);
        }
    }

    function create(){
        return view('MoodManage::add');
    }

    function store(Request $request){
        try {
            $mood = new Mood;
            $mood->name = $request->get('name');
            $mood->description = $request->get('description');
            $mood->tags = json_encode($request->get('tags'));
            $mood->status = 1;

            $mood->save();

            return redirect(route('admin.moods.index'))->with(['success' => true,
                'success.message' => "Successfully added new mood",
                'success.title' => 'Success']);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.moods.create'))->with([
                'error' => true,
                'error.message'=> 'Error adding new mood. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function show($moods){
        try {
            $mood = Mood::find($moods);
            $mood->tags = json_decode($mood->tags, true);
            return view('MoodManage::edit', compact('mood'));
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.moods.index'))->with([
                'error' => true,
                'error.message'=> "Please try again. Ex: ".$exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }


    function update(Request $request, $moods){
        try {
            $mood = Mood::find($moods);
            $mood->name = $request->get('name');
            $mood->description = $request->get('description');
            $mood->tags = json_encode($request->get('tags'));

            $mood->save();

            return redirect(route('admin.moods.index'))->with(['success' => true,
                'success.message' => "Successfully updated mood",
                'success.title' => 'Success']);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.moods.index', $moods))->with([
                'error' => true,
                'error.message'=> 'Error updating mood. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function toggleStatus($moods){
        try {
            $mood = Mood::find($moods);
            $mood->status = $mood->status == 1 ? 0 : 1;
            $mood->save();
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return response()->json("error : ". $exceptionId, 403);
        }
    }
}
