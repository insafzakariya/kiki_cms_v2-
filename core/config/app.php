<?php

return [

	/*
		|--------------------------------------------------------------------------
		| Application Debug Mode
		|--------------------------------------------------------------------------
		|
		| When your application is in debug mode, detailed error messages with
		| stack traces will be shown on every error that occurs within your
		| application. If disabled, a simple generic error page is shown.
		|
	*/

	'debug' => env('APP_DEBUG'),

	/*
		|--------------------------------------------------------------------------
		| Application URL
		|--------------------------------------------------------------------------
		|
		| This URL is used by the console to properly generate URLs when using
		| the Artisan command line tool. You should set this to the root of
		| your application so that it is used when running Artisan tasks.
		|
	*/

	'url' => '/',

	/*
		|--------------------------------------------------------------------------
		| Application Timezone
		|--------------------------------------------------------------------------
		|
		| Here you may specify the default timezone for your application, which
		| will be used by the PHP date and date-time functions. We have gone
		| ahead and set this to a sensible default for you out of the box.
		|
	*/

	'timezone' => 'Asia/Colombo',

	/*
		|--------------------------------------------------------------------------
		| Application Locale Configuration
		|--------------------------------------------------------------------------
		|
		| The application locale determines the default locale that will be used
		| by the translation service provider. You are free to set this value
		| to any of the locales which will be supported by the application.
		|
	*/

	'locale' => 'en',

	/*
		|--------------------------------------------------------------------------
		| Application Fallback Locale
		|--------------------------------------------------------------------------
		|
		| The fallback locale determines the locale to use when the current one
		| is not available. You may change the value to correspond to any of
		| the language folders that are provided through your application.
		|
	*/

	'fallback_locale' => 'en',

	/*
		|--------------------------------------------------------------------------
		| Encryption Key
		|--------------------------------------------------------------------------
		|
		| This key is used by the Illuminate encrypter service and should be set
		| to a random, 32 character string, otherwise these encrypted strings
		| will not be safe. Please do this before deploying an application!
		|
	*/

	'key' => env('APP_KEY'),

	'cipher' => 'AES-256-CBC',

	/*
		|--------------------------------------------------------------------------
		| Logging Configuration
		|--------------------------------------------------------------------------
		|
		| Here you may configure the log settings for your application. Out of
		| the box, Laravel uses the Monolog PHP logging library. This gives
		| you a variety of powerful log handlers / formatters to utilize.
		|
		| Available Settings: "single", "daily", "syslog", "errorlog"
		|
	*/

	'log' => 'daily',

	/*
		|--------------------------------------------------------------------------
		| Autoloaded Service Providers
		|--------------------------------------------------------------------------
		|
		| The service providers listed here will be automatically loaded on the
		| request to your application. Feel free to add your own services to
		| this array to grant expanded functionality to your applications.
		|
	*/

	'providers' => [

		/*
			 * Laravel Framework Service Providers...
		*/
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Bus\BusServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Foundation\Providers\FoundationServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Pipeline\PipelineServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Auth\Passwords\PasswordResetServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Broadcasting\BroadcastServiceProvider',
		'Cartalyst\Sentinel\Laravel\SentinelServiceProvider',
		'Collective\Html\HtmlServiceProvider',
		'Baum\Providers\BaumServiceProvider',
		'Intervention\Image\ImageServiceProvider',
		'Maatwebsite\Excel\ExcelServiceProvider',
		'Elibyy\TCPDF\ServiceProvider',
        'Yajra\Datatables\DatatablesServiceProvider',

		
		/*LINK YOUR PAKAGERS HERE*/

		'UserManage\UserServiceProvider',
		'Permissions\PermissionsServiceProvider',
		'MenuManage\MenuServiceProvider',
        'UserRoles\UserRolesServiceProvider',
		'DashboardManage\DashboardServiceProvider',		
		'ArtistManage\ArtistManageServiceProvider',
		'MusicGenre\MusicGenreManageServiceProvider',
		'RadioChannel\RadioChannelManageServiceProvider',
		'SongsCategory\SongsCategoryManageServiceProvider',
		'MoodManage\MoodManageServiceProvider',
		'LyricistManage\LyricistManageServiceProvider',
		'SongComposerManage\SongComposerManageServiceProvider',
		'ProductManage\ProductManageServiceProvider',
		'ProjectManage\ProjectManageServiceProvider',
		'SongManage\SongManageServiceProvider',
		'PlaylistManage\PlaylistManageServiceProvider',



		/*DON't include your pakages to below this line*/
		
        Stevebauman\Location\LocationServiceProvider::class,       
        Laravel\Socialite\SocialiteServiceProvider::class,
        Darryldecode\Cart\CartServiceProvider::class,       
        Srmklive\PayPal\Providers\PayPalServiceProvider::class,		
		Greggilbert\Recaptcha\RecaptchaServiceProvider::class,
		\Torann\GeoIP\GeoIPServiceProvider::class,
		Superbalist\LaravelGoogleCloudStorage\GoogleCloudStorageServiceProvider::class,
		 App\Providers\SolariumServiceProvider::class,
		 // yedincisenol\Vision\LaravelServiceProvider::class,
        //yajra

		
		/*
			 * Application Service Providers...
		*/
		'App\Providers\AppServiceProvider',
		'App\Providers\BusServiceProvider',
		'App\Providers\ConfigServiceProvider',
		'App\Providers\EventServiceProvider',
		'App\Providers\RouteServiceProvider',
		'App\Providers\ViewComposerServiceProvider'

	],


	/*
		|--------------------------------------------------------------------------
		| Class Aliases
		|--------------------------------------------------------------------------
		|
		| This array of class aliases will be registered when this application
		| is started. However, feel free to register as many as you wish as
		| the aliases are "lazy" loaded so they don't hinder performance.
		|
	*/

	'aliases' => [
		'App' => 'Illuminate\Support\Facades\App',
		'Artisan' => 'Illuminate\Support\Facades\Artisan',
		'Auth' => 'Illuminate\Support\Facades\Auth',
		'Blade' => 'Illuminate\Support\Facades\Blade',
		'Bus' => 'Illuminate\Support\Facades\Bus',
		'Cache' => 'Illuminate\Support\Facades\Cache',
		'Config' => 'Illuminate\Support\Facades\Config',
		'Cookie' => 'Illuminate\Support\Facades\Cookie',
		'Crypt' => 'Illuminate\Support\Facades\Crypt',
		'DB' => 'Illuminate\Support\Facades\DB',
		'Eloquent' => 'Illuminate\Database\Eloquent\Model',
		'Event' => 'Illuminate\Support\Facades\Event',
		'File' => 'Illuminate\Support\Facades\File',
		'Hash' => 'Illuminate\Support\Facades\Hash',
		'Input' => 'Illuminate\Support\Facades\Input',
		'Inspiring' => 'Illuminate\Foundation\Inspiring',
		'Lang' => 'Illuminate\Support\Facades\Lang',
		'Log' => 'Illuminate\Support\Facades\Log',
		'Mail' => 'Illuminate\Support\Facades\Mail',
		'Password' => 'Illuminate\Support\Facades\Password',
		'Queue' => 'Illuminate\Support\Facades\Queue',
		'Redirect' => 'Illuminate\Support\Facades\Redirect',
		'Request' => 'Illuminate\Support\Facades\Request',
		'Response' => 'Illuminate\Support\Facades\Response',
		'Route' => 'Illuminate\Support\Facades\Route',
		'Schema' => 'Illuminate\Support\Facades\Schema',
		'Session' => 'Illuminate\Support\Facades\Session',
		'Storage' => 'Illuminate\Support\Facades\Storage',
		'URL' => 'Illuminate\Support\Facades\URL',
		'Validator' => 'Illuminate\Support\Facades\Validator',
		'View' => 'Illuminate\Support\Facades\View',
		'DynamicMenu' => 'App\Classes\DynamicMenu',
		'Activation' => 'Cartalyst\Sentinel\Laravel\Facades\Activation',
		'Reminder' => 'Cartalyst\Sentinel\Laravel\Facades\Reminder',
		'Sentinel' => 'Cartalyst\Sentinel\Laravel\Facades\Sentinel',
		'Form' => 'Collective\Html\FormFacade',
		'Html' => 'Collective\Html\HtmlFacade',
		'Image' => 'Intervention\Image\Facades\Image',
		'Excel' => 'Maatwebsite\Excel\Facades\Excel',
		'PDF' => 'Elibyy\TCPDF\Facades\TCPDF',
		'Location' => 'Stevebauman\Location\Facades\Location',
		'Usps' => 'Usps\Facades\Usps',
		'Socialite' => Laravel\Socialite\Facades\Socialite::class,
		'Cart' => Darryldecode\Cart\Facades\CartFacade::class,
		'PayPal' => Srmklive\PayPal\Facades\PayPal::class,		
		'Recaptcha' => Greggilbert\Recaptcha\Facades\Recaptcha::class,
		'GeoIP' => \Torann\GeoIP\Facades\GeoIP::class,
		'Vision'    =>  \yedincisenol\Vision\LaravelFacede::class,
		'Datatables' => Yajra\Datatables\Datatables::class

	],

];
