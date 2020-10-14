<?php
namespace MenuManage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Font;
use Illuminate\Http\Request;
use MenuManage\Http\Requests\MenuRequest;
use MenuManage\Models\Menu;
use Permissions\Models\Permission;
use Response;
use Sentinel;

class MenuController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Menu Controller
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
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function addView()
	{
		$menus = Menu::all(); 
		$font=Font::all();
		$permissions = Permission::all();
		$permissions->pull( 'admin' );
		$permissions->pull( 'index' );
		return view( 'menuManage::menu.add' )->with([ 'menus' => $menus,
			'permissionArr' => $permissions,
			 ]);
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function add(MenuRequest $request)
	{
		// return 'df';
		$permissions = $request->get( 'permissions' );
		$permissions[] = "admin";

		$parent = Menu::find( $request->get( 'parent_menu' ) );
		$sortAfter = Menu::find( $request->get( 'menu_order' ) );

		$menu = Menu::create([
			'label'			=> $request->get( 'label' ),
			'link'			=> $request->get( 'menu_url' ),
			'icon'			=> $request->get( 'menu_icon' ),
			'permissions'	=> json_encode( $permissions )
		]);

		$menu->makeChildOf($parent);

		if($sortAfter->id != $parent->id)
		$menu->moveToRightOf($sortAfter);

		return redirect( 'menu/add' )->with([ 'success' => true,
			'success.message'=> 'Menu added successfully!',
			'success.title' => 'Well Done!' ]);
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function listView()
	{
		
		return view( 'menuManage::menu.list' );
	}

	/**
	 * Show the menu add screen to the user.
	 *
	 * @return Response
	 */
	public function jsonList(Request $request)
	{
		if($request->ajax()){
			$data = Menu::with(['parentMenu'])->first()->getDescendants();
			$user = Sentinel::getUser();
			$jsonList = array();
			$i=1;
			foreach ($data as $key => $menu) {
				$dd = array();
				array_push($dd, $i);
				$label = '';
				for($j=1; $j < $menu->depth; $j++) {
					$label .= '--';
				}

				$label .= $menu->label;
				array_push($dd, $label);
				array_push($dd, $menu->link);
				if($menu->parent != 0){
					array_push($dd, $menu->parentMenu->label);
				}else{
					array_push($dd, "Root Menu");
				}

				$json = json_decode($menu->permissions,true);

				array_push($dd, str_replace(',', ', ', json_encode($json)));
				//array_push($dd, $menu->permissions);
				

				$permissions = Permission::whereIn('name',['menu.edit','admin'])->where('status','=',1)->lists('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<center><a href="#" class="blue" onclick="window.location.href=\''.url('menu/edit/'.$menu->id).'\'" data-toggle="tooltip" data-placement="top" title="Edit Menu"><i class="fa fa-pencil"></i></a></center>');
				}else{
					array_push($dd, '<a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Edit Disabled"><i class="fa fa-pencil"></i></a>');
				}

				$permissions = Permission::whereIn('name',['menu.delete','admin'])->where('status','=',1)->lists('name');
				if(Sentinel::hasAnyAccess($permissions)){
					array_push($dd, '<center><a  class="red menu-delete" data-id="'.$menu->id.'" data-toggle="tooltip" data-placement="top" title="Delete Menu"><i class="fa fa-trash-o"></i></a></center>');
				}else{
					array_push($dd, '<center><a href="#" class="disabled" data-toggle="tooltip" data-placement="top" title="Delete Disabled"><i class="fa fa-trash-o"></i></a></center>');
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
	 * Activate or Deactivate Menu
	 * @param  Request $request menu id with status to change
	 * @return Json           	json object with status of success or failure
	 */
	public function status(Request $request)
	{
		if($request->ajax()){
			$id = $request->input('id');
			$status = $request->input('status');

			$menu = Menu::find($id);
			if($menu){
				$menu->status = $status;
				$menu->save();
				return response()->json(['status' => 'success']);
			}else{
				return response()->json(['status' => 'invalid_id']);
			}
		}else{
			return response()->json(['status' => 'not_ajax']);
		}
	}

	/**
	 * Delete a Menu
	 * @param  Request $request menu id
	 * @return Json           	json object with status of success or failure
	 */
	public function delete(Request $request)
	{
		
		if($request->ajax()){
			$id = $request->input('id');

			$menu = Menu::find($id);
			if($menu){
				$menu->delete();
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


		$menus = Menu::all();
		$font=Font::all();
		$permissions = Permission::lists( 'name' , 'name' );;
		$permissions->pull('admin' );
		$permissions->pull('index' );		
		$curMenu = Menu::find($id);
		// $menuSort = $curMenu->getSiblingsAndSelf()->lists('label','id');
		$menuSort = $curMenu->getSiblingsAndSelf();
		$selected_permission=array();

		if($curMenu){
			$cur_permission=json_decode($curMenu->permissions);
			foreach (json_decode($permissions) as $key => $value) {
		    	if(in_array($value, $cur_permission, true)){		    		
		    		array_push($selected_permission, '<option selected value="'.$value.'">'.$value.'</option>');
		    	}else{
		    		array_push($selected_permission, '<option  value="'.$value.'">'.$value.'</option>');
		    	}
		    }	

			return view( 'menuManage::menu.edit' )->with([ 'menus' => $menus,
				'permissionArr' => $selected_permission,
				'curMenu' => $curMenu,
				'menuSort' => $menuSort,
				'font'=>$font
				 ]);
		}else{
			return view( 'errors.404' )->with([ 'menus' => $menus,
				'permissionArr' => $permissions ]);
		}
	}

	/**
	 * Add new menu data to database
	 *
	 * @return Redirect to menu add
	 */
	public function edit(MenuRequest $request, $id)
	{
		$permissions = $request->get( 'permissions' );
		$permissions[] = "admin";		

		$parent = Menu::find( $request->get( 'parent_menu' ) );
		$sortAfter = Menu::find( $request->get( 'menu_order' ) );

		$menu = Menu::find($id);

		$menu->label		= $request->get( 'label' );
		$menu->link			= $request->get( 'menu_url' );		
		$menu->icon			= $request->get( 'menu_icon' );		
		$menu->permissions	= json_encode( $permissions );

		$menu->save();

		$menu->makeChildOf($parent);

		if($menu->id != $sortAfter->id)
			$menu->moveToRightOf($sortAfter);

		return redirect( 'menu/list' )->with([ 'success' => true,
			'success.message'=> 'Menu updated successfully!',
			'success.title' => 'Good Job!' ]);
	}
}
