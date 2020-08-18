<?php namespace App\Http\Middleware;

use Closure;
use Permissions\Models\Permission;
use Request;
use Route;
use Sentinel;
use Session;

class AuthenticateFront {

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
				return redirect()->route('front.login');
			} else {
				$user = Sentinel::getUser();
				$action = Route::currentRouteName();
				$permissions = Permission::whereIn('name', [$action, 'admin'])->where('status', '=', 1)->lists('name');
				if (!$user->hasAnyAccess($permissions)) {
					return response()->view('front.errors.404');
				}
			}
		} catch (\Exception $e) {
			Session::put('loginRedirect', $request->url());
			return redirect()->route('front.login');
		}

		view()->share('user', $user);

		return $next($request);
	}

}
