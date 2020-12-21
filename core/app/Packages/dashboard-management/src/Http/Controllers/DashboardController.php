<?php
namespace DashboardManage\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Response;
use Sentinel;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Config;
use Illuminate\Http\Request;

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
		// $date = date_parse('July');
		// return  $date['month'];
		return view('DashboardManage::chart.subscribe-chart');
	}
	
	public function subsribeData(Request $request)
	{ 
		// return date('Y-M', strtotime($request->get('start_date')));
		$start_month= implode('/', array_reverse(explode('/', $request->get('start_date'))));
		$end_month= implode('/', array_reverse(explode('/', $request->get('end_date'))));
	
		$data_array=array(
			'months'=>array(),
			'dialog'=>array(),
			'dialog_bar_colour'=>array(),
			'dialog_border_bar_colour'=>array(),
			'hutch'=>array(),
			'hutch_bar_colour'=>array(),
			'hutch_border_bar_colour'=>array(),
			'apple'=>array(),
			'apple_bar_colour'=>array(),
			'apple_border_bar_colour'=>array()
		);
		$label=[];
		//Month List
		$satrt_date=str_replace('/','-', $start_month.'-01');
		$end_date=str_replace('/','-', $end_month.'-31');
		$result = CarbonPeriod::create($satrt_date, '1 month', $end_date);

        foreach ($result as $dt) {
			array_push($label,$dt->format("Y-m"));
			array_push($data_array['months'],$dt->format("Y-m"));
			array_push($data_array['dialog'],0);
			array_push($data_array['dialog_bar_colour'],Config::get('chart.service_provider.dialog.rgba'));
			array_push($data_array['dialog_border_bar_colour'],Config::get('chart.service_provider.dialog.rgba'));
			
			array_push($data_array['hutch'],0);
			array_push($data_array['hutch_bar_colour'],Config::get('chart.service_provider.hutch.rgba'));
			array_push($data_array['hutch_border_bar_colour'],Config::get('chart.service_provider.hutch.rgba'));
			
			array_push($data_array['apple'],0);
			array_push($data_array['apple_bar_colour'],Config::get('chart.service_provider.apple.rgba'));
			array_push($data_array['apple_border_bar_colour'],Config::get('chart.service_provider.apple.rgba'));
			// $data_array[$dt->format("Y-m")]=array('dialog'=>0,'mobitel'=>0,'hutch'=>0,'appel'=>0);
		}
		// return $data_array;
		
		$datasets=array();

		//Dialog Subscribe Month Wise Query
		
		$dialog_subscribe_list = DB::select("SELECT count(viwer_id) as subscriber_count,  MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
		FROM ideabiz WHERE subscribe = 1 and createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date)
		group by year,month");

		foreach($dialog_subscribe_list AS $dSubscribe){
			$key = array_search ($dSubscribe->year.'-'.str_pad($dSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			// return $data_array['months'];
			// $data_array[$dSubscribe->year.'-'.str_pad($dSubscribe->month, 2, '0', STR_PAD_LEFT)]['dialog']=$dSubscribe->subscriber_count;
			$data_array['dialog'][$key]=$dSubscribe->subscriber_count;
		}

		//Hutch Subscribe Month Wise Query

		$hutch_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
		from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
		and type = 'HUTCH' and subscribe = 1 and status = 1 group by year,month");

		foreach($hutch_subscribe_list AS $hSubscribe){
			$key = array_search ($hSubscribe->year.'-'.str_pad($hSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['hutch'][$key]=$hSubscribe->subscriber_count;
		}

		//Apple Subscribe Month Wise Query

		$hutch_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
		from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
		and type = 'APPLE' and subscribe = 1 and status = 1 group by year,month");

		foreach($hutch_subscribe_list AS $hSubscribe){
			$key = array_search ($hSubscribe->year.'-'.str_pad($hSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['apple'][$key]=$hSubscribe->subscriber_count;
		}
		// return $data_array;
		//Create Dataset Array
		//DatatSet 1
		$dialog_dataset=array(
			"label"=>"dialog",
			"data"=> $data_array['dialog'],
			'backgroundColor'=>$data_array['dialog_bar_colour'],
			"borderColor"=>$data_array['dialog_border_bar_colour'],
			"borderWidth"=>1
		);
		$hutch_dataset=array(
			"label"=>"Hutch",
			"data"=> $data_array['hutch'],
			'backgroundColor'=>$data_array['hutch_bar_colour'],
			"borderColor"=>$data_array['hutch_border_bar_colour'],
			"borderWidth"=>1
		);
		$apple_dataset=array(
			"label"=>"Apple",
			"data"=> $data_array['apple'],
			'backgroundColor'=>$data_array['apple_bar_colour'],
			"borderColor"=>$data_array['apple_border_bar_colour'],
			"borderWidth"=>1
		);

		$dataset_2=array(
			"label"=>"Mobitel",
			"data"=> array(12, 19, 3, 5, 2, 3),
			'backgroundColor'=>array('rgba(44, 130, 201, 1)','rgba(44, 130, 201, 1)'),
			"borderColor"=>array('rgba(44, 130, 201, 1)','rgba(44, 130, 201, 1)'),
			"borderWidth"=>1
		);
		

		//Assign to Dataset Array
		array_push($datasets,$dialog_dataset);
		array_push($datasets,$hutch_dataset);
		array_push($datasets,$apple_dataset);
		// array_push($datasets,$dataset_2);
		// array_push($datasets,$dataset_3);

		//Finel Array For Chart
		$chart_data=array(
			'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;
	}
	
	

}
