<?php

namespace RadioChannel\Http\Controllers;

use App\Http\Controllers\Controller;
use Config;
use File;
use Illuminate\Http\Request;
use Log;
use RadioChannel\Models\RadioChannel;
use Sentinel;
use Datatables;
use Response;
use App\Http\Controllers\ImageController;

class RadioChannelController extends Controller
{
    /**
     * @var string
     */
    private $radioChannelLogoPath;

    public function __construct()
    {
        $this->radioChannelLogoPath = Config::get('filePaths.radio-channel-logo');
    }

    public function index()
    {
        return view('RadioChannel::list');
    }

    public function getChannels(Request $request)
    {

        $limit = $request->input('length');
        $start = $request->input('start');

        $search = $request->input('search.value');

        if (!$search) {
            try {
                $user = Sentinel::getUser();
                return Datatables::of(
                    RadioChannel::select('id', 'name', 'status')
                )
                    ->editColumn('status', function ($value) {
                        return $value->status == 1 ? 'Activated' : 'Inactivated';
                    })
                    ->editColumn('toggle-status', function ($value) {
                        if ($value->status == 1) {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue radio-channels-status-toggle " data-id="' . $value->id . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                        } else {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue radio-channels-status-toggle " data-id="' . $value->id . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                        }
                    })
                    ->addColumn('edit', function ($value) use ($user) {
                        if ($user->hasAnyAccess(['admin.radio-channels.show', 'admin']))
                            return '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.radio-channels.show', $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Radio Channels"><i class="fa fa-pencil"></i></a></center>';
                    })
                    ->make(true);
            } catch (\Throwable $exception) {
                $exceptionId = rand(0, 99999999);
                Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
                return Datatables::of(collect())->make(true);
            }
        } else {

            $user = Sentinel::getUser();
            $data = RadioChannel::where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });

            $totalData = count($data->get());
            $totalFiltered = $totalData;

            $data = $data->offset($start)->limit($limit)
                ->get();

            $jsonList = array();
            $i=1;
            foreach ($data as $key => $radio) {

                $status = $radio->status == 1 ? 'Activated' : 'Inactivated';

                $toggleStatus = null;
                if ($radio->status == 1) {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue radio-channels-status-toggle " data-id="' . $radio->id . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                } else {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue radio-channels-status-toggle " data-id="' . $radio->id . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                }

                $editField = null;
                if ($user->hasAnyAccess(['admin.radio-channels.show', 'admin'])) {
                    $editField = '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.radio-channels.show', $radio->id) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Radio Channels"><i class="fa fa-pencil"></i></a></center>';
                }

                $dd = array(
                    'id' => $radio->id,
                    'name' => $radio->name,
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

    function create()
    {
        return view('RadioChannel::add');
    }

    function store(Request $request)
    {
        try {
            $radioChannel = new RadioChannel();
            $radioChannel->name = $request->get('name');
            $radioChannel->url = $request->get('url');
            $radioChannel->description = $request->get('description');
            $radioChannel->status = 1;

            $radioChannel->save();

            if ($request->hasFile('image')) {
                $image = new ImageController();
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'radios-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $image->upload($this->radioChannelLogoPath, $aImage, $fileName, $radioChannel->id);

                $radioChannel->update([
                    'image' => $fileName
                ]);

            }
            return redirect(route('admin.radio-channels.index'))->with(['success' => true,
                'success.message' => "Successfully added new radio channels",
                'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.radio-channels.create'))->with([
                'error' => true,
                'error.message' => 'Error adding new radio channels. Please try again. Ex: ' . $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function uploadImage($file, $path, $fileName)
    {
        if (!file_exists($path)) {
            Log::info("Creating directory " . $path);
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);
    }

    function show($radioChannels)
    {
        try {
            $radioChannel = RadioChannel::find($radioChannels);
            $image = [];
            $image_config = [];
            if($radioChannel->image){
                array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url').Config('filePaths.front.radio-channel').$radioChannel->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $radioChannel->id,
                    'url' => url('admin/radio-channels/image-delete'),
                ));
            }

            return view('RadioChannel::edit', compact('radioChannel', 'image', 'image_config'));
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.radio-channels.index'))->with([
                'error' => true,
                'error.message' => "Please try again. Ex: " . $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }


    function update(Request $request, $radioChannels)
    {
        try {
            $radioChannel = RadioChannel::find($radioChannels);
            $radioChannel->name = $request->get('name');
            $radioChannel->description = $request->get('description');
            $radioChannel->url = $request->get('url');

            if ($request->file('image')) {
                $image = new ImageController();
                $aImage = $request->file('image');
                $ext = $aImage->getClientOriginalExtension();
                $fileName = 'radios-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $image->upload($this->radioChannelLogoPath, $aImage, $fileName, $radioChannel->id);
                $radioChannel->image = $fileName;
            }else if($request->has('image_removed') && $request->get('image_removed') == 1){
                $radioChannel->image =  null;
            }

            $radioChannel->save();

            return redirect(route('admin.radio-channels.index'))
                ->with([
                    'success' => true,
                    'success.message' => "Successfully updated radio channels",
                    'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.radio-channels.index', $radioChannels))
                ->with([
                    'error' => true,
                    'error.message' => 'Error updating radio channels. Please try again. Ex: ' . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }

    function toggleStatus($radioChannels)
    {
        try {
            $radioChannel = RadioChannel::find($radioChannels);
            $radioChannel->status = $radioChannel->status == 1 ? 0 : 1;
            $radioChannel->save();
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return response()->json("error : " . $exceptionId, 403);
        }
    }

    public function imageDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $songComposer = RadioChannel::find($id);
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
}
