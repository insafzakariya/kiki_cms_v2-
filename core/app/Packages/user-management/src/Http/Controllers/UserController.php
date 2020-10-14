<?php

namespace UserManage\Http\Controllers;

ini_set('max_execution_time', 0);

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Datatables;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Permissions\Models\Permission;
use Response;
use Sentinel;
use UserManage\Http\Requests\UserRequest;
use UserManage\Models\User;
use UserRoles\Models\UserRole;
use Validator;

class UserController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Show the User add screen to the user.
     *
     * @return Response
     */
    public function addView()
    {

        $user = User::where('status', '=', 1)->get();
        $roles = UserRole::orderBy('name', 'asc')->get();
        return view('userManage::user.add')->with([
            'users' => $user,
            'roles' => $roles,

        ]);
    }

    /**
     * Add new user data to database
     * @param UserRequest $request
     * @return Redirect to menu add
     */
    public function add(UserRequest $request)
    {

        $username_submitted = User::where('username', '=', $request->get('username'))->get();
        if (isset($username_submitted[0])) {
            return redirect('user/add')->with(['error' => true,
                'error.message' => 'Already EXSIST User!',
                'error.title' => 'Try Again!']);
        } else {
            if (!empty($request->get('roles')) > 0) {
                $supervisor = User::find(1);
                $credentials = [
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('username'),
                    'confirmed' => 1,
                    'username' => $request->get('username'),
                    'password' => $request->get('password'),
                ];
                $user = Sentinel::registerAndActivate($credentials);
                $user->makeChildOf($supervisor);
                foreach ($request->get('roles') as $key => $value) {
                    $role = Sentinel::findRoleById($value);
                    $role->users()->attach($user);
                }
                return redirect('user/add')->with(['success' => true,
                    'success.message' => 'User Created successfully!',
                    'success.title' => 'Well Done!']);
            } else {
                return redirect('user/add')->with(['error' => true,
                    'error.message' => 'ROLE can not be empty!',
                    'error.title' => 'Try Again!'])->withInput($request->input());
            }
        }
    }

    /**
     * Show the user add screen to the user.
     *
     * @return Response
     */
    public function listView($type)
    {

        $roles = UserRole::where('visible', 1)->get();
        $role_user_count = array();

        return view('userManage::user.list')->with(['type' => $type]);
    }

    /**
     * Show the user add screen to the user.
     *
     * @return Response
     */
    public function jsonList(Request $request, $type)
    {
        $logged_user = Sentinel::getUser();

        //if ($request->ajax()) {
        try {

            $user = User::select([
                'id',
                'email',
                'first_name',
                'last_name',
                'status',
                DB::raw("CONCAT(users.first_name,'-',users.last_name) as name"),
            ])->with([
                'roles',
            ]);

            return Datatables::of($user)
                /*->addColumn('name', function ($user) {
                    return $user->first_name.' '.$user->last_name;
                })*/
                /*->filterColumn('name', function($query, $keyword) {
                    $query->whereRaw("CONCAT(users.first_name,'-',users.last_name) like ?", ["%{$keyword}%"]);
                })*/
                ->filterColumn('name', function($query, $keyword) {
                    $sql = "CONCAT(users.first_name,'-',users.last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->editColumn('designation', function ($user) {
                    return $user->roles ? $user->roles->first()->name : '';
                })
                ->editColumn('status', function ($user) {
                    return $user->status == 1 ? 'ACTIVE' : 'INACTIVE';
                    /*if ( $user->status == 6) {
            $status = "Inactive";
            }elseif ($user->confirmed == 1 && $user->status == 1) {
            $status = "Active";
            } else if($user->confirmed == 0 && ($user->status == 1 || $user->status == 3)){
            if ($user->status == 1) {
            $status = "E/Pending";
            }else{
            $status = "Pending";
            }

            } else if($user->confirmed == 1 && $user->status == 4){
            $status = "Rejected";
            }else if($user->confirmed == 1 && $user->status == 3){
            $status = "A/Pending";
            } else{
            $status = "Deleted";
            }

            return $status;*/
                })
                ->addColumn('status_edit', function ($value) {
                    if ($value->status == 1) {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->id . '" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';

                    } else {
                        return '<center><a href="javascript:void(0)" form="noForm" class="blue song-status-toggle " data-id="' . $value->id . '" data-status="1"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';

                    }
                })
                ->addColumn('edit', function ($user) {
                    return '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('user/edit/' . $user->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fa fa-pencil"></i></a></center>';
                })
                ->make(true);

        } catch (Exception $exception) {
            Log::error(" user list view| Error in " . __CLASS__ . "::" . __FUNCTION__ . ":" . $exception->getLine() . " | " . $exception->getMessage());
            return $exception->getMessage();
        }

        $columns = array(
            0 => 'users.id',
            1 => 'users.id',
            2 => 'first_name',
            3 => 'username',
            4 => 'r.name',
            5 => 'total_ads',
            6 => 'confirmed',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $request->input('search.value');

        $data = User::with(['roles']);

        if ($request->has('status')) {
            if ($request->input('status') == 'active') {
                $data = $data->where('confirmed', 1);
            } elseif ($request->input('status') == 'pending') {
                $data = $data->where('confirmed', 0);
            } elseif ($request->input('status') == 'deleted') {
                $data = $data->where('status', 5);
            }
        } else {
            $data = $data;
        }

        if (!$request->has('search.value')) {
            $totalData = $data->count();

            if ($request->has('order.0.column') && $request->input('order.0.column') == 5) {
                $data = $data
                    ->distinct('users.email')
                    ->join('role_users as ru', 'users.id', '=', 'ru.user_id')
                    ->join('roles as r', 'r.id', '=', 'ru.role_id')
                    ->select([
                        'users.*',
                        \DB::raw('(SELECT count(*) FROM ads WHERE ads.user_id=users.id) as total_ads')])
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $data = $data
                    ->distinct('users.email')
                    ->join('role_users as ru', 'users.id', '=', 'ru.user_id')
                    ->join('roles as r', 'r.id', '=', 'ru.role_id')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get(['users.*']);
            }

        } else {
            $search = $request->input('search.value');

            $totalData = $data
                ->distinct('users.email')
                ->join('role_users as ru', 'users.id', '=', 'ru.user_id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->where('users.email', 'LIKE', "%{$search}%")
                ->orWhere('users.status', 'LIKE', "%{$search}%")
                ->orWhere('r.name', 'LIKE', "%{$search}%");

            $data = $data
                ->distinct('users.email')
                ->join('role_users', 'users.id', '=', 'role_users.user_id')
                ->join('roles as rr', 'rr.id', '=', 'role_users.role_id')
                ->where('users.email', 'LIKE', "%{$search}%")
                ->orWhere('users.status', 'LIKE', "%{$search}%")
                ->orWhere('rr.name', 'LIKE', "%{$search}%");

            $qs = explode(" ", $search);
            foreach ($qs as $qin => $query) {
                $totalData = $totalData->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%");

                $data = $data->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%");
            }

            $totalData = $totalData->get(['users.*'])
                ->count();

            $data = $data->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get(['users.*']);
        }

        $jsonList = array();
        $i = $start;
        foreach ($data as $key => $user) {
            if ($user->inRole('ad-user') | $user->inRole('merchant-user') | $user->inRole('agent-admin') | $user->inRole('system-admin') | $user->inRole('advertiser')) {

                $roles = '';
                foreach ($user->roles as $key_role => $value_role) {
                    $roles .= $value_role->name . ' ';
                }

                $dd = array();
                if ($logged_user->id != $user->id) {
                    array_push($dd, '<center><input type="checkbox" value="' . $user->id . '" name="check[]"></input></center>');
                } else {
                    array_push($dd, '');
                }

                array_push($dd, $i + 1);

                if ($user->first_name !== "" || $user->last_name !== "") {
                    array_push($dd, $user->first_name . ' ' . $user->last_name);
                } else {
                    array_push($dd, "-");
                }

                if ($user->username != "") {
                    array_push($dd, $user->username);
                } else {
                    array_push($dd, "-");
                }
                if (count($user->roles) > 0) {
                    array_push($dd, $roles);
                } else {
                    array_push($dd, "-");
                }

                if (count($user->ads) > 0) {
                    array_push($dd, '<center>' . count($user->ads) . '</center>');
                } else {
                    array_push($dd, '<center>0</center>');
                }

                if (isset($user->store->business_page_name)) {
                    if ($user->inRole('merchant-user') && $user->store->business_page_name) {
                        array_push($dd, '<a target="_blank" href="' . url($user->store->business_page_name) . '">' . $user->store->business_page_name . '</a>');
                    } else {
                        array_push($dd, "-");
                    }

                } else {
                    array_push($dd, "-");
                }
                $button = '<div class="fixed_float">';
                if ($user->inRole('merchant-user')) {
                    $button .= '<a href="' . url("user/view/" . $user->id) . '"><button type="button" class="btn btn-secondary ad-view blue" title="View"><i class="fa fa-eye"></i></button></a>';

                }

                $button .= '</div>';

                if ($user->status == 6) {
                    array_push($dd, '<center><span class="label pull-right">Inactive</span></center>');
                } elseif ($user->confirmed == 1 && $user->status == 1) {
                    array_push($dd, '<center><span class="label label-success pull-right">Active</span></center>');
                } else if ($user->confirmed == 0 && ($user->status == 1 || $user->status == 3)) {
                    if ($user->status == 1) {
                        array_push($dd, '<center><span class="label label-warning pull-right">E/Pending</span></center>' . $button);
                    } else {
                        array_push($dd, '<center><span class="label label-warning pull-right">Pending</span></center>' . $button);
                    }

                } else if ($user->confirmed == 1 && $user->status == 4) {
                    array_push($dd, '<center><span class="label label-danger pull-right">Rejected</span></center>');
                } else if ($user->confirmed == 1 && $user->status == 3) {
                    array_push($dd, '<center><span style="color:green" class="label  pull-right">A/Pending</span></center>');
                } else {
                    array_push($dd, '<center><span class="label label-danger pull-right">Deleted</span></center>');
                }

                if ($user->inRole('system-admin')) {
                    if ($logged_user->inRole('system-admin')) {
                        $permissions = Permission::whereIn('name', ['user.edit', 'admin'])->where('status', '=', 1)->lists('name');
                        if (Sentinel::hasAnyAccess($permissions)) {
                            array_push($dd, '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('user/edit/' . $user->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fa fa-pencil"></i></a></center>');
                        } else {
                            array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
                        }

                        $permissions = Permission::whereIn('name', ['user.delete', 'admin'])->where('status', '=', 1)->lists('name');
                        if (Sentinel::hasAnyAccess($permissions)) {
                            $actions = '<a href="#" class="red user-delete" data-id="' . $user->id . '" data-toggle="tooltip" data-placement="top" title="Delete User"><i class="fa fa-trash-o"></i></a> ';

                            array_push($dd, '<center>' . $actions . '</center>');

                            if ($user->status == 6) {
                                array_push($dd, '<center><a href="#" class="red user-status-toggle" data-id="' . $user->id . '" data-toggle="tooltip" data-placement="top" title="Activate User"><i class="fa fa-check"></i></a></center>');
                            } else {
                                array_push($dd, '<center><a href="#" class="red user-status-toggle" data-id="' . $user->id . '" data-toggle="tooltip" data-placement="top" title="Inactivate User"><i class="fa fa-times"></i></a></center>');
                            }
                        } else {
                            array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled"><i class="fa fa-trash-o"></i></a>');
                            array_push($dd, '');
                        }

                    } else {
                        array_push($dd, '-');
                        array_push($dd, '-');
                        array_push($dd, '');
                    }
                } else {
                    $permissions = Permission::whereIn('name', ['user.edit', 'admin'])->where('status', '=', 1)->lists('name');
                    if (Sentinel::hasAnyAccess($permissions)) {
                        array_push($dd, '<center><a href="#" class="blue" onclick="window.location.href=\'' . url('user/edit/' . $user->id) . '\'" data-toggle="tooltip" data-placement="top" title="Edit User"><i class="fa fa-pencil"></i></a></center>');
                    } else {
                        array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
                    }

                    $permissions = Permission::whereIn('name', ['user.delete', 'admin'])->where('status', '=', 1)->lists('name');
                    if (Sentinel::hasAnyAccess($permissions)) {
                        $actions = '<a href="#" class="red user-delete" data-id="' . $user->id . '" data-toggle="tooltip" data-placement="top" title="Delete User"><i class="fa fa-trash-o"></i></a> ';
                        array_push($dd, '<center>' . $actions . '</center>');
                        if ($user->status == 6) {
                            array_push($dd, '<center><a href="#" class="red user-status-toggle" data-id="' . $user->id . '" data-toggle="tooltip" data-placement="top" title="Activate User"><i class="fa fa-check"></i></a></center>');
                        } else {
                            array_push($dd, '<center><a href="#" class="red user-status-toggle" data-id="' . $user->id . '" data-toggle="tooltip" data-placement="top" title="Inactivate User"><i class="fa fa-times"></i></a></center>');
                        }
                    } else {
                        array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled"><i class="fa fa-trash-o"></i></a>');
                    }
                }

                array_push($jsonList, $dd);
                $i++;
            }
        }
        return Response::json(array('data' => $jsonList, "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData)));
        /*} else {
    return Response::json(array('data' => []));
    }*/
    }

    public function changerole(Request $request, $type)
    {
        $role_selected = $request->input('role');
        $users = $request->input('check');
        if (!empty($users)) {

            foreach ($users as $key => $value_1) {
                $user = Sentinel::findById($value_1);
                foreach ($user->roles as $key => $value_2) {
                    $role = Sentinel::findRoleById($value_2->id);
                    $role->users()->detach($user);
                }

                $user->save();
                //attach user for role
                $role = Sentinel::findRoleById($role_selected);
                $role->users()->attach($user);

                if ($user->roles()->where("role_id", 2)->count() > 0 && LayoutCustomization::where('user_id', $user->id)->count() == 0) {
                    LayoutCustomization::create([
                        'user_id' => $user->id,
                        'header_color_code' => '#800000',
                        'header_style_id' => '1',
                        'logo_img' => '',
                        'background_color_code' => '#ffffff',
                    ]);
                }

            }
            return redirect('user/list/' . $type)->with(['success' => true,
                'success.message' => sizeof($users) . ' User Role Changed successfully!',
                'success.title' => 'Well Done!']);
        } else {
            return redirect('user/list/' . $type)->with(['error' => true,
                'error.message' => 'Please Select one or more users!',
                'error.title' => 'Try Again!'])->withInput($request->input());
        }

    }

    /**
     * Activate or Deactivate User
     * @param  Request $request user id with status to change
     * @return json object with status of success or failure
     */
    public function status(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $user = User::find($id);
            if ($user) {
                $user->status = $status;
                $user->save();
                return response()->json(['status' => "success"]);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Delete a User
     * @param  Request $request user id
     * @return Json            json object with status of success or failure
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');

            $user = User::find($id);
            if ($user) {
                // $user->delete();
                $user->update(['status' => 5]);
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }
    public function toggleUser(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');

            $user = User::find($id);
            if ($user) {
                if ($user->status == 6) {
                    $user->status = 1;
                } else {
                    $user->status = 6;
                }

                if ($user->save()) {
                    return response()->json(['status' => 'success']);
                }
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    /**
     * Show the user edit screen to the user.
     *
     * @return Response
     */
    public function editView($id)
    {

        $user = User::with(['roles'])->find($id);
        $roles = UserRole::orderBy('name', 'asc')->get();
        $srole = array();
        foreach ($user->roles as $key => $value) {
            array_push($srole, $value->id);
        }

        return view('userManage::user.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'selected_roles' => $srole
        ]);



//        $logged_user = Sentinel::getUser();
//
//        $curUser = User::with(['roles'])->find($id);
//        if ($curUser->inRole('system-admin')) {
//            if ($logged_user->inRole('system-admin')) {
//                $branch = Branch::where('status', '=', 1)->get();
//                $user = User::where('status', '=', 1)->get();
//                $srole = array();
//                foreach ($curUser->roles as $key => $value) {
//                    array_push($srole, $value->id);
//                }
//
//                $roles = UserRole::orderBy('name', 'asc')->where('visible', 1)->get();
//                $roles_array = array();
//                foreach ($roles as $key => $value) {
//                    if (in_array($value->id, $srole, true)) {
//                        array_push($roles_array, '<option selected value="' . $value->id . '">' . $value->name . '</option>');
//                    } else {
//                        array_push($roles_array, '<option  value="' . $value->id . '">' . $value->name . '</option>');
//                    }
//                }
//
//                if ($curUser) {
//                    return view('userManage::user.edit')
//                        ->with([
//                            'curUser' => $curUser,
//                            'users' => $user,
//                            'roles' => $roles_array,
//                            'branch' => $branch,
//                        ]);
//                } else {
//                    return view('errors.404');
//                }
//            } else {
//                return view('errors.404');
//            }
//
//        } else if ($curUser->inRole('super-admin-developer')) {
//            return view('errors.access-denied');
//        } else {
//            $branch = Branch::where('status', '=', 1)->get();
//            $user = User::where('status', '=', 1)->get();
//            $srole = array();
//            foreach ($curUser->roles as $key => $value) {
//                array_push($srole, $value->id);
//            }
//
//            $roles = UserRole::orderBy('name', 'asc')->where('visible', 1)->get();
//            $roles_array = array();
//            foreach ($roles as $key => $value) {
//                if (in_array($value->id, $srole, true)) {
//                    array_push($roles_array, '<option selected value="' . $value->id . '">' . $value->name . '</option>');
//                } else {
//                    array_push($roles_array, '<option  value="' . $value->id . '">' . $value->name . '</option>');
//                }
//            }
//
//            $industries = Industry::all();
//            $business = null;
//            $layoutDetails = null;
//            $SliderImage = null;
//
//            $services = Service::where('status', '1')->get();
//            $user_enabled_services = ServiceRequest::where("user_id", $curUser->id)->where("status", 1)->lists("service_id")->toArray();
//            foreach ($services as $service) {
//                if (in_array($service->id, $user_enabled_services)) {
//                    $service->enabled = true;
//                } else {
//                    $service->enabled = false;
//                }
//            }
//
//            if ($curUser) {
//                if ($curUser->roles()->where("role_id", 2)->count() > 0) {
//                    $business = BusinessRegistration::where("user_id", $curUser->id)->first();
//                    $layoutDetails = LayoutCustomization::where("user_id", $curUser->id)->first();
//                    $SliderImage = SliderImage::where("layout_id", $layoutDetails['id'])->get();
//                }
//                return view('userManage::user.edit')->with([
//                    'curUser' => $curUser,
//                    'users' => $user,
//                    'roles' => $roles_array,
//                    'branch' => $branch,
//                    'services' => $services,
//                ])
//                    ->with("industries", $industries)
//                    ->with("layoutDetails", $layoutDetails)
//                    ->with("SliderImage", $SliderImage)
//                    ->with("business", $business);
//            } else {
//                return view('errors.404');
//            }
//        }

    }

    public function genarate_password()
    {
        return str_random(8);
    }

    /**
     * Add new user data to database
     *
     * @return Redirect to menu add
     */
    public function edit(UserRequest $request, $id)
    {

        // return $request->get( 'supervisor' );
        $password = $request->get('password');
        $usercount = User::where('id', '!=', $id)->where('email', '=', $request->get('username'))->count();
        if ($usercount == 0) {
            if (!empty($request->get('roles')) > 0) {
                $user = User::with(['roles'])->find($id);
                $user->first_name = $request->get('first_name');
                $user->last_name = $request->get('last_name');
                $user->username = $request->get('username');
                $user->email = $request->get('username');
                // $user->branch = $request->get('branch');
                // $user->makeChildOf(Sentinel::findById(1));
                $user->save();
                // return $user;

                foreach ($user->roles as $key => $value) {
                    $role = Sentinel::findRoleById($value->id);
                    $role->users()->detach($user);
                }

                //attach user for role
                foreach ($request->get('roles') as $key => $value) {
                    $role = Sentinel::findRoleById($value);
                    $role->users()->attach($user);
                }
                if ($password != '') {
                    Sentinel::update($user, array('password' => $password));
                }

                return redirect('user/list/all')->with(['success' => true,
                    'success.message' => 'User updated successfully!',
                    'success.title' => 'Good Job!']);
            } else {
                return redirect('user/edit/' . $id)->with(['error' => true,
                    'error.message' => 'ROLE can not be empty!',
                    'error.title' => 'Try Again!'])->withInput($request->input());
            }
        } else {
            return redirect('user/edit/' . $id)->with(['error' => true,
                'error.message' => 'User Already Exist!',
                'error.title' => 'Duplicate!']);
        }
    }

    public function profileView()
    {
        $user = Sentinel::getUser();

        // if (!in_array($user->permissions,['admin' => true])) {
        //     return redirect('/');
        // }

        return view('userManage::user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Sentinel::getUser();
        $this->validate($request, ['first_name' => 'required|max:255', 'last_name' => 'required|max:255', 'email' => 'email|unique:users,email,' . $user->id]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return back()->with(['success' => 'true', 'success.message' => 'User Profile Updated Successfully', 'success.title' => 'User Profile']);
    }

    // start account setting functions
    public function accountSettingsView()
    {
        $industries = Industry::all();
        $user = Sentinel::getUser();
        $business = null;
        $layoutDetails = null;
        $SliderImage = null;
        if ($user->roles()->where("role_id", 2)->orWhere("role_id", 3)->count() > 0) {
            $business = BusinessRegistration::where("user_id", $user->id)->first();
            $layoutDetails = LayoutCustomization::where("user_id", $user->id)->first();
            $SliderImage = SliderImage::where("layout_id", $layoutDetails['id'])->get();
        }

        return view("userManage::user.accountSettings")
            ->with("industries", $industries)
            ->with("user", $user)
            ->with("layoutDetails", $layoutDetails)
            ->with("SliderImage", $SliderImage)
            ->with("business", $business);
    }

    public function setAccountSettings(Request $request)
    {
        $user = Sentinel::getUser();

        if ($request->has('basic_data')) {

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'city' => $request->city,
                'mobile' => $request->mobile,
            ]);

            return redirect()->back()->with(['success' => true,
                'success.message' => 'Basic Settings Updated!',
                'success.title' => 'Success!',
            ]);
        }

        if ($request->has('social_data')) {
            $user->update([
                'facebook_url' => $request->facebook,
                'google_plus_url' => $request->google_plus,
                'twitter_url' => $request->twitter,
                'instagram_url' => $request->instagram,
                'linkedin_url' => $request->linkedin,
            ]);

            return redirect()->back()->with(['success' => true,
                'success.message' => 'Social Settings Updated!',
                'success.title' => 'Success!',
            ]);
        }

        if ($request->has('password_data')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
                return redirect()->back()->with(['success' => true,
                    'success.message' => 'Successfully updated your password!',
                    'success.title' => 'Success!',
                ]);
            } else {
                return redirect()->back()->with(['errors' => true,
                    'error.message' => 'Current password is not correct!',
                    'error.title' => 'Error!',
                ]);
            }
        }

        if ($request->has('business_data')) {

            $business = null;
            if ($request->has('business_user')) {
                $business = BusinessRegistration::where("user_id", $request->business_user)->first();

            } else {
                $business = BusinessRegistration::where("user_id", $user->id)->first();
            }

            if ($business) {
                $business->update([
                    'business_name' => $request->input('business_name'),
                    'industry_id' => $request->input('business_industry'),
                    'about' => $request->input('about'),
                    'open' => '',
                    'close' => '',
                    'company_address' => $request->input('business_company_address'),
                    'contact_number' => $request->input('business_contact_number'),
                    'business_page_name' => $request->input('profile_url'),
                    'website' => $request->input('business_url'),
                    'facebook_url' => $request->input('business_facebook_url'),
                    'googleplus_url' => $request->input('business_google_plus_url'),
                    'instagram_url' => $request->input('instagram_url'),
                    'twitter_url' => $request->input('twitter_url'),
                    'linkedin_url' => $request->input('linkedin_url'),
                ]);
                $opening = new OpeningHour;
                $openingResponse = $opening->storeTimes($business->id, $request);
                if ($openingResponse) {
                    return $openingResponse;
                }
                if ($request->has('business_user')) {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully updated business data!',
                        'success.title' => 'Success!',
                    ]);
                } else {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully updated your business data!',
                        'success.title' => 'Success!',
                    ]);
                }
            } else {

                $business = new BusinessRegistration();

                $business->business_name = $request->input('business_name');
                $business->website = $request->input('business_url');
                $business->business_page_name = $request->input('profile_url');
                $business->industry_id = $request->input('business_industry');
                $business->about = $request->input('about');
                $business->open = $request->input('open');
                $business->close = $request->input('close');
                $business->contact_number = $request->input('business_contact_number');
                $business->company_address = $request->input('business_company_address');
                $business->facebook_url = $request->input('business_facebook_url');
                $business->googleplus_url = $request->input('business_google_plus_url');
                $business->instagram_url = $request->input('instagram_url');
                $business->twitter_url = $request->input('twitter_url');
                $business->linkedin_url = $request->input('linkedin_url');

                if ($request->has('business_user')) {
                    $business->user_id = $request->business_user;

                } else {
                    $business->user_id = $user->id;
                }

                $business->save();
                $opening = new OpeningHour;
                $openingResponse = $opening->storeTimes($business->id, $request);
                if ($openingResponse) {
                    return $openingResponse;
                }

                if ($request->has('business_user')) {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully created business data!',
                        'success.title' => 'Success!',
                    ]);
                } else {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully created your business!',
                        'success.title' => 'Success!',
                    ]);
                }
            }
        }

        $image = new ImageController();

        if ($request->has('about_data')) {
            $msgs = [];

            if ($request->hasFile('cover_image')) {
                $rules = [
                    "cover_image" => 'image|mimes:jpg,png,jpeg',
                ];

                $validator = Validator::make($request->all(), $rules, $msgs);

                if ($validator->fails()) {
                    return redirect()->back()->with(["errors" => $validator->errors()]);
                }

                $file = $request->file('cover_image');
                $file_name = 'cover-' . date("YmdHis") . '.' . $file->getClientOriginalExtension();
                $path = $image->upload('user', $file, $file_name, $user->id);

                $user->update([
                    'cover_image' => $path . '/' . $file_name,
                ]);
            }
            if ($request->hasFile('avatar')) {
                $rules = [
                    "avatar" => 'image|mimes:jpg,png,jpeg',
                ];

                $validator = Validator::make($request->all(), $rules, $msgs);

                if ($validator->fails()) {
                    return redirect()->back()->with(["errors" => $validator->errors()]);
                }

                $file = $request->file('avatar');
                $file_name = 'avatar-' . date("YmdHis") . '.' . $file->getClientOriginalExtension();
                // $file->move(base_path() . '/storage/upload/images/user/', $file_name);
                $path = $image->upload('user', $file, $file_name, $user->id);

                $user->update([
                    'avatar' => $path . '/' . $file_name,
                ]);
            }
            $user->update([
                'description' => $request->input('description'),
            ]);
            return redirect()->back()->with(['success' => true,
                'success.message' => 'Successfully updated your about data!',
                'success.title' => 'Success!',
            ]);
        }

        if ($request->has('layout_data')) {
            $msgs = [];

            $layoutDetails = null;
            if ($request->has('layout_user')) {

                $layoutDetails = LayoutCustomization::where("user_id", $request->layout_user)->first();
            } else {

                $layoutDetails = LayoutCustomization::where("user_id", $user->id)->first();
            }
            if ($layoutDetails) {
                if ($request->hasFile('layout_logo')) {

                    $rules = [
                        "layout_logo" => 'image|mimes:jpg,png,jpeg',
                    ];

                    $validator = Validator::make($request->all(), $rules, $msgs);

                    if ($validator->fails()) {
                        return redirect()->back()->with(["errors" => $validator->errors()]);
                    }

                    $file = $request->file('layout_logo');
                    $file_name = 'layout-logo-' . date("YmdHis") . '.' . $file->getClientOriginalExtension();
                    $path = $image->upload_logo('layout/logo', $file, $file_name, $layoutDetails['id']);

                    $layoutDetails->update([
                        'logo_img' => $path . '/' . $file_name,
                    ]);
                }
                if ($request->hasFile('layout_slider_image')) {
                    $files = $request->file('layout_slider_image');
                    $si = 0;
                    foreach ($files as $file) {
                        $rules = array('layout_slider_image' => 'image|mimes:jpg,png,jpeg');
                        $validator = Validator::make(array('layout_slider_image' => $file), $rules);
                        if ($validator->fails()) {
                            return redirect()->back()->with(["errors" => $validator->errors()]);
                        }

                        $file_name = 'layout-slider-' . date("YmdHis") . '-' . $si . '.' . $file->getClientOriginalExtension();
                        $path = $image->Upload_slider('layout/slider', $file, $file_name, $layoutDetails['id']);
                        // $file->move(base_path() . '/storage/uploads/images/layout/slider/', $file_name);

                        SliderImage::create([
                            "layout_id" => $layoutDetails['id'],
                            "img_path" => $path,
                            "img_name" => $file_name,
                        ]);
                        $si++;
                    }
                }
                if ($request->has("layout_slider_delete")) {
                    foreach ($request->input('layout_slider_delete') as $delImage) {
                        SliderImage::destroy($delImage);
                    }
                }
                $layoutDetails->update([
                    'header_style_id' => $request->input('layout_header_style'),
                    'header_color_code' => $request->input('layout_header_color'),
                    'background_color_code' => $request->input('layout_background_color'),
                ]);
                if ($request->has('layout_user')) {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully updated layout data!',
                        'success.title' => 'Success!',
                    ]);
                } else {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully updated your layout data!',
                        'success.title' => 'Success!',
                    ]);
                }
            } else {
                $layoutDetails = new LayoutCustomization();
                if ($request->has('layout_user')) {

                    $layoutDetails->user_id = $request->layout_user;
                } else {

                    $layoutDetails->user_id = $user->id;
                }
                $layoutDetails->header_style_id = $request->input('layout_header_style');
                $layoutDetails->header_color_code = $request->input('layout_header_color');
                $layoutDetails->background_color_code = $request->input('layout_background_color');
                $layoutDetails->save();
                if ($request->hasFile('layout_logo')) {
                    $rules = [
                        "layout_logo" => 'image|mimes:jpg,png,jpeg',
                    ];

                    $validator = Validator::make($request->all(), $rules, $msgs);

                    if ($validator->fails()) {
                        return redirect()->back()->with(["errors" => $validator->errors()]);
                    }

                    $file = $request->file('layout_logo');
                    $file_name = 'layout-logo-' . date("YmdHis") . '.' . $file->getClientOriginalExtension();
                    $path = $image->upload('layout/logo', $file, $file_name, $layoutDetails['id']);

                    $layoutDetails->logo_img = $path . '/' . $file_name;

                }
                if ($request->hasFile('layout_slider_image')) {
                    $files = $request->file('layout_slider_image');
                    foreach ($files as $file) {
                        $rules = array('layout_slider_image' => 'image|mimes:jpg,png,jpeg');
                        $validator = Validator::make(array('layout_slider_image' => $file), $rules);
                        if ($validator->fails()) {
                            return redirect()->back()->with(["errors" => $validator->errors()]);
                        }

                        $file_name = 'layout-slider-' . date("YmdHis") . '.' . $file->getClientOriginalExtension();
                        $path = $image->Upload_slider('layout', $file, $file_name, $layoutDetails['id']);
                        // $file->move(base_path() . '/storage/upload/images/layouts/', $file_name);

                        SliderImage::create([
                            "layout_id" => $layoutDetails['id'],
                            "img_path" => $path,
                            "img_name" => $file_name,
                        ]);
                    }
                }
                if ($request->has("layout_slider_delete")) {
                    foreach ($request->input('layout_slider_delete') as $delImage) {
                        SliderImage::destroy($delImage);
                    }
                }
                if ($request->has('layout_user')) {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully updated  layout data!',
                        'success.title' => 'Success!',
                    ]);
                } else {
                    return redirect()->back()->with(['success' => true,
                        'success.message' => 'Successfully updated your layout data!',
                        'success.title' => 'Success!',
                    ]);
                }
            }
        }
        return false;
    }

    public function viewUser($id)
    {
        $user = User::find($id);

        return view('userManage::user.view')->with([
            'user' => $user,
        ]);
    }

    public function merchantUserApprove(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $user = User::find($id);
            if ($user) {
                $user->status = $status;
                $user->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    public function merchantUserReject(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $status = $request->input('status');

            $user = User::find($id);
            if ($user) {
                $user->status = $status;
                $user->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'invalid_id']);
            }
        } else {
            return response()->json(['status' => 'not_ajax']);
        }
    }

    public function getserProductsData($id, Request $request)
    {
        $user = User::findOrFail($id);
        $jsonList = array();
        $search = $request->input('search.value');

        $products = $user->products();

        if (strlen($search) > 0) {
            $products = $products->where('name', 'LIKE', "%{$search}%")
                ->orWhere('price', 'LIKE', "%{$search}%")
                ->orWhere('current_stock', 'LIKE', "%{$search}%");
        }
        $products = $products->get();
        foreach ($products as $key => $product) {
            $data = array();

            $image = "";
            if ($product->getImages->first() == null) {
                $image = '<img alt="image" style="width: 75px;" class="img-responsive" src="' . url('/core/storage/uploads/no_image.png') . '"></img>';
            } elseif ($product->getImages->first()->is_wp) {
                $image = '<img alt="image" class="img-responsive" style="width: 75px;" src="http://35.200.150.141/' . $product->getImages()->first()['path'] . '/' . $product->getImages()->first()['filename'] . '"></img>';
            } else {
                $image = '<img alt="image" class="img-responsive" style="width: 75px;" src="' . Config('constants.bucket.url') . '' . $product->getImages()->first()['path'] . '/' . $product->getImages()->first()['filename'] . '"></img>';
            }
            array_push($data, $image);

            array_push($data, $product->name);
            array_push($data, $product->current_stock);

            if ($product->price_negotiable == 1) {
                array_push($data, "Price is negotiable");
            } else {
                array_push($data, number_format($product->price, 2));
            }

            array_push($jsonList, $data);
        }
        return Response::json(array('data' => $jsonList, "draw" => intval($request->input('draw')),
            "recordsTotal" => count($jsonList),
            "recordsFiltered" => count($jsonList)));
    }
}
