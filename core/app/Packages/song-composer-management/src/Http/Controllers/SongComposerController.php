<?php

namespace SongComposerManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Config;
use File;
use Illuminate\Http\Request;
use Log;
use Sentinel;
use SongComposerManage\Models\SongComposer;
use Datatables;

class SongComposerController extends Controller
{

    private $songComposerImagePath;


    /**
     * @var ImageController
     */
    private $imageController;

    public function __construct()
    {
        $this->songComposerImagePath = Config::get('filePaths.song-composer-images');
        $this->imageController = new ImageController();
    }

    public function index()
    {
        return view('SongComposerManage::list');
    }

    public function getSongComposers()
    {
        try {
            $user = Sentinel::getUser();

            $model = SongComposer::select('id', 'name', 'description', 'status');

            return Datatables::eloquent($model)
                ->editColumn('status', function ($value) {
                    return $value->status == 1 ? 'Activated' : 'Inactivated';
                })
                ->editColumn('songs_count', function ($value) {
                    return $value->songs()->count();
                })
                ->editColumn('toggle-status', function ($value) {
                    if ($value->status == 1) {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue song-composer-status-toggle " data-id="' . $value->id . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                    } else {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue song-composer-status-toggle " data-id="' . $value->id . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                    }
                })
                ->addColumn('edit', function ($value) use ($user) {
                    if ($user->hasAnyAccess(['admin.song-composers.show', 'admin']))
                        return '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.song-composers.show', $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Song Composer"><i class="fa fa-pencil"></i></a></center>';
                })
                ->make(true);


        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return Datatables::of(collect())->make(true);
        }
    }

    function create()
    {
        return view('SongComposerManage::add');
    }

    function store(Request $request)
    {
        try {
            $songComposer = new SongComposer();
            $songComposer->name = $request->get('name');
            $songComposer->description = $request->get('description');
            $songComposer->tags = $request->get('tags');
            $songComposer->status = 1;


            if ($request->file('image')) {
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'song-composer-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $this->imageController->Upload($this->songComposerImagePath, $aImage, $fileName, "-") . "/" . $fileName;
                $songComposer->image = $fileName;
            }

            $songComposer->save();

            return redirect(route('admin.song-composers.index'))
                ->with([
                    'success' => true,
                    'success.message' => "Successfully added new song composer",
                    'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.song-composers.index'))
                ->with([
                    'error' => true,
                    'error.message' => 'Error adding new song composer. Please try again. Ex: ' . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }

    function show($songComposers)
    {
        try {
            $songComposer = SongComposer::find($songComposers);
            $image = [];
            $image_config = [];
            if ($songComposer->image) {
                array_push($image, "<img style='height:190px' src='" . Config('constants.bucket.url') . Config('filePaths.front.song-composer') . $songComposer->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $songComposer->id,
                    'url' => url('admin/song-composers/image-delete'),
                ));
            }
            return view('SongComposerManage::edit', compact('songComposer', 'image', 'image_config'));
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.song-composers.index'))
                ->with([
                    'error' => true,
                    'error.message' => "Please try again. Ex: " . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }


    function update(Request $request, $songComposers)
    {
        try {
            $songComposer = SongComposer::find($songComposers);
            $songComposer->name = $request->get('name');
            $songComposer->description = $request->get('description');
            $songComposer->tags = $request->get('tags');
            if ($request->hasFile('image')) {
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'song-composer-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $this->imageController->Upload($this->songComposerImagePath, $aImage, $fileName, "-") . "/" . $fileName;
                $songComposer->image = $fileName;
            } else if ($request->has('image_removed') && $request->get('image_removed') == 1) {

                $songComposer->image = '';
            }

            $songComposer->save();

            return redirect(route('admin.song-composers.index')
            )->with([
                'success' => true,
                'success.message' => "Successfully updated song composer",
                'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.song-composers.index', $songComposers))
                ->with([
                    'error' => true,
                    'error.message' => 'Error updating song composer. Please try again. Ex: ' . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }

    function toggleStatus($songComposers)
    {

        try {
            $songComposer = SongComposer::find($songComposers);
            if ($songComposer) {
                $songComposer->status = $songComposer->status == 1 ? 0 : 1;
                $songComposer->save();

                return response()->json("success : Status successfully changed", 200);
            } else
                return response()->json("error : Ooops something went wrong please try again", 403);

        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return response()->json("error : " . $exception->getMessage(), 403);
        }
    }

    public function imageDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $songComposer = SongComposer::find($id);
            $songComposer->update([
                'image' => ''
            ]);

            // activity
            /*if (Sentinel::getUser()) {
                parent::activity_create('Deleted product image.');
            }*/
            return 2;
        }
        return 1;
    }

    public function composerSearch(Request $request){
        $search = $request->get('term');
        $composers = [];
        if($search){
            $composers =  SongComposer::where('name', 'like', '%' . $search . '%')
                ->where('status', 1)
                ->limit(20)
                ->orderBy('name', 'asc')
                ->get();
        }
        return $composers;
    }
}
