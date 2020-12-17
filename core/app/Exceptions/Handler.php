<?php namespace App\Exceptions;

use App\Classes\DynamicMenu;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use MenuManage\Models\Menu;
use Request;
use Sentinel;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{


		/**
		 * Check whether the exception is a 404 http exception.
		 *
		 */
		if($this->isHttpException($e) && $e->getStatusCode()=='404'){

			/*
			 * Checks whether a user has been logged.
			 * If so generates menu and displays it.
			 */
			if(Sentinel::check()){
				$user = Sentinel::getUser();
				$menu = Menu::where('label', '=', 'Root Menu')->first()->getDescendants()->toHierarchy(); // Get all menus
			    $currentMenu = Menu::where('link','=',Request::path())->where('status','=',1)->first(); //Get the id of Current Route Url

			    if($currentMenu)
			    //   $aa = DynamicMenu::generateMenu(0,$menu,0,$currentMenu,Sentinel::getUser()->id); //Generate Menu with current url id
			      $aa = DynamicMenu::generateMenu(0,$menu,0,$currentMenu,$user); //Generate Menu with current url id
			    else
			    //   $aa = DynamicMenu::generateMenu(0,$menu,0,null,Sentinel::getUser()->id); //Generate Menu without current url id
			      $aa = DynamicMenu::generateMenu(0,$menu,0,null,$user); //Generate Menu without current url id

			    view()->share('menu',$aa); //Share the generated menu with all views
			    view()->share('user',$user);
			}
			return response()->view("errors.404");

		}

		return parent::render($request, $e);
	}

}
