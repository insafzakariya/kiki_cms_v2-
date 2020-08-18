<?php
namespace Permissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Permissions\Http\Requests\PermissionRequest;
use Permissions\Models\Permission;
use Response;
use Sentinel;

class PermissionController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Permission Controller
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
	 * Show the Permission add screen to the user.
	 *
	 * @return Response
	 */
	public function addView()
	{
		return view( 'permissions::permission.add' );
	}

	/**
	 * Add new Permission data to database
	 *
	 * @return Redirect to Permission add
	 */
	public function add(PermissionRequest $request)
	{
		$name = $request->get( 'label' );
		$description = $request->get( 'description' );

		$count= Permission::where('name', '=',$name)->count();
		if ($count==0) {
				$permission = Permission::create([
				'name'			=> $name,
				'description'			=> $description,
				'created_by'=>Sentinel::getUser()->id
			]);	

			return redirect( 'permission/add' )->with([ 'success' => true,
			'success.message' => 'Permission added successfully!',
			'success.title'   => 'Well Done!' ]);
		}else{
			
				return redirect('permission/add')->with([ 'error' => true,
				'error.message'=> 'Permission Already Exsist!',
				'error.title' => 'Duplicate!']);
			}
		
	}

	/**
	 * Show the Permission add screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		return view( 'permissions::permission.list' );
	}

	/**
	 * Show the Permission add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		if($request->ajax()){
			$permissions = Permission::where('name','!=','admin')->where('name','!=','index')->get();
			$user = Sentinel::getUser();
			$jsonList = array();
			$i=1;
			foreach ($permissions as $key => $permission) {
				$dd = array();
				array_push($dd, $i);

				array_push($dd, $permission->name);
				array_push($dd, ($permission->description != null || $permission->description != '')? $permission->description : '-');
				$permissions = Permission::whereIn('name',['permission.edit','admin'])->where('status','=',1)->lists('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="blue" onclick="window.location.href=\''.url('permission/edit/'.$permission->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit Permission"><i class="fa fa-pencil"></i></a>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
				}

				$permissions = Permission::whereIn('name',['permission.delete','admin'])->where('status','=',1)->lists('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<a href="#" class="red permission-delete" data-id="'.$permission->id.'" data-toggle="tooltip" data-placement="top" title="Delete Permission"><i class="fa fa-trash-o"></i></a>');
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
	 * Delete a Permission
	 * @param  Request $request Permission id
	
	 */
	public function delete(Request $request)
	{
		if($request->ajax()){
			$id = $request->input('id');

			$permission = Permission::find($id);
			if($permission){
				$permission->delete();
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
		$permission=Permission::find($id);	
		if($permission){
			return view( 'permissions::permission.edit' )->with([ 
				'curPermission' => $permission						

				 ]);
		}else{
			return view( 'errors.404' );
		}
		
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(Request $request, $id)
	{
		$name = $request->get( 'label' );
		$description = $request->get( 'description' );

		$count= Permission::where('id', '!=', $id)->where('name', '=',$name)->count();
		
			if($count==0){
				$curPermission =  Permission::find($id);					
				$curPermission->name = $name;
				$curPermission->description = $description;				
				$curPermission->save();
				
				return redirect( 'permission/edit/'.$id )->with([ 'success' => true,
					'success.message'=> 'Permission updated successfully!',
					'success.title' => 'Good Job!' ]);
			}else{
				return redirect('permission/edit/'.$id)->with([ 'error' => true,
				'error.message'=> 'Permission Already Exsist!',
				'error.title' => 'Duplicate!']);
			}
	}
}
