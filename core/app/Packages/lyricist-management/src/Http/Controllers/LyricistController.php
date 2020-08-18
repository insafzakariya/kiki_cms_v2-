<?php
namespace LyricistManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Config;
use File;
use Illuminate\Http\Request;
use Log;
use LyricistManage\Models\Lyricist;
use Sentinel;
use Datatables;
use Response;
use DB;

class LyricistController extends Controller {

    private $lyricistImagePath ;

    /**
     * @var ImageController
     */
    private $imageController;

    public function __construct()
    {
        $this->lyricistImagePath = Config::get('filePaths.lyricist-images');
        $this->imageController = new ImageController();
    }

    public function index()
    {
        return view( 'LyricistManage::list' );
    }

    public function getLyricists(Request $request)
    {

        $limit = $request->input('length');
        $start = $request->input('start');

        $search = $request->input('search.value');

        if (!$search) {
            try {
                $user = Sentinel::getUser();
                return Datatables::of(
                    Lyricist::leftJoin('songs', function ($q){
                            $q->on('songs_writers.writerId', '=', 'songs.writerId');
                            $q->where('songs.status', '=', 1);
                        })
                        ->groupBy('songs_writers.writerId')
                        ->select('songs_writers.writerId', 'songs_writers.name', 'songs_writers.description', 'songs_writers.status', DB::raw("COUNT(songs.songId) as songs_count"))
                        ->with('songs')
                )
                    ->editColumn('status', function ($value) {
                        return $value->status == 1 ? 'Activated' : 'Inactivated';
                    })
                    ->editColumn('songs_count', function ($value) {
                        return $value->songs_count;
                    })
                    ->editColumn('toggle-status', function ($value) {
                        if ($value->status == 1) {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue lyricist-status-toggle " data-id="' . $value->writerId . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                        } else {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue lyricist-status-toggle " data-id="' . $value->writerId . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                        }
                    })
                    ->addColumn('edit', function ($value) use ($user) {
                        if ($user->hasAnyAccess(['admin.lyricists.show', 'admin']))
                            return '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.lyricists.show', $value->writerId) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Lyricist"><i class="fa fa-pencil"></i></a></center>';
                    })
                    ->make(true);
            } catch (\Throwable $exception) {
                $exceptionId = rand(0, 99999999);
                Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
                return Datatables::of(collect())->make(true);
            }
        } else {

            $user = Sentinel::getUser();
            $data = Lyricist::leftJoin('songs', function ($q){
                        $q->on('songs_writers.writerId', '=', 'songs.writerId');
                        $q->where('songs.status', '=', 1);
                    })
                        ->groupBy('songs_writers.writerId')
                        ->select('songs_writers.writerId', 'songs_writers.name', 'songs_writers.description', 'songs_writers.status', DB::raw("COUNT(songs.songId) as songs_count"))
                        ->with('songs')->where(function($q) use ($search) {
                        $q->where('songs_writers.name', 'like', '%'.$search.'%');
                    });

            $totalData = count($data->get());
            $totalFiltered = $totalData;

            $data = $data->offset($start)->limit($limit)
                ->get();

            $jsonList = array();
            $i=1;
            foreach ($data as $key => $writer) {

                $status = $writer->status == 1 ? 'Activated' : 'Inactivated';

                $toggleStatus = null;
                if ($writer->status == 1) {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue lyricist-status-toggle " data-id="' . $writer->writerId . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                } else {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue lyricist-status-toggle " data-id="' . $writer->writerId . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                }

                $editField = null;
                if ($user->hasAnyAccess(['admin.lyricists.show', 'admin']))
                    $editField = '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.lyricists.show', $writer->writerId) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Lyricist"><i class="fa fa-pencil"></i></a></center>';

                $dd = array(
                    'writerId' => $writer->writerId,
                    'name' => $writer->name,
                    'songs_count' => $writer->songs_count,
                    'status' => $status,
                    'toggle-status' => $toggleStatus,
                    'edit' => $editField,
                );

                array_push($jsonList, $dd);
            }

            return Response::json(array(
                'data' => $jsonList,
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered)
            ));

            }

        }


    function create(){
        return view('LyricistManage::add');
    }

    function store(Request $request){
        try {
            $lyricist = new Lyricist;
            $lyricist->name = $request->get('name');
            $lyricist->description = $request->get('description');
            if($request->get('tags') != null){
                $lyricist->search_tag = implode(",", $request->get('tags'));
            }
            $lyricist->status = 1;

            if($request->hasFile('image')) {
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'lyricist-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->Upload($this->lyricistImagePath, $aImage, $fileName, "-");
                $lyricist->image = $fileName;
            }
            $lyricist->save();

            return redirect(route('admin.lyricists.index'))->with(['success' => true,
                'success.message' => "Successfully added new lyricist",
                'success.title' => 'Success']);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.lyricists.create'))->with([
                'error' => true,
                'error.message'=> 'Error adding new lyricist. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function show($lyricists){
        try {
            $lyricist = Lyricist::find($lyricists);
            if(in_array($lyricist->search_tag, array("", null))){
                $lyricist->search_tag = array();
            }else{
                $lyricist->search_tag = explode(',', $lyricist->search_tag);
            }
            $image = [];
            $image_config = [];
            if($lyricist->image){
                array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url').Config('filePaths.front.lyricist').$lyricist->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $lyricist->id,
                ));
            }
            return view('LyricistManage::edit', compact('lyricist', 'image_config', 'image'));
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.lyricists.index'))->with([
                'error' => true,
                'error.message'=> "Please try again. Ex: ".$exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }


    function update(Request $request, $lyricists){
        try {
            $lyricist = Lyricist::find($lyricists);
            $lyricist->name = $request->get('name');
            $lyricist->description = $request->get('description');
            $lyricist->search_tag = implode(",", $request->get('tags'));

            if($request->hasFile('image')){
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'lyricist-image-'.rand(0, 999999).'-'. date('YmdHis') . '.' . $ext;
                $filePath = $this->imageController->Upload($this->lyricistImagePath, $aImage, $fileName, "-");
                $lyricist->image = $fileName;
            }else if($request->has('image_removed') && $request->get('image_removed') == 1){
                $lyricist->image =  null;
            }

            $lyricist->save();

            return redirect(route('admin.lyricists.index'))->with(['success' => true,
                'success.message' => "Successfully updated lyricist",
                'success.title' => 'Success']);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.lyricists.index', $lyricists))->with([
                'error' => true,
                'error.message'=> 'Error updating lyricist. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function toggleStatus($lyricists){
        try {
            $lyricist = Lyricist::find($lyricists);
            $lyricist->status = $lyricist->status == 1 ? 0 : 1;
            $lyricist->save();
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return response()->json("error : ". $exceptionId, 403);
        }
    }

    public function lyricsSearch(Request $request){

        $search = $request->get('term');
        $writers = [];
        if($search){
            $writers =  Lyricist::where('name', 'like', '%' . $search . '%')->where('status', 1)->limit(20)->orderBy('name', 'asc')->get();
        }

        return $writers;

    }
}
