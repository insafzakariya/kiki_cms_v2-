<?php
namespace UserRoles\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Permissions\Models\Permission;
use Response;
use Sentinel;
use UserRoles\Http\Requests\UserRoleRequest;
use UserRoles\Models\UserRole;

class UserRoleController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| User Role Controller
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
	 * Show the user role add screen to the user.
	 *
	 * @return Response
	 */
	public function addView()
	{
		$permissions = Permission::all()->lists( 'name' , 'name' );
		$permissions->pull( 'admin' );
		// $permissions->pull( 'index' );
		return view( 'userRoles::role.add' )->with([ 'permissionArr' => $permissions ]);
	}

	/**
	 * Add new role data to database
	 *
	 * @return Redirect to role add
	 */
	public function add(UserRoleRequest $request)
	{
		$user = Sentinel::getUser();
		$permissions =$request->get('permissions');
		$status = false;
		DB::transaction(function () use ($request, $permissions, $user, &$status) {

			Sentinel::getRoleRepository()->setModel('UserRoles\Models\UserRole');

			$role = Sentinel::getRoleRepository()->createModel()->create([
			    'name' => $request->get('name'),
			    'slug' => str_slug($request->get('name')),
			    'created_by' => $user->id,
			]);
			$perm = array();
			foreach ($permissions as $key => $value) {
				$perm[$value] = true;
			}

			$role->permissions = json_encode($perm);
			$role->save();
			$status = true;
		});
		if($status){
			return redirect( 'user/role/add' )->with([ 'success' => true,
				'success.message'=> 'Role added successfully!',
				'success.title' => 'Well Done!' ]);
		}
	}

	/**
	 * Show the roles list screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		return view( 'userRoles::role.list' );
	}

	/**
	 * Return roles json list to view
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		if($request->ajax()){
			$data = UserRole::all();
			$user = Sentinel::getUser();
			$jsonList = array();
			$i=1;
			foreach ($data as $key => $role) {
				$dd = array();
				array_push($dd, $i);

				array_push($dd, $role->name);				

				$json = array_keys(json_decode($role->permissions,true));

				array_push($dd, str_replace(',', ', ', json_encode($json)));

				$permissions = Permission::whereIn('name',['user.role.edit','admin'])->where('status','=',1)->lists('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('user/role/edit/'.$role->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit Role"><i class="fa fa-pencil"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
				}

				$permissions = Permission::whereIn('name',['user.role.delete','admin'])->where('status','=',1)->lists('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="red role-delete" data-id="'.$role->id.'" data-toggle="tooltip" data-placement="top" title="Delete Role"><i class="fa fa-trash-o"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled"><i class="fa fa-trash-o"></i></a>');
				}

				array_push($jsonList, $dd);
				$i++;
			}
			return Response::json(array('data'=>$jsonList));
		}else{
			return Response::json(array('data'=>[]));
		}
	}

	/**
	 * Delete a User Role
	 * @param  Request $request role id
	 * @return Json           	json object with status of success or failure
	 */
	public function delete(Request $request)
	{
		if($request->ajax()){
			$id = $request->input('id');

			$role = UserRole::find($id);
			if($role){
				$role->delete();
				return response()->json(['status' => 'success']);
			}else{
				return response()->json(['status' => 'invalid_id']);
			}
		}else{
			return response()->json(['status' => 'not_ajax']);
		}
	}

	/**
	 * Show the menu edit screen to the user.
	 *
	 * @return Response
	 */
	public function editView($id)
	{
		$permissions = Permission::all()->lists( 'name' , 'name' );
		$permissions->pull( 'admin' );
		// $permissions->pull( 'index' );

		$role = UserRole::find($id);

		$cur_permission=$role->permissions;
		$selected_permission=array();
		$cur_permission=array_keys(json_decode($role->permissions, true));
		foreach ($permissions as $key => $value) {			
	    	if(in_array($value, $cur_permission, true)){		    		
		    		array_push($selected_permission, '<option selected value="'.$value.'">'.$value.'</option>');
		    	}else{
		    		array_push($selected_permission, '<option  value="'.$value.'">'.$value.'</option>');
		    	}
	    }
	   
		if($role){
			return view('userRoles::role.edit')->with([
				'permissionArr' => $selected_permission,
				'role'          => $role
			]);
		}else{
			return view('errors.404');
		}
	}

	/**
	 * edit role data to database
	 *
	 * @return Redirect to roles list
	 */
	public function edit(UserRoleRequest $request, $id)
	{

		$user = Sentinel::getUser();
		$permissions =$request->get('permissions');		
		$status = false;
		DB::transaction(function () use ($request, $permissions, $user, &$status,$id) {

			Sentinel::getRoleRepository()->setModel('UserRoles\Models\UserRole');

			$role = Sentinel::findRoleById($id);
			$role->name = $request->get('name');
			$role->slug = str_slug($request->get('name'));
			$role->created_by = $user->id;

			$perm = array();
			foreach ($permissions as $key => $value) {
				$perm[$value] = true;
			}

			$role->permissions = json_encode($perm);
			$role->save();
			$status = true;
		});
		if($status){
			return redirect( 'user/role/list' )->with([ 'success' => true,
				'success.message'=> 'Role added successfully!',
				'success.title' => 'Well Done!' ]);
		}

		
	}
}
