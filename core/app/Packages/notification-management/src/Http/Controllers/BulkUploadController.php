<?php


namespace NotificationManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use LyricistManage\Models\Lyricist;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Log;
use DB;
use Datatables;
use Response;
use Session;
use Sentinel;
use Illuminate\Support\Facades\Validator;
use NotificationManage\Models\UserGroup;
use NotificationManage\Models\UserGroupsViewer;


class BulkUploadController extends Controller
{
    public function listView()
    {
        return view('NotificationManage::user-group-list');
    }


    public function userGroupUpload()
    {

        try {
            return view('NotificationManage::bulk-upload')
                ->with([]);
        } catch (Exception $exception) {
            Log::error("User Group upload | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return view('errors.404');
        }
    }

    public function upload(Request $request)
    {

        try {
            DB::beginTransaction();


            $file = $request->file('upload_file');
            if (File::exists($file)) {
                $path = $file->getRealPath();
                $userData = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                })->setDateColumns(['uploaded_date'])->get();

                $row = 0;
                $errors = [];
                $data = [];
                foreach ($userData as $key => $rowData) {
                    if (!$rowData->filter()->isEmpty()) {
                        $row++;
                        $newData = [
                            'viewerId' => $rowData['viewer_id']
                        ];
                        $messages = [
                            'viewerId.required' => "ViewerId Required. Row No-{$row} "
                        ];
                        $rules = [
                            'viewerId' => 'required|integer'
                        ];

                        $validator = Validator::make($newData, $rules, $messages);
                        if ($validator->fails()) {
                            return response()->json(
                                [
                                    'errors' => $validator->errors(),
                                ],
                                422
                            );
                        }
                        array_push($data, $newData);
                    }
                }
                if ($data) {
                    $bulkUpload = UserGroup::create([
                        'name' => $request->get('name'),
                        'row_count' => $row,
                        'file_name' => $file->getClientOriginalName(),
                        'status' => 1,
                        'description' => $request->get('description')
                    ]);

                    Log::info($bulkUpload->id);
                    $userGroupId = $bulkUpload->id;

                    foreach ($data as $key => $row) {
                        $userGroupViewer = UserGroupsViewer::create([
                            'viewer_id' => $row['viewerId'],
                            'user_group_id' => $userGroupId,
                            'status' => 1
                        ]);
                    }
                    DB::commit();
                }

                return response()->json(
                    [
                        'message' => 'updated',
                    ],
                    200
                );
            } else {
                Log::error("User Group bulk upload | No file");
                return response()->json(
                    [
                        'message' => 'No file',
                    ],
                    404
                );
            }
        } catch (Exception $exception) {
            DB::rollback();
            Log::error("User Group bulk upload | Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return response()->json(
                [
                    'message' => 'Something went wrong',
                ],
                500
            );
        }
    }

    public function jsonList(Request $request)
    {

        Log::info('called');

        try {

            $searchField = $request->get('field_name');
            $searchParam = $request->get('search_param');

            //return $searchParam.'---'.$searchField;

            $dataQuery = UserGroup::select([
                'id',
                'name',
                'description',
                'file_name',
                'row_count',
                'status'
            ]);

            if ($searchField and $searchParam) {
                switch ($searchField) {
                    case 'name':
                        $dataQuery->where('name', 'like', '%' . $searchParam . '%');
                        break;
                    case 'description':
                        $dataQuery->where('description', 'like', '%' . $searchParam . '%');
                        break;
                    case 'file_name':
                        $dataQuery->where('file_name', 'like', '%' . $searchParam . '%');
                        break;
                    default:
                }
            }

            $dataTables = Datatables::eloquent($dataQuery)

                ->addColumn('action', function ($value) {
                    if ($value->status == 1) {
                        return '<center>
                            <a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->id . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate">
                            <i class="fa fa-toggle-on"></i>
                            </a>
                            </center>';
                    } else {
                        return '<center>
                            <a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate">
                            <i class="fa fa-toggle-off"></i>
                            </a>
                            </center>';
                    }
                });

            $dataTables->smart(false);

            Log::info('setp 1');


            //->toJson();
            return $dataTables->make(true);
        } catch (Exception $exception) {
            Log::error(" User Group list view| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return $exception->getMessage();
        }
        // Log::info('setp2');
        // $dataQuery = UserGroup::select([
        //     'id',
        //     'name',
        //     'description',
        //     'file_name',
        //     'row_count',
        //     'status'
        // ]);

        // $limit = $request->input('length');
        // $start = $request->input('start');

        // $search = $request->input('search.value');

        // $data = UserGroup::select([
        //     'id',
        //     'name',
        //     'description',
        //     'file_name',
        //     'row_count',
        //     'status'
        // ]);

        // if ($search) {
        //     $data = UserGroup::where(function ($q) use ($search) {
        //         $q->orWhere('name', 'like', '%' . $search . '%')
        //             ->orWhere('description', 'like', '%' . $search . '%')
        //             ->orWhere('file_name', 'like', '%' . $search . '%');
        //     });
        // }

        // $totalData = count($data->get());
        // $totalFiltered = $totalData;

        // $data = $data->orderBy('id', 'desc')
        //     ->offset($start)->limit($limit)
        //     ->get();

        // $jsonList = array();
        // $i = 1;
        // foreach ($data as $key => $user_group) {

        //     $dd = array();

        //     if ($user_group->id != "") {
        //         array_push($dd, $user_group->id);
        //     } else {
        //         array_push($dd, "-");
        //     }

        //     if ($user_group->name != "") {
        //         array_push($dd, $user_group->name);
        //     } else {
        //         array_push($dd, "-");
        //     }

        //     if ($user_group->description != "") {
        //         array_push($dd, $user_group->description);
        //     } else {
        //         array_push($dd, "-");
        //     }

        //     if ($user_group->file_name != "") {
        //         array_push($dd, $user_group->file_name);
        //     } else {
        //         array_push($dd, "-");
        //     }

        //     if ($user_group->row_count != "") {
        //         array_push($dd, $user_group->row_count);
        //     } else {
        //         array_push($dd, "-");
        //     }

        //     $status = null;
        //     if ($user_group->status == 1) {
        //         $status = "ACTIVE";
        //     } else {
        //         $status = "INACTIVE";
        //     }

        //     array_push($dd, $status);

        //     if ($user_group->status == 0 || $user_group->status == 1) {

        //         $status = null;

        //         if ($user_group->status == 1) {
        //             $checkbox = '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $user_group->id . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
        //             array_push($dd, $checkbox);
        //         } else {
        //             $checkbox = '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $user_group->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
        //             array_push($dd, $checkbox);
        //         }
        //     } else {
        //         array_push($dd, "-");
        //     }

        //     array_push($jsonList, $dd);
        //     $i++;
        // }

        // Log::info(json(array(
        //     'data' => $jsonList,
        //     "draw" => intval($request->input('draw')),
        //     "recordsTotal" => intval($totalData),
        //     "recordsFiltered" => intval($totalFiltered),
        // )));


        // return Response::json(array(
        //     'data' => $jsonList,
        //     "draw" => intval($request->input('draw')),
        //     "recordsTotal" => intval($totalData),
        //     "recordsFiltered" => intval($totalFiltered),
        // ));
    }








    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $userGroup = UserGroup::where('id', $id)->first();
        if ($userGroup) {
            $userGroup->status = $state;
            $userGroup->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }
}
