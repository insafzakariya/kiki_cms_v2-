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
			'apple_border_bar_colour'=>array(),

			'mobitel'=>array(),
			'mobitel_bar_colour'=>array(),
			'mobitel_border_bar_colour'=>array(),

			'overall'=>array(),
			'overall_bar_colour'=>array(),
			'overall_border_bar_colour'=>array()
		);
		$label=[];
		//Month List
		$satrt_date=str_replace('/','-', $start_month.'-01');
		$end_first_date=str_replace('/','-', $end_month.'-01');
		// $end_date=str_replace('/','-', $end_month.'-31');
		$end_date_initial=date("Y-m-t", strtotime($end_first_date));
		$end_date = date('Y-m-d', strtotime($end_date_initial . ' +1 day'));
		$result = CarbonPeriod::create($satrt_date, '1 month', $end_date_initial);

        foreach ($result as $dt) {
			array_push($label,$dt->format("M-Y"));
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

			array_push($data_array['mobitel'],0);
			array_push($data_array['mobitel_bar_colour'],Config::get('chart.service_provider.mobitel.rgba'));
			array_push($data_array['mobitel_border_bar_colour'],Config::get('chart.service_provider.mobitel.rgba'));
			
			array_push($data_array['overall'],0);
			array_push($data_array['overall_bar_colour'],Config::get('chart.service_provider.overall.rgba'));
			array_push($data_array['overall_border_bar_colour'],Config::get('chart.service_provider.overall.rgba'));
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
			$data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}

		//Hutch Subscribe Month Wise Query

		$hutch_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
		from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
		and type = 'HUTCH' and subscribe = 1 and status = 1 group by year,month");

		foreach($hutch_subscribe_list AS $hSubscribe){
			$key = array_search ($hSubscribe->year.'-'.str_pad($hSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['hutch'][$key]=$hSubscribe->subscriber_count;
			$data_array['overall'][$key]=($data_array['overall'][$key]+$hSubscribe->subscriber_count);
		}

		//Apple Subscribe Month Wise Query

		$appel_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
		from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
		and type = 'APPLE' and subscribe = 1 and status = 1 group by year,month");

		foreach($appel_subscribe_list AS $aSubscribe){
			$key = array_search ($aSubscribe->year.'-'.str_pad($aSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['apple'][$key]=$aSubscribe->subscriber_count;
			$data_array['overall'][$key]=($data_array['overall'][$key]+$aSubscribe->subscriber_count);
		}
		if($satrt_date>'2020-11-10'){
			//MOBITEl SUBSCRIBE Query BEFORE '2020-11-10 08:46:23'
			$mobitel_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
			from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
			and type = 'MOBITEL' and subscribe = 1 and status = 1 group by year,month");

			foreach($mobitel_subscribe_list AS $mSubscribe){
				$key = array_search ($mSubscribe->year.'-'.str_pad($mSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
				$data_array['mobitel'][$key]=$mSubscribe->subscriber_count;
				$data_array['overall'][$key]=($data_array['overall'][$key]+$mSubscribe->subscriber_count);
			}

		}else{
			//MOBITEl SUBSCRIBE Query BEFORE '2020-11-10 08:46:23' 
		    $mobitel_subscribe_list=DB::select("SELECT Count(a.vid) AS subscriber_count,
			Cast(a.creadt AS DATE) AS create_date
			FROM   (SELECT viewer             AS vid,
							Cast(date AS DATE) AS creadt
					FROM   susila_db.viewer_subscription
					WHERE  date BETWEEN Cast("."'".$satrt_date."'"."  AS DATE) AND Cast(
						"."'".$end_date."'"." AS DATE)
							AND subscriptiontype = 'MOBITEL_ADD_TO_BILL'
					UNION
					SELECT viewer_id                AS vid,
							Cast(createdate AS DATE) AS creadt
					FROM   subscription_data
					WHERE  createdate BETWEEN Cast('2020-11-10 08:46:23' AS DATETIME) AND
											Cast(
												"."'".$end_date."'"." AS DATE)
							AND type = 'MOBITEL'
							AND subscribe = 1
							AND status = 1) a
			GROUP  BY a.creadt,create_date");

			////MOBITEl SUBSCRIBE Query AFTER '2020-11-10 08:46:23'

			foreach($mobitel_subscribe_list AS $mSubscribe){
				$year = date('Y', strtotime($mSubscribe->create_date));
				$month = date('m', strtotime($mSubscribe->create_date));

				$key = array_search ($year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT), $data_array['months']);
				$data_array['mobitel'][$key]=($data_array['mobitel'][$key]+$mSubscribe->subscriber_count);
				$data_array['overall'][$key]=($data_array['overall'][$key]+$mSubscribe->subscriber_count);
			}
		}
		

		// return $data_array;
		//Create Dataset Array
		//DatatSet 1
		$dialog_dataset=array(
			"label"=>"dialog",
			"data"=> $data_array['dialog'],
			'backgroundColor'=>$data_array['dialog_bar_colour'],
			"borderColor"=>$data_array['dialog_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> true,
		);
		$hutch_dataset=array(
			"label"=>"Hutch",
			"data"=> $data_array['hutch'],
			'backgroundColor'=>$data_array['hutch_bar_colour'],
			"borderColor"=>$data_array['hutch_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> true,
		);
		$apple_dataset=array(
			"label"=>"Apple",
			"data"=> $data_array['apple'],
			'backgroundColor'=>$data_array['apple_bar_colour'],
			"borderColor"=>$data_array['apple_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> true,
		);
		$mobitel_dataset=array(
			"label"=>"Mobitel",
			"data"=> $data_array['mobitel'],
			'backgroundColor'=>$data_array['mobitel_bar_colour'],
			"borderColor"=>$data_array['mobitel_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> true,
		);
		$overall_dataset=array(
			"label"=>"Overall",
			"data"=> $data_array['overall'],
			'backgroundColor'=>$data_array['overall_bar_colour'],
			"borderColor"=>$data_array['overall_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> true,
		);

		$dataset_2=array(
			"label"=>"Mobitel",
			"data"=> array(12, 19, 3, 5, 2, 3),
			'backgroundColor'=>array('rgba(44, 130, 201, 1)','rgba(44, 130, 201, 1)'),
			"borderColor"=>array('rgba(44, 130, 201, 1)','rgba(44, 130, 201, 1)'),
			"borderWidth"=>1
		);
		

		//Assign to Dataset Array
		array_push($datasets,$overall_dataset);
		array_push($datasets,$dialog_dataset);
		array_push($datasets,$hutch_dataset);
		array_push($datasets,$apple_dataset);
		array_push($datasets,$mobitel_dataset);
		
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
