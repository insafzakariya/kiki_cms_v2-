<?php 

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Input;
use Mail;
use Sentinel;
use Session;
use Socialite;
use UserManage\Models\User;
use UserRoles\Models\Artist;

class AuthController extends Controller {

	/*
		|--------------------------------------------------------------------------
		| Welcome Controller
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
	public function __construct() {
		//$this->middleware('auth');
	}
	

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function loginView() { 
		try {
			if (!Sentinel::check()) {				
				return view('layouts.back.login');
			} else {
				$redirect = Session::get('loginRedirect', '');
				Session::forget('loginRedirect');
				return redirect($redirect);
			}
		} catch (\Exception $e) {
			return view('layouts.back.login')->withErrors(['login' => $e->getMessage()]);
		}
	}
	/*FRONT LOGIN*/
	public function loginView_front() {
		
		try {
			if (!Sentinel::check()) {				
				return view('layouts.front.login');
			} else {
				$redirect = Session::get('loginRedirect', '');
				Session::forget('loginRedirect');
				return redirect($redirect);
			}
		} catch (\Exception $e) {
			return view('layouts.front.login')->withErrors(['login' => $e->getMessage()]);
		}
	}

	
	public function login() {

		$remember = false;
		$credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
		);

		if (Input::get('login-remember')) {
			$remember = true;
		} else {
			$remember = false;
		}

