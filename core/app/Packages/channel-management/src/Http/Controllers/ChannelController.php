<?php

namespace ChannelManage\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\Policy;
use Carbon\Carbon;
use Config;
use Datatables;
use Exception;
use File;
use Illuminate\Http\Request;
use Log;
use Response;
use Session;

class ChannelController extends Controller
{

   

    public function __construct()
    {
        
    }

    public function index()
    {
        return view('ChannelManage::add');
    }
 
   
  

}
