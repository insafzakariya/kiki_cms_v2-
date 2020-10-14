<?php namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Request;
use Route;
use Sentinel;
use Session;


abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;


}