		try {
			// using 3 as for pending status for merchant user
			$check_user = User::where('confirmed', 1)
			->whereIn('status', [1,3,6])->where('email', Input::get('username'))->first();
			
			if($check_user){
				if($check_user->status == 6){
					return redirect('user/login')->withErrors([ 
						'login' => 'Account Inactivated, Please contact the administrator to reactivate the account',
					]);
				}
			    $user = Sentinel::authenticate($credentials, $remember);
				if ($user) {				
					if($user->hasAnyAccess(['ad.user'])){
						$redirect = Session::get('loginRedirect', '/');
					}else{
						$redirect = Session::get('loginRedirect', 'admin');
					}
					Session::forget('loginRedirect');

					return redirect($redirect);

				} else {
					return redirect('user/login')->withErrors([
						'login' => 'Incorrect Username or Password',]);
				}
			}
			else{
				return redirect('user/login')->withErrors([ 
						'login'=> 'Your account is no longer available',
					]);
			}
		} catch (\Exception $e) {
			return $msg = $e->getMessage();
		}
	}
	public function login_front() {
		// using 3 as for pending status for merchant user
		$check_user = User::where('confirmed', 1)->whereIn('status', [1,3])->where('email', Input::get('username'))->first();		

		if($check_user){
			if($check_user->status == 6){
				return redirect('user/login')->with([ 
					'error' => true,
					'error.message'=> 'Please contact the administrator to reactivate the account',
					'error.title' => 'Account Inactivated!'
				]);
			}
			if ($check_user->wp_id!=0 && $check_user->password_reset_count==0) {
				return view('layouts.front.migrated-user-password-reset');
			}else{
				
				$credentials = array(
					'username' => Input::get('username'),
					'password' => Input::get('password'),
				);
				$remember = false;
				if (Input::get('login-remember')) {
					$remember = true;
				} else {
					$remember = false;
				}

				try {

					$user = Sentinel::authenticate($credentials, $remember);

				 // return $user->getPermissionsAttribute();
					if ($user) {

						if($user->hasAnyAccess(['guest.user'])){
							$redirect = url('front/register/type');					
						}else if($user->hasAnyAccess(['ad.user'])){
							$redirect = url('/');					
						}else if($user->hasAnyAccess(['business.user'])){
							$redirect = url('/');
						}elseif ($user->hasAnyAccess(['admin'])) {
							$redirect = url('admin');
						}elseif ($user->hasAnyAccess(['sambole.admin'])) {
							$redirect = url('admin');
						}
						Session::forget('loginRedirect');

						return redirect($redirect);

					} else {
						return redirect('front/login')->with([ 'error' => true,
							'error.message'=> 'Incorrect Username or Password !',
							'error.title' => 'Try Again!']);
					}
				} catch (\Exception $e) {
					return $msg = $e->getMessage();
				}
			}

		}
		else{
			$check_user = User::where('confirmed', 1)->where('email', Input::get('username'))->first();
			$error_msg = "";
			if($check_user['status'] == 1){
				$error_msg = 'You need to confirm your account before login!';
				return redirect()->back()->with(['error' => true,
				'error.message' => 'You need to confirm your account before login!',
				'error.title' => 'Error!']);
			}
			if($check_user['status'] == 5){
				$error_msg = 'You account is no longer available!';
				return redirect()->back()->with(['error' => true,
				'error.message' => 'You account is no longer available!',
				'error.title' => 'Error!']);
			}
			
			return redirect()->back()->with(['error' => true,
			'error.message' => 'Please contact the administrator to reactivate the account',
			'error.title' => 'Error!']);

			
		}
		
		
	}
	public function registerView_front()
	{

		try {
			if (!Sentinel::check()) {				
				return view('layouts.front.register');
			} else {
				$redirect = Session::get('loginRedirect', '');
				Session::forget('loginRedirect');
				return redirect($redirect);
			}
		} catch (\Exception $e) {
			return view('layouts.front.login')->withErrors(['login' => $e->getMessage()]);
		}
	}
	public function register_front() {
		$credentials = [
			'login' => Input::get('email'),
		];

		$user_exsist = Sentinel::findByCredentials($credentials);
		
		if (!$user_exsist) {

			try {
				DB::transaction(function () {

					$code = null;

					######### Email verification ############
					$code = md5(uniqid(mt_rand(), true));
					$url = route('front.email.confirm', $code);

					$user = Input::get('email');

					Mail::send('emails.confirm', ['url' => $url], function($message) use ($user) {
						$message->to($user, '')->subject('User Verification Confirmation');
						// $message->cc('lakshitha.infomail@gmail.com', '')->subject('Verify Email Address');
						// $message->cc('insaf.zak@gmail.com', '')->subject('Verify Email Address');
					});
					######### Email verification END ############

					// using 3 as for pending status for merchant user
					$user_type=Input::get('user_type');
					$status = 1;
					if($user_type=='business.user') {
						$status = 3;					
					}

					$user = Sentinel::registerAndActivate([								
						'email' => Input::get('email'),
						'username' => Input::get('email'),
						'password' => Input::get('password'),
						'first_name' => Input::get('fname'),
						'last_name' => Input::get('lname'),
						'confirmation_code' => $code,
						'status' => $status,
					]);

					if (!$user) {

						throw new TransactionException('', 100);
					}

					$user->makeRoot();
					$user_type=Input::get('user_type');
					if ($user_type=='ad.user') {
						$role = Sentinel::findRoleById(1);
						$role->users()->attach($user);							
					} else if($user_type=='business.user') {
						$role = Sentinel::findRoleById(2);	
						$role->users()->attach($user);						
					}


						// User::rebuild();
					// Sentinel::login($user);			

				});

			} catch (TransactionException $e) {
				if ($e->getCode() == 100) {
					Log::info("Could not register user");

					return redirect('front/register')->with(['error' => true,
						'error.message' => "Could not register user",
						'error.title' => 'Ops!']);
				}
			} catch (Exception $e) {

			}
			$redirect = route('front.email.resend');
			// $user_type=Input::get('user_type');
			// if ($user_type=='ad.user') {
			// 	return redirect($redirect)->with(['success' => true,
			// 	'success.message' => "We have emailed you a confirmation request to your inbox!",
			// 	'success.title' => 'Success']);					
			// } else if($user_type=='business.user') {
			// 	$redirect = url('businessReg');
			// 	return redirect($redirect)->with(['success' => true,
			// 	'success.message' => "We have emailed you a confirmation request to your inbox!",
			// 	'success.title' => 'Success']);	
			// }

			$user = Sentinel::getUser();
			$link = new WebController();
			// $link->linkOldAdsToNewUser($user->id, $user->email);

			return redirect($redirect)->with(['success' => true,
				'success.message' => "We have emailed you a confirmation request to your inbox!",
				'success.title' => 'Success']);
		}else{
			// if($user_exsist->status == 5){ // deleted user
			// 	$user_exsist->sendReactivateEmail();

			// 	return redirect($redirect)->with(['success' => true,
			// 	'success.message' => "We have emailed you an account reactivation request to your inbox!",
			// 	'success.title' => 'Account Reactivation']);
			// }
			return redirect('front/register')->with(['error' => true,
				'error.message' => "Email address already exsist!",
				'error.title' => 'Ops!']);
		}
	}


	// ---------------------------------------------------------------------------



	/**
     * user registeration - confirm
     *
     * @return Response
     */


	// public function emailConfirm($code)
	// {
	// 	$user = User::where('confirmation_code', $code)->first();

	// 	if($user !== null){
	// 		if($user['confirmed'] !== 1){
	// 			$user['confirmed'] = 1;
	// 			$user->save();

	// 			return redirect()->route('front.login')->with(['success' => true,
	// 				'success.message' => "Successfully activated your account!",
	// 				'success.title' => 'Success']);
	// 		}
	// 		else{
	// 			return redirect()->route('front.login')->with(['warning' => true,
	// 				'warning.message' => "Account already activated!",
	// 				'warning.title' => 'Warning']);
	// 		}

	// 	}
	// 	else{
	// 		return redirect()->route('front.register')->with(['error' => true,
	// 			'error.message' => "Invalid confirmation request!",
	// 			'error.title' => 'Error']);
	// 	}        
	// }




	public function emailConfirm($code)
	{
		$user = User::where('confirmation_code', $code)->first();

		if($user !== null){
			if($user['confirmed'] !== 1){
				$user['confirmed'] = 1;
				// $user->save();
				// Sentinel::login($user);
				// dd($user);

				if ($user->inRole('advertiser')) {

					$redirect = url('/');
					Sentinel::login($user);
					return redirect($redirect)->with(['success' => true,
						'success.message' => "Wellcome!",
						'success.title' => 'Success']);

				} else if($user->inRole('merchant-user')) {
					
					$redirect = url('businessReg');
					Sentinel::login($user);
					return redirect($redirect)->with(['success' => true,
						'success.message' => "wellcome",
						'success.title' => 'Success']);	
				}else{

				}

				// return redirect()->route('front.login')->with(['success' => true,
				// 	'success.message' => "Successfully activated your account!",
				// 	'success.title' => 'Success']);
			}
			else{
				return redirect()->route('front.login')->with(['warning' => true,
					'warning.message' => "Account already activated!",
					'warning.title' => 'Warning']);
			}

		}
		else{
			return redirect()->route('front.register')->with(['error' => true,
				'error.message' => "Invalid confirmation request!",
				'error.title' => 'Error']);
		}        
	}
	public function emailReactivate($code)
	{
		$user = User::where('confirmation_code', $code)->first();

		if($user !== null){
			if($user['confirmed'] !== 1){
				
			}
			else{
				return redirect()->route('front.login')->with(['warning' => true,
					'warning.message' => "Account already activated!",
					'warning.title' => 'Warning']);
			}

		}
		else{
			return redirect()->route('front.register')->with(['error' => true,
				'error.message' => "Invalid confirmation request!",
				'error.title' => 'Error']);
		}        
	}






	// ---------------------------------------------------------------------------

	public function emailResend()
	{
		return view('layouts.front.email-resend');
	}

	public function emailResendPost(Request $request)
	{
		$user = User::where('email', $request->input('email'))->first();

		if(!$user){
			return redirect()->back()->with(['error' => true,
				'error.message' => "Invalid email address!",
				'error.title' => 'Error']);
		}
		else{
            // Email resend verification
			$code = md5(uniqid(mt_rand(), true));
			$url = route('front.email.confirm', $code);
			$user->update(['confirmation_code' => $code]);
			$user = Input::get('email');
			Mail::send('emails.confirm', ['url' => $url], function($message) use ($user) {
				$message->to($user, '')->subject('Verify Email Address');
				//$message->cc('lakshitha.infomail@gmail.com', '')->subject('Verify Email Address');
			});
			return redirect()->back()->with(['success' => true,
				'success.message' => "Successfully resent your email confirmation request!",
				'success.title' => 'Success']);
		}
	}

	public function registerTypeView_front()
	{
		return view('layouts.front.register-type');
	}
	public function registerType_front()
	{
		$user_type=Input::get('user_type');
		$user=Sentinel::getUser();		
		if ($user) {
			foreach ($user->roles as $key => $value) {
				$role = Sentinel::findRoleById($value->id);
				$role->users()->detach($user);
			}

			if ($user_type=='ad.user') {
				$role = Sentinel::findRoleById(1);
				$role->users()->attach($user);
				$user->save();
				return redirect()->to('/');
			} else if($user_type=='business.user') {
				$role = Sentinel::findRoleById(2);
				$role->users()->attach($user);
				$user->save();
				return redirect()->to('businessReg');
			}

		} else {
			return view('layouts.front.login');
		}

	}
	/*
		*	@method logout()
		*	@description Logging out the logged in user
		*	@return URL redirection
	*/
		public function logout() {

			if (Sentinel::hasAccess('ad.user'))
			{
				Sentinel::logout();		
				return redirect()->to('/');
			}else if(Sentinel::hasAccess('business.user')){
				Sentinel::logout();		
				return redirect()->to('/');
			}else{
				Sentinel::logout();		
				return redirect()->to('/admin');
			}


		}
		public function logout_front() {
			Sentinel::logout();		
			return redirect()->to('/');

		}


     /**
     * Redirect the user to the FACEBOOK authentication page.
     *
     * @return Response
     */
     public function redirectToFacebook()
     {
     	return Socialite::driver('facebook')->redirect();
     }
 	/**
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
 	public function redirectToGoogle()
 	{
 		return Socialite::driver('google')->redirect();
 	}

    /**
     * Obtain the user information from FACEBOOK.
     *
     * @return Response
     */
    public function handleFacebookCallback()
    {
    	$user = Socialite::driver('facebook')->user();
    	$user_details=$user->user;        
    	if(User::where('fb_id',$user_details['id'])->exists()){
    		$fbloged_user=User::where('fb_id',$user_details['id'])->get();
    		$fbloged_user=$fbloged_user[0];        	
    		$user_login = Sentinel::findById($fbloged_user->id);
    		Sentinel::login($user_login);
    		return redirect('/');
    	}else{
    		try {
    			$registed_user=DB::transaction(function ()  use ($user_details){

    				$user = Sentinel::registerAndActivate([								
    					'email' =>$user_details['id'].'@fbmail.com',
    					'username' => $user_details['name'],
    					'password' => $user_details['id'],
    					'fb_id' => $user_details['id']
    				]);

    				if (!$user) {
    					throw new TransactionException('', 100);
    				}

    				$user->makeRoot();

    				$role = Sentinel::findRoleById(1);
    				$role->users()->attach($user);

    				User::rebuild();
    				return $user;


    			});
    			Sentinel::login($registed_user);
    			return redirect('/');

    		} catch (TransactionException $e) {
    			if ($e->getCode() == 100) {
    				Log::info("Could not register user");

    				return redirect('user/register')->with(['error' => true,
    					'error.message' => "Could not register user",
    					'error.title' => 'Ops!']);
    			}
    		} catch (Exception $e) {

    		}
    	}






        // $user->token;
    }/**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function handleGoogleCallback()
    {	
    	$user = Socialite::driver('google')->user();
    	$user_details=$user->user;
	     // return $user_details['emails'][0]['value'];

    	if(User::where('g_id',$user_details['id'])->exists()){

    		$fbloged_user=User::where('g_id',$user_details['id'])->get();
    		$fbloged_user=$fbloged_user[0];        	
    		$user_login = Sentinel::findById($fbloged_user->id);
    		Sentinel::login($user_login);
    		return redirect('/');
    	}else{

    		try {
    			$registed_user=DB::transaction(function ()  use ($user_details){

    				$user = Sentinel::registerAndActivate([								
    					'email' =>$user_details['emails'][0]['value'],
    					'username' => $user_details['emails'][0]['value'],
    					'password' => $user_details['id'],
    					'g_id' => $user_details['id']
    				]);

    				if (!$user) {
    					throw new TransactionException('', 100);
    				}

    				$user->makeRoot();

    				$role = Sentinel::findRoleById(1);
    				$role->users()->attach($user);

    				User::rebuild();
    				return $user;


    			});
    			Sentinel::login($registed_user);
    			return redirect('/');

    		} catch (TransactionException $e) {
    			if ($e->getCode() == 100) {
    				Log::info("Could not register user");

    				return redirect('user/register')->with(['error' => true,
    					'error.message' => "Could not register user",
    					'error.title' => 'Ops!']);
    			}
    		} catch (Exception $e) {

    		}
    	}

    }
}
