<?php
namespace ProjectManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Config;
use File;
use Illuminate\Http\Request;
use Log;
use ProjectManage\Models\Project;
use Sentinel;
use Datatables;
use Response;
use DB;

class ProjectController extends Controller {

    private $projectmagePath ;

    public function __construct()
    {
        $this->projectmagePath = Config::get('filePaths.project-images');
    }

    public function index()
    {
        return view( 'ProjectManage::list' );
    }

    public function getProjects(Request $request)
    {

        $limit = $request->input('length');
        $start = $request->input('start');
        $orderNo = $request->input('order.0.column');

        $search = $request->input('search.value');

        if (!$search) {
            try {
                $user = Sentinel::getUser();
                return Datatables::of(
                    Project::leftJoin('products', function ($q){
                            $q->on('projects.id', '=', 'products.project_id');
                            $q->where('products.status', '=', 1);
                        })
                        ->groupBy('projects.id')
                        ->select('projects.id', 'projects.name', 'projects.code', 'projects.status', DB::raw("COUNT(products.id) as products_count"))
                        ->with('getProducts')
                )
                    ->editColumn('status', function ($value) {
                        return $value->status == 1 ? 'Activated' : 'Inactivated';
                    })
                    ->editColumn('products_count', function ($value) {
                        return $value->products_count;
                    })
                    ->editColumn('toggle-status', function ($value) {
                        if ($value->status == 1) {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue project-status-toggle " data-id="' . $value->id . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                        } else {
                            return '<center><a href="javascript:void(0)" form="noForm" class="blue project-status-toggle " data-id="' . $value->id . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                        }
                    })
                    ->addColumn('edit', function ($value) use ($user) {
                        if ($user->hasAnyAccess(['admin.projects.show', 'admin']))
                            return '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.projects.show', $value->id) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Project"><i class="fa fa-pencil"></i></a></center>';
                    })
                    ->make(true);
            } catch (\Throwable $exception) {
                $exceptionId = rand(0, 99999999);
                Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
                return Datatables::of(collect())->make(true);
            }
        } else {

            $user = Sentinel::getUser();
            $data = Project::leftJoin('products', function ($q){
                    $q->on('projects.id', '=', 'products.project_id');
                    $q->where('products.status', '=', 1);
                })
                ->groupBy('projects.id')
                ->select('projects.id', 'projects.name', 'projects.code', 'projects.status', DB::raw("COUNT(products.id) as products_count"))
                ->with('getProducts')->where(function ($q) use ($search) {
                $q->where('projects.name', 'like', '%' . $search . '%')
                    ->orWhere('projects.code', 'like', '%' . $search . '%');
            });

            $totalData = count($data->get());
            $totalFiltered = $totalData;

            $data = $data->offset($start)->limit($limit)
                ->get();

            if($request->input('order.0.column') == 3){
                if($request->input('order.0.dir') == "asc"){
                    $data = $data->sortBy(function($filteredData){
                        return $filteredData->products_count;
                    })->values()->all();
                }
                else{
                    $data = $data->sortByDesc(function($filteredData){
                        return $filteredData->products_count;
                    })->values()->all();
                }

                $data = collect($data);
            }

            $jsonList = array();
            $i = 1;
            foreach ($data as $key => $project) {

                $status = $project->status == 1 ? 'Activated' : 'Inactivated';

                $toggleStatus = null;
                if ($project->status == 1) {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue project-status-toggle " data-id="' . $project->id . '"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                } else {
                    $toggleStatus = '<center><a href="javascript:void(0)" form="noForm" class="blue project-status-toggle " data-id="' . $project->id . '"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                }

                $editField = null;
                if ($user->hasAnyAccess(['admin.projects.show', 'admin'])) {
                    $editField = '<center><a href="#" class="blue" onclick="window.location.href=\'' . route('admin.projects.show', $project->id) . '\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Project"><i class="fa fa-pencil"></i></a></center>';
                }


                $dd = array(
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'products_count' => $project->products_count,
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
        return view('ProjectManage::add');
    }

    function store(Request $request){
        try {
            $project = new Project;
            $project->name = $request->get('name');
            $project->description = $request->get('description');
            $project->status = 1;

            $imageController = new ImageController();
            $file = $request->file('image');
            if($file){
                $ext = $file->getClientOriginalExtension();
                $fileName = 'project-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $imageController->upload($this->projectmagePath, $file, $fileName, null);/*id not used in imageController*/
                ///$this->uploadImage($aImage, $this->projectmagePath, $fileName);
                $project->image = $fileName;
            }
            $project->save();


            $project->code = Project::$projectCodePrefix . $project->id;
            $project->save();

            return redirect(route('admin.projects.index'))
                ->with([
                    'success' => true,
                    'success.message' => "Successfully added new project. Project code : " . $project->code,
                    'success.title' => 'Success']);
        } catch (\Throwable $exception) {
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return redirect(route('admin.projects.create'))
                ->with([
                    'error' => true,
                    'error.message' => 'Error adding new project. Please try again. Ex: ' . $exceptionId,
                    'error.title' => 'Oops !!'
                ]);
        }
    }

    function uploadImage($file, $path, $fileName){
        if (!file_exists($path)) {
            Log::info("Creating directory ".$path);
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);
    }

    function show($projects){
        try {
            $project = Project::find($projects);
            $image = [];
            $image_config = [];
            if($project->image){
                array_push($image, "<img style='height:190px' src='" .  Config('constants.bucket.url').Config('filePaths.front.project').$project->image . "'>");
                array_push($image_config, array(
                    'caption' => '',
                    'type' => 'image',
                    'key' => $project->id,
                    'url' => url('admin/projects/image-delete'),
                ));
            }

            return view('ProjectManage::edit', compact('project', 'image', 'image_config'));
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return redirect(route('admin.projects.index'))->with([
                'error' => true,
                'error.message'=> "Please try again. Ex: ".$exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }


    function update(Request $request, $projects){
        try {
            $project = Project::find($projects);
            $project->name = $request->get('name');
            $project->description = $request->get('description');
            if($request->file('image')){
                $imageController = new ImageController();
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $fileName = 'project-image-' . rand(0, 999999) . '-' . date('YmdHis') . '.' . $ext;
                $path = $imageController->upload($this->projectmagePath, $file, $fileName, null);/*id not used in imageController*/
                ///$this->uploadImage($aImage, $this->projectmagePath, $fileName);
                $project->image = $fileName;
               // Log::info("Creating directory ".$fileName." path- " .$path);
            }else if($request->has('image_removed') && $request->get('image_removed') == 1){
                $project->image =  null;
            }

            $project->save();

            return redirect(route('admin.projects.index'))->with(['success' => true,
                'success.message' => "Successfully updated project",
                'success.title' => 'Success']);
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage(), $request->all());
            return redirect(route('admin.projects.index', $projects))->with([
                'error' => true,
                'error.message'=> 'Error updating project. Please try again. Ex: '. $exceptionId,
                'error.title' => 'Oops !!'
            ]);
        }
    }

    function toggleStatus($projects){
        try {
            $project = Project::find($projects);
            $project->status = $project->status == 1 ? 0 : 1;
            $project->save();
        }catch (\Throwable $exception){
            $exceptionId = rand(0, 99999999);
            Log::error("Ex " . $exceptionId . " | Error in " . __CLASS__ . "::" . __FUNCTION__ .":" .$exception->getLine()." | " . $exception->getMessage());
            return response()->json("error : ". $exceptionId, 403);
        }
    }

    public function imageDelete(Request $request)
    {
        if ($request->has('key')) {
            $id = $request->get('key');
            $project = Project::find($id);
            $project->image = null;
            $project->save();

            // activity
            /*if (Sentinel::getUser()) {
                parent::activity_create('Deleted product image.');
            }*/
            return 2;
        }
        return 1;
    }
}
