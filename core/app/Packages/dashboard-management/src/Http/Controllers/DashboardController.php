<?php
namespace DashboardManage\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Response;
use Sentinel;


// use DeviceManage\Models\Device;


class DashboardController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Dashboard Controller
	|--------------------------------------------------------------------------
	|
	| 
	| 
	| 
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
	 * Show Subscribe Chart Page.
	 *
	 * @return Response
	 */
	public function subsribe()
	{
		return view('DashboardManage::chart.subscribe-chart');
	}
	
	public function subsribeData()
	{
		
		$label=['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
		$datasets=array();

		//Create Dataset Array
		//DatatSet 1
		$dataset_1=array(
			"label"=>"dialog",
			"data"=> array(12, 19, 3, 5, 2, 3),
			'backgroundColor'=>array('rgba(255, 99, 132, 0.2)','rgba(255, 99, 132, 0.2)'),
			"borderColor"=>array('rgba(255, 99, 132, 1)','rgba(255, 99, 132, 1)'),
			"borderWidth"=>1
		);
		$dataset_2=array(
			"label"=>"Mobitel",
			"data"=> array(12, 19, 3, 5, 2, 3),
			'backgroundColor'=>array('rgba(44, 130, 201, 1)','rgba(44, 130, 201, 1)'),
			"borderColor"=>array('rgba(44, 130, 201, 1)','rgba(44, 130, 201, 1)'),
			"borderWidth"=>1
		);
		$dataset_3=array(
			"label"=>"Hutch",
			"data"=> array(12, 19, 3, 5, 2, 3),
			'backgroundColor'=>array('rgba(232, 126, 4, 1)','rgba(232, 126, 4, 1)'),
			"borderColor"=>array('rgba(232, 126, 4, 1)','rgba(232, 126, 4, 1)'),
			"borderWidth"=>1
		);

		//Assign to Dataset Array
		array_push($datasets,$dataset_1);
		array_push($datasets,$dataset_2);
		array_push($datasets,$dataset_3);

		//Finel Array For Chart
		$chart_data=array(
			'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;
	}
	
	

}
