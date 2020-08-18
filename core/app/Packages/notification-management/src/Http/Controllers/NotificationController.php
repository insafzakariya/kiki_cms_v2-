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
use NotificationManage\Models\FcmNotification;


class NotificationController extends Controller
{
    public function listView()
    {
        return view('NotificationManage::notification-list');
    }

    public function jsonList(Request $request)
    {

        Log::info('called');

        try {

            $searchField = $request->get('field_name');
            $searchParam = $request->get('search_param');

            //return $searchParam.'---'.$searchField;

            $dataQuery = FcmNotification::select([
                'id', 'user_group', 'section', 'content_type', 'content_id', 'notification_time',
                'language', 'sinhala_title', 'english_title', 'tamil_title', 'status'
            ])->with([
                'userGroup']);

        
            
            if ($searchField and $searchParam) {
                switch ($searchField) {
                    case 'section':
                        $dataQuery->where('section', 'like', '%' . $searchParam . '%');
                        break;
                    case 'content_type':
                        $dataQuery->where('content_type', 'like', '%' . $searchParam . '%');
                        break;
                    case 'content_id':
                        $dataQuery->where('content_id', 'like', '%' . $searchParam . '%');
                        break;
                    case 'language':
                        $dataQuery->where('language', 'like', '%' . $searchParam . '%');
                        break;
                    case 'title':
                        $dataQuery->where('sinhala_title', 'like', '%' . $searchParam . '%') 
                        ->orWhere('english_title', 'like', '%' . $searchParam . '%')
                        ->orWhere('tamil_title', 'like', '%' . $searchParam . '%');
                        break;
                    default:
                }
            }

            $dataTables = Datatables::eloquent($dataQuery)
                ->addColumn('title', function ($value) {
                    $title = $value->english_title ? $value->english_title : '';
                    $title .= $value->sinhala_title ?  "<br>" . $value->sinhala_title : '';
                    $title .= $value->tamil_title ? "<br>" . $value->tamil_title : '';
                    return $title ;
                })
                ->addColumn('user_group', function ($value) {
                    return $value->userGroup ? $value->userGroup->name : '';
                })
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
                })
                ->addColumn('edit', function ($value) {
                    return '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('admin/song/step-1/' . $value->songId) . '\'" data-toggle="tooltip" data-placement="top" title="Edit Song"><i class="fa fa-pencil"></i></a></center>';
                });

            $dataTables->smart(false);

            Log::info('setp 1');


            //->toJson();
            return $dataTables->make(true);
        } catch (Exception $exception) {
            Log::error(" User Group list view| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return $exception->getMessage();
        }        
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $state = $request->state;

        $notification = FcmNotification::where('id', $id)->first();
        if ($notification) {
            $notification->status = $state;
            $notification->save();
            
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'invalid_id']);
    }

    public function addView(Request $request)
    {
        return view('NotificationManage::notification-add');
    }
}
