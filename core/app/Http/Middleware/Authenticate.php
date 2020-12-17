<?php namespace App\Http\Middleware;

use App\Classes\DynamicMenu;
use Closure;
use Input;
use MenuManage\Models\Menu;
use Permissions\Models\Permission;
use Request;
use Route;
use Sentinel;
use Session;
use Log;

class Authenticate {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		try {
			if (!Sentinel::check()) {
				Session::put('loginRedirect', $request->url());
				return redirect()->route('user.login');
			} else {
				Log::info('Showing user profile for user: 1');

				$user = Sentinel::getUser();
				$action = Route::currentRouteName();
				$permissions = Permission::whereIn('name', [$action, 'admin'])->where('status', '=', 1)->lists('name');

				if (!$user->hasAnyAccess($permissions)) {
					Log::info('Showing user profile for user: 2');
					$menu = Menu::with(['children'])->where('label', '=', 'Root Menu')->first()->getDescendants()->toHierarchy(); // Get all menus
					
					$currentMenu = Menu::with(['children'])->where('link', '=', Request::path())->where('status', '=', 1)->first(); //Get the id of Current Route Url

					if ($currentMenu) {
						// $aa = DynamicMenu::generateMenu(0, $menu, 0, $currentMenu, Sentinel::getUser()->id);
						$aa = DynamicMenu::generateMenu(0, $menu, 0, $currentMenu, $user);
					}
					//Generate Menu with current url id
					else {
						// $aa = DynamicMenu::generateMenu(0, $menu, 0, null, Sentinel::getUser()->id);
						$aa = DynamicMenu::generateMenu(0, $menu, 0, null, $user);
					}
					//Generate Menu without current url id

					view()->share('menu', $aa); //Share the generated menu with all views
					view()->share('user', $user);

					return response()->view('errors.404');
				}
			}
		} catch (\Exception $e) {
			Session::put('loginRedirect', $request->url());
			return redirect()->route('user.login');
		}
		Log::info('Showing user profile for user: 3');
		$user = Sentinel::getUser();
		//Menu::rebuild();die;
		Log::info('Showing user profile for user: 4');
		$menu = Menu::with(['children'])->where('label', '=', 'Root Menu')->first()->getDescendants()->toHierarchy(); // Get all menus
		Log::info('Showing user profile for user: 5');
		$currentMenu = Menu::with(['children'])->where('link', '=', Request::path())->where('status', '=', 1)->first(); //Get the id of Current Route Url
		Log::info('Showing user profile for user: 6');
		if ($currentMenu) {
			Log::info('Showing user profile for user: 7');
			// $aa = DynamicMenu::generateMenu(0, $menu, 0, $currentMenu, Sentinel::getUser()->id);
			$aa = DynamicMenu::generateMenu(0, $menu, 0, $currentMenu, $user);
		}
		//Generate Menu with current url id
		else {
			Log::info('Showing user profile for user: 8');
			// $aa = DynamicMenu::generateMenu(0, $menu, 0, null, Sentinel::getUser()->id);
			$aa = DynamicMenu::generateMenu(0, $menu, 0, null, $user);
		}
		Log::info('Showing user profile for user: 9');
		//Generate Menu without current url id

		view()->share('menu', $aa); //Share the generated menu with all views
		view()->share('user', $user);

		return $next($request);
	}

}
