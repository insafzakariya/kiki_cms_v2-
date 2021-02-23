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
		
		// $dialog_subscribe_list = DB::select("SELECT count(viwer_id) as subscriber_count,  MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
		// FROM ideabiz WHERE subscribe = 1 and createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date)
		// group by year,month");

		$dialog_subscribe_list = DB::select("select count(distinct viewer_id)  as subscriber_count,MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
		from subscription_invoice where createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) and type = 'DIALOG' and amount > 0
		and success = 1 and status = 1 group by year,month");

		foreach($dialog_subscribe_list AS $dSubscribe){
			$key = array_search ($dSubscribe->year.'-'.str_pad($dSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			// return $data_array['months'];
			// $data_array[$dSubscribe->year.'-'.str_pad($dSubscribe->month, 2, '0', STR_PAD_LEFT)]['dialog']=$dSubscribe->subscriber_count;
			$data_array['dialog'][$key]=$dSubscribe->subscriber_count;
			$data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}

		//Hutch Subscribe Month Wise Query

		// $hutch_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
		// from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
		// and type = 'HUTCH' and subscribe = 1 and status = 1 group by year,month");

		$hutch_subscribe_list = DB::select("select count(distinct viewer_id)  as subscriber_count,MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
		from subscription_invoice where createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) and type = 'HUTCH' and amount > 0
		and success = 1 and status = 1 group by year,month");

		foreach($hutch_subscribe_list AS $hSubscribe){
			$key = array_search ($hSubscribe->year.'-'.str_pad($hSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['hutch'][$key]=$hSubscribe->subscriber_count;
			$data_array['overall'][$key]=($data_array['overall'][$key]+$hSubscribe->subscriber_count);
		}

		//Apple Subscribe Month Wise Query

		// $appel_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
		// from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
		// and type = 'APPLE' and subscribe = 1 and status = 1 group by year,month");

		$appel_subscribe_list = DB::select("select count(distinct viewer_id)  as subscriber_count,MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
		from subscription_invoice where createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) and type = 'APPLE' and amount > 0
		and success = 1 and status = 1 group by year,month");
		
		foreach($appel_subscribe_list AS $aSubscribe){
			$key = array_search ($aSubscribe->year.'-'.str_pad($aSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['apple'][$key]=$aSubscribe->subscriber_count;
			$data_array['overall'][$key]=($data_array['overall'][$key]+$aSubscribe->subscriber_count);
		}

		//MOBITEL
		$mobitel_subscribe_list = DB::select("select count(distinct viewer_id)  as subscriber_count,MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
			from subscription_invoice where createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) and type = 'MOBITEL' and amount > 0
			and success = 1 and status = 1 group by year,month");

		foreach($mobitel_subscribe_list AS $mSubscribe){
			$key = array_search ($mSubscribe->year.'-'.str_pad($mSubscribe->month, 2, '0', STR_PAD_LEFT), $data_array['months']);
			$data_array['mobitel'][$key]=$mSubscribe->subscriber_count;
			$data_array['overall'][$key]=($data_array['overall'][$key]+$mSubscribe->subscriber_count);
		}
		
		//THIS IS OLD CONDITION
		/*
		if($satrt_date>'2020-11-10'){
			//MOBITEl SUBSCRIBE Query BEFORE '2020-11-10 08:46:23'
			// $mobitel_subscribe_list=DB::select("select count(viewer_id)  as subscriber_count, MONTH(cast(createDate as date))  as month ,YEAR(cast(createDate as date)) as year
			// from subscription_data where createDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) 
			// and type = 'MOBITEL' and subscribe = 1 and status = 1 group by year,month");

			$mobitel_subscribe_list = DB::select("select count(distinct viewer_id)  as subscriber_count,MONTH(cast(createdDate as date)) as month,YEAR(cast(createdDate as date)) as year
			from subscription_invoice where createdDate between cast("."'".$satrt_date."'"." as date) and cast("."'".$end_date."'"." as date) and type = 'MOBITEL' and amount > 0
			and success = 1 and status = 1 group by year,month");
		

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
		}*/
		
		

		// return $data_array;
		//Create Dataset Array
		//DatatSet 1
		$dialog_dataset=array(
			"label"=>"dialog",
			"data"=> $data_array['dialog'],
			'backgroundColor'=>$data_array['dialog_bar_colour'],
			"borderColor"=>$data_array['dialog_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_dataset=array(
			"label"=>"Hutch",
			"data"=> $data_array['hutch'],
			'backgroundColor'=>$data_array['hutch_bar_colour'],
			"borderColor"=>$data_array['hutch_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$apple_dataset=array(
			"label"=>"Apple",
			"data"=> $data_array['apple'],
			'backgroundColor'=>$data_array['apple_bar_colour'],
			"borderColor"=>$data_array['apple_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$mobitel_dataset=array(
			"label"=>"Mobitel",
			"data"=> $data_array['mobitel'],
			'backgroundColor'=>$data_array['mobitel_bar_colour'],
			"borderColor"=>$data_array['mobitel_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$overall_dataset=array(
			"label"=>"Overall",
			"data"=> $data_array['overall'],
			'backgroundColor'=>$data_array['overall_bar_colour'],
			"borderColor"=>$data_array['overall_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
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

	public function dailyTransaction()
	{
		return view('DashboardManage::chart.dailytransaction-chart');
	}
	public function dailyTransactionData(Request $request)
	{
		$start_date=$request->get('start_date');
		$end_date_initial=$request->get('end_date');
		$end_date = date('Y-m-d', strtotime($end_date_initial . ' +1 day'));

		$data_array=array(
			'days'=>array(),

			'TOTAL'=>array(),
			'TOTAL_bar_colour'=>array(),
			'TOTAL_border_bar_colour'=>array(),

			'DIALOG_5'=>array(),
			'DIALOG_5_bar_colour'=>array(),
			'DIALOG_5_border_bar_colour'=>array(),
			
			'DIALOG_25'=>array(),
			'DIALOG_25_bar_colour'=>array(),
			'DIALOG_25_border_bar_colour'=>array(),
			
			'DIALOG_99'=>array(),
			'DIALOG_99_bar_colour'=>array(),
			'DIALOG_99_border_bar_colour'=>array(),

			'HUTCH_5'=>array(),
			'HUTCH_5_bar_colour'=>array(),
			'HUTCH_5_border_bar_colour'=>array(),
			
			'HUTCH_25'=>array(),
			'HUTCH_25_bar_colour'=>array(),
			'HUTCH_25_border_bar_colour'=>array(),

			'HUTCH_99'=>array(),
			'HUTCH_99_bar_colour'=>array(),
			'HUTCH_99_border_bar_colour'=>array(),

			'MOBITEL_5'=>array(),
			'MOBITEL_5_bar_colour'=>array(),
			'MOBITEL_5_border_bar_colour'=>array(),
			
			'APPLE_6'=>array(),
			'APPLE_6_bar_colour'=>array(),
			'APPLE_6_border_bar_colour'=>array(),

			'APPLE_10'=>array(),
			'APPLE_10_bar_colour'=>array(),
			'APPLE_10_border_bar_colour'=>array(),
			
			'APPLE_15'=>array(),
			'APPLE_15_bar_colour'=>array(),
			'APPLE_15_border_bar_colour'=>array(),
		);

		$label=[];
		$result = CarbonPeriod::create($start_date, '1 day', $end_date_initial);
		foreach ($result as $dt) {
			array_push($label,$dt->format("d-D-M-Y"));
			array_push($data_array['days'],$dt->format("Y-m-d"));

			array_push($data_array['TOTAL'],0);
			array_push($data_array['TOTAL_bar_colour'],Config::get('chart.service_provider.overall.rgba'));
			array_push($data_array['TOTAL_border_bar_colour'],Config::get('chart.service_provider.overall.rgba'));

			array_push($data_array['DIALOG_5'],0);
			array_push($data_array['DIALOG_5_bar_colour'],Config::get('chart.service_provider.dialog_5.rgba'));
			array_push($data_array['DIALOG_5_border_bar_colour'],Config::get('chart.service_provider.dialog_5.rgba'));
			
			array_push($data_array['DIALOG_25'],0);
			array_push($data_array['DIALOG_25_bar_colour'],Config::get('chart.service_provider.dialog_25.rgba'));
			array_push($data_array['DIALOG_25_border_bar_colour'],Config::get('chart.service_provider.dialog_25.rgba'));
			
			array_push($data_array['DIALOG_99'],0);
			array_push($data_array['DIALOG_99_bar_colour'],Config::get('chart.service_provider.dialog_99.rgba'));
			array_push($data_array['DIALOG_99_border_bar_colour'],Config::get('chart.service_provider.dialog_99.rgba'));
			
			array_push($data_array['HUTCH_5'],0);
			array_push($data_array['HUTCH_5_bar_colour'],Config::get('chart.service_provider.hutch_5.rgba'));
			array_push($data_array['HUTCH_5_border_bar_colour'],Config::get('chart.service_provider.hutch_5.rgba'));
			
			array_push($data_array['HUTCH_25'],0);
			array_push($data_array['HUTCH_25_bar_colour'],Config::get('chart.service_provider.hutch_25.rgba'));
			array_push($data_array['HUTCH_25_border_bar_colour'],Config::get('chart.service_provider.hutch_25.rgba'));
			
			array_push($data_array['HUTCH_99'],0);
			array_push($data_array['HUTCH_99_bar_colour'],Config::get('chart.service_provider.hutch_99.rgba'));
			array_push($data_array['HUTCH_99_border_bar_colour'],Config::get('chart.service_provider.hutch_99.rgba'));

			array_push($data_array['MOBITEL_5'],0);
			array_push($data_array['MOBITEL_5_bar_colour'],Config::get('chart.service_provider.mobitel_5.rgba'));
			array_push($data_array['MOBITEL_5_border_bar_colour'],Config::get('chart.service_provider.mobitel_5.rgba'));
			
			array_push($data_array['APPLE_6'],0);
			array_push($data_array['APPLE_6_bar_colour'],Config::get('chart.service_provider.apple_6.rgba'));
			array_push($data_array['APPLE_6_border_bar_colour'],Config::get('chart.service_provider.apple_6.rgba'));
			
			array_push($data_array['APPLE_10'],0);
			array_push($data_array['APPLE_10_bar_colour'],Config::get('chart.service_provider.apple_10.rgba'));
			array_push($data_array['APPLE_10_border_bar_colour'],Config::get('chart.service_provider.apple_10.rgba'));
			
			array_push($data_array['APPLE_15'],0);
			array_push($data_array['APPLE_15_bar_colour'],Config::get('chart.service_provider.apple_15.rgba'));
			array_push($data_array['APPLE_15_border_bar_colour'],Config::get('chart.service_provider.apple_15.rgba'));
		}
	
		$datasets=array();

		//Transaction Data Retreview
		$transaction_data_list = DB::select("select count(viewer_id)  as subscriber_count, amount as package,type, cast(createdDate as date)  as create_date 
		from subscription_invoice where createdDate between cast("."'".$start_date."'"." as date) and cast("."'".$end_date."'"." as date) and amount > 0
		and success = 1 and status = 1 group by create_date, package,type");

		foreach($transaction_data_list AS $data){
			$key = array_search ($data->create_date, $data_array['days']);
			$data_array[$data->type.'_'.$data->package][$key]=$data->subscriber_count;
			$data_array['TOTAL'][$key]=$data_array['TOTAL'][$key]+$data->subscriber_count;
			// $data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}
		$total_dataset=array(
			"label"=>"TOTAL",
			"data"=> $data_array['TOTAL'],
			'backgroundColor'=>$data_array['TOTAL_bar_colour'],
			"borderColor"=>$data_array['TOTAL_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$dialog_5_dataset=array(
			"label"=>"Dialog Rs 5",
			"data"=> $data_array['DIALOG_5'],
			'backgroundColor'=>$data_array['DIALOG_5_bar_colour'],
			"borderColor"=>$data_array['DIALOG_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$dialog_25_dataset=array(
			"label"=>"Dialog Rs 25",
			"data"=> $data_array['DIALOG_25'],
			'backgroundColor'=>$data_array['DIALOG_25_bar_colour'],
			"borderColor"=>$data_array['DIALOG_25_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$dialog_99_dataset=array(
			"label"=>"Dialog Rs 99",
			"data"=> $data_array['DIALOG_99'],
			'backgroundColor'=>$data_array['DIALOG_99_bar_colour'],
			"borderColor"=>$data_array['DIALOG_99_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_5_dataset=array(
			"label"=>"Hutch Rs 5",
			"data"=> $data_array['HUTCH_5'],
			'backgroundColor'=>$data_array['HUTCH_5_bar_colour'],
			"borderColor"=>$data_array['HUTCH_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$hutch_25_dataset=array(
			"label"=>"Hutch Rs 25",
			"data"=> $data_array['HUTCH_25'],
			'backgroundColor'=>$data_array['HUTCH_25_bar_colour'],
			"borderColor"=>$data_array['HUTCH_25_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_99_dataset=array(
			"label"=>"Hucth Rs 99",
			"data"=> $data_array['HUTCH_99'],
			'backgroundColor'=>$data_array['HUTCH_99_bar_colour'],
			"borderColor"=>$data_array['HUTCH_99_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$mobitel_5_dataset=array(
			"label"=>"Mobitel Rs 5",
			"data"=> $data_array['MOBITEL_5'],
			'backgroundColor'=>$data_array['MOBITEL_5_bar_colour'],
			"borderColor"=>$data_array['MOBITEL_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$apple_6_dataset=array(
			"label"=>"APPLE $6",
			"data"=> $data_array['APPLE_6'],
			'backgroundColor'=>$data_array['APPLE_6_bar_colour'],
			"borderColor"=>$data_array['APPLE_6_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$apple_10_dataset=array(
			"label"=>"APPLE $10",
			"data"=> $data_array['APPLE_10'],
			'backgroundColor'=>$data_array['APPLE_10_bar_colour'],
			"borderColor"=>$data_array['APPLE_10_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		
		$apple_15_dataset=array(
			"label"=>"APPLE $15",
			"data"=> $data_array['APPLE_15'],
			'backgroundColor'=>$data_array['APPLE_15_bar_colour'],
			"borderColor"=>$data_array['APPLE_15_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		//Assign to Dataset Array
		array_push($datasets,$dialog_5_dataset);
		array_push($datasets,$dialog_25_dataset);
		array_push($datasets,$dialog_99_dataset);

		array_push($datasets,$hutch_5_dataset);
		array_push($datasets,$hutch_25_dataset);
		array_push($datasets,$hutch_99_dataset);

		array_push($datasets,$mobitel_5_dataset);
		array_push($datasets,$apple_6_dataset);
		array_push($datasets,$apple_10_dataset);
		array_push($datasets,$apple_15_dataset);

		array_push($datasets,$total_dataset);

		$chart_data=array(
			'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;
	}

	public function dailyRevenue()
	{
		return view('DashboardManage::chart.dailyRevenu-chart');
	}

	public function dailyRevenueData(Request $request)
	{
		$start_date=$request->get('start_date');
		$end_date_initial=$request->get('end_date');
		$end_date = date('Y-m-d', strtotime($end_date_initial . ' +1 day'));

		$data_array=array(
			'days'=>array(),

			'TOTAL'=>array(),
			'DIALOG'=>array(),
			'HUTCH'=>array(),
			'MOBITEL'=>array(),
			'APPLE'=>array(),

		);

		$label=[];
		$result = CarbonPeriod::create($start_date, '1 day', $end_date_initial);
		foreach ($result as $dt) {
			array_push($label,$dt->format("d-D-M-Y"));
			array_push($data_array['days'],$dt->format("Y-m-d"));

			array_push($data_array['TOTAL'],0);
			array_push($data_array['DIALOG'],0);
			array_push($data_array['HUTCH'],0);

			array_push($data_array['MOBITEL'],0);
			array_push($data_array['APPLE'],0);
			
		}
	
		$datasets=array();

		//Transaction Data Retreview
		$transaction_data_list = DB::select("select count(viewer_id)  as subscriber_count, amount as package,type, cast(createdDate as date)  as create_date 
		from subscription_invoice where createdDate between cast("."'".$start_date."'"." as date) and cast("."'".$end_date."'"." as date) and amount > 0
		and success = 1 and status = 1 and type !='APPLE' group by create_date, package,type");

		foreach($transaction_data_list AS $data){
			$key = array_search ($data->create_date, $data_array['days']);
			$data_array[$data->type][$key]=$data_array[$data->type][$key]+($data->package*$data->subscriber_count);
			$data_array['TOTAL'][$key]=$data_array['TOTAL'][$key]+($data->package*$data->subscriber_count);
			// $data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}
		$total_dataset=array(
			"label"=>"TOTAL",
			"data"=> $data_array['TOTAL'],
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.overall.rgba'),
			// 'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.overall.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.overall.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);

		$dialog_dataset=array(
			"label"=>"Dialog",
			"data"=> $data_array['DIALOG'],
			"borderWidth"=>1,
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.dialog.rgba'),
			'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.dialog.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.dialog.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);


		$hutch_dataset=array(
			"label"=>"Hutch",
			"data"=> $data_array['HUTCH'],
			"borderWidth"=>1,
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.hutch.rgba'),
			'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.hutch.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.hutch.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);

		
		$mobitel_dataset=array(
			"label"=>"Mobitel",
			"data"=> $data_array['MOBITEL'],
			"borderWidth"=>1,
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.mobitel.rgba'),
			'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.mobitel.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.mobitel.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);

		$apple_dataset=array(
			"label"=>"APPLE $6",
			"data"=> $data_array['APPLE'],
			"borderWidth"=>1,
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.appel.rgba'),
			'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.appel.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.appel.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);

		//Assign to Dataset Array
		array_push($datasets,$dialog_dataset);
		array_push($datasets,$hutch_dataset);
		array_push($datasets,$mobitel_dataset);
		// array_push($datasets,$apple_dataset);

		array_push($datasets,$total_dataset);

		$chart_data=array(
			// 'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;
	}

	public function newSubscriberWithFreeTrial()
	{
		return view('DashboardManage::chart.newsubscriberwithfreetrial-chart');
	}
	public function newSubscriberWithFreeTrialData(Request $request)
	{
		$start_date=$request->get('start_date');
		$end_date_initial=$request->get('end_date');
		$end_date = date('Y-m-d', strtotime($end_date_initial . ' +1 day'));

		$data_array=array(
			'days'=>array(),

			'TOTAL'=>array(),
			'TOTAL_bar_colour'=>array(),
			'TOTAL_border_bar_colour'=>array(),

			'DIALOG_5'=>array(),
			'DIALOG_5_bar_colour'=>array(),
			'DIALOG_5_border_bar_colour'=>array(),
			
			'DIALOG_25'=>array(),
			'DIALOG_25_bar_colour'=>array(),
			'DIALOG_25_border_bar_colour'=>array(),
			
			'DIALOG_99'=>array(),
			'DIALOG_99_bar_colour'=>array(),
			'DIALOG_99_border_bar_colour'=>array(),

			'HUTCH_5'=>array(),
			'HUTCH_5_bar_colour'=>array(),
			'HUTCH_5_border_bar_colour'=>array(),
			
			'HUTCH_25'=>array(),
			'HUTCH_25_bar_colour'=>array(),
			'HUTCH_25_border_bar_colour'=>array(),

			'HUTCH_99'=>array(),
			'HUTCH_99_bar_colour'=>array(),
			'HUTCH_99_border_bar_colour'=>array(),

			'MOBITEL_5'=>array(),
			'MOBITEL_5_bar_colour'=>array(),
			'MOBITEL_5_border_bar_colour'=>array(),
			
			'APPLE_6'=>array(),
			'APPLE_6_bar_colour'=>array(),
			'APPLE_6_border_bar_colour'=>array(),

			'APPLE_10'=>array(),
			'APPLE_10_bar_colour'=>array(),
			'APPLE_10_border_bar_colour'=>array(),
			
			'APPLE_15'=>array(),
			'APPLE_15_bar_colour'=>array(),
			'APPLE_15_border_bar_colour'=>array(),
			
			'KEELS_5'=>array(),
			'KEELS_5_bar_colour'=>array(),
			'KEELS_5_border_bar_colour'=>array(),

			
		);

		$label=[];
		$result = CarbonPeriod::create($start_date, '1 day', $end_date_initial);
		foreach ($result as $dt) {
			array_push($label,$dt->format("d-D-M-Y"));
			array_push($data_array['days'],$dt->format("Y-m-d"));


			array_push($data_array['TOTAL'],0);
			array_push($data_array['TOTAL_bar_colour'],Config::get('chart.service_provider.overall.rgba'));
			array_push($data_array['TOTAL_border_bar_colour'],Config::get('chart.service_provider.overall.rgba'));

			array_push($data_array['DIALOG_5'],0);
			array_push($data_array['DIALOG_5_bar_colour'],Config::get('chart.service_provider.dialog_5.rgba'));
			array_push($data_array['DIALOG_5_border_bar_colour'],Config::get('chart.service_provider.dialog_5.rgba'));
			
			array_push($data_array['DIALOG_25'],0);
			array_push($data_array['DIALOG_25_bar_colour'],Config::get('chart.service_provider.dialog_25.rgba'));
			array_push($data_array['DIALOG_25_border_bar_colour'],Config::get('chart.service_provider.dialog_25.rgba'));
			
			array_push($data_array['DIALOG_99'],0);
			array_push($data_array['DIALOG_99_bar_colour'],Config::get('chart.service_provider.dialog_99.rgba'));
			array_push($data_array['DIALOG_99_border_bar_colour'],Config::get('chart.service_provider.dialog_99.rgba'));
			
			array_push($data_array['HUTCH_5'],0);
			array_push($data_array['HUTCH_5_bar_colour'],Config::get('chart.service_provider.hutch_5.rgba'));
			array_push($data_array['HUTCH_5_border_bar_colour'],Config::get('chart.service_provider.hutch_5.rgba'));
			
			array_push($data_array['HUTCH_25'],0);
			array_push($data_array['HUTCH_25_bar_colour'],Config::get('chart.service_provider.hutch_25.rgba'));
			array_push($data_array['HUTCH_25_border_bar_colour'],Config::get('chart.service_provider.hutch_25.rgba'));
			
			array_push($data_array['HUTCH_99'],0);
			array_push($data_array['HUTCH_99_bar_colour'],Config::get('chart.service_provider.hutch_99.rgba'));
			array_push($data_array['HUTCH_99_border_bar_colour'],Config::get('chart.service_provider.hutch_99.rgba'));

			array_push($data_array['MOBITEL_5'],0);
			array_push($data_array['MOBITEL_5_bar_colour'],Config::get('chart.service_provider.mobitel_5.rgba'));
			array_push($data_array['MOBITEL_5_border_bar_colour'],Config::get('chart.service_provider.mobitel_5.rgba'));
			
			array_push($data_array['APPLE_6'],0);
			array_push($data_array['APPLE_6_bar_colour'],Config::get('chart.service_provider.apple_6.rgba'));
			array_push($data_array['APPLE_6_border_bar_colour'],Config::get('chart.service_provider.apple_6.rgba'));
			
			array_push($data_array['APPLE_10'],0);
			array_push($data_array['APPLE_10_bar_colour'],Config::get('chart.service_provider.apple_10.rgba'));
			array_push($data_array['APPLE_10_border_bar_colour'],Config::get('chart.service_provider.apple_10.rgba'));
			
			array_push($data_array['APPLE_15'],0);
			array_push($data_array['APPLE_15_bar_colour'],Config::get('chart.service_provider.apple_15.rgba'));
			array_push($data_array['APPLE_15_border_bar_colour'],Config::get('chart.service_provider.apple_15.rgba'));
			
			array_push($data_array['KEELS_5'],0);
			array_push($data_array['KEELS_5_bar_colour'],Config::get('chart.service_provider.keels_5.rgba'));
			array_push($data_array['KEELS_5_border_bar_colour'],Config::get('chart.service_provider.keels_5.rgba'));
		}
	
		$datasets=array();
		//Transaction Data Retreview
		//  "select count(viewer_id)  as subscriber_count, amount as package,type,
		// cast(createDate as date)  as create_date from subscription_data 
		// where createDate between cast("."'".$start_date."'"." as date) and 
		// cast("."'".$end_date."'"." as date)  and subscribe = 1 and status = 1 
		// group by create_date, package,type";
		// echo "JJJ";
		$transaction_data_list = DB::select("select count(viewer_id)  as subscriber_count, amount as package,type,
		 cast(createDate as date)  as create_date from subscription_data 
		 where createDate between cast("."'".$start_date."'"." as date) and 
		 cast("."'".$end_date."'"." as date)  and subscribe = 1 and status = 1 and amount >0
		 group by create_date, package,type");

		foreach($transaction_data_list AS $data){
			$key = array_search ($data->create_date, $data_array['days']);
			$data_array[$data->type.'_'.$data->package][$key]=$data->subscriber_count;
			$data_array['TOTAL'][$key]=$data_array['TOTAL'][$key]+$data->subscriber_count;
			// $data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}
		$total_dataset=array(
			"label"=>"TOTAL",
			"data"=> $data_array['TOTAL'],
			'backgroundColor'=>$data_array['TOTAL_bar_colour'],
			"borderColor"=>$data_array['TOTAL_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$dialog_5_dataset=array(
			"label"=>"Dialog Rs 5",
			"data"=> $data_array['DIALOG_5'],
			'backgroundColor'=>$data_array['DIALOG_5_bar_colour'],
			"borderColor"=>$data_array['DIALOG_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$dialog_25_dataset=array(
			"label"=>"Dialog Rs 25",
			"data"=> $data_array['DIALOG_25'],
			'backgroundColor'=>$data_array['DIALOG_25_bar_colour'],
			"borderColor"=>$data_array['DIALOG_25_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$dialog_99_dataset=array(
			"label"=>"Dialog Rs 99",
			"data"=> $data_array['DIALOG_99'],
			'backgroundColor'=>$data_array['DIALOG_99_bar_colour'],
			"borderColor"=>$data_array['DIALOG_99_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_5_dataset=array(
			"label"=>"Hutch Rs 5",
			"data"=> $data_array['HUTCH_5'],
			'backgroundColor'=>$data_array['HUTCH_5_bar_colour'],
			"borderColor"=>$data_array['HUTCH_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$hutch_25_dataset=array(
			"label"=>"Hutch Rs 25",
			"data"=> $data_array['HUTCH_25'],
			'backgroundColor'=>$data_array['HUTCH_25_bar_colour'],
			"borderColor"=>$data_array['HUTCH_25_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_99_dataset=array(
			"label"=>"Hucth Rs 99",
			"data"=> $data_array['HUTCH_99'],
			'backgroundColor'=>$data_array['HUTCH_99_bar_colour'],
			"borderColor"=>$data_array['HUTCH_99_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$mobitel_5_dataset=array(
			"label"=>"Mobitel Rs 5",
			"data"=> $data_array['MOBITEL_5'],
			'backgroundColor'=>$data_array['MOBITEL_5_bar_colour'],
			"borderColor"=>$data_array['MOBITEL_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$apple_6_dataset=array(
			"label"=>"APPLE $6",
			"data"=> $data_array['APPLE_6'],
			'backgroundColor'=>$data_array['APPLE_6_bar_colour'],
			"borderColor"=>$data_array['APPLE_6_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$apple_10_dataset=array(
			"label"=>"APPLE $10",
			"data"=> $data_array['APPLE_10'],
			'backgroundColor'=>$data_array['APPLE_10_bar_colour'],
			"borderColor"=>$data_array['APPLE_10_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$apple_15_dataset=array(
			"label"=>"APPLE $15",
			"data"=> $data_array['APPLE_15'],
			'backgroundColor'=>$data_array['APPLE_15_bar_colour'],
			"borderColor"=>$data_array['APPLE_15_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$keels_5_dataset=array(
			"label"=>"KEELS Rs 5",
			"data"=> $data_array['KEELS_5'],
			'backgroundColor'=>$data_array['KEELS_5_bar_colour'],
			"borderColor"=>$data_array['KEELS_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		//Assign to Dataset Array
		array_push($datasets,$dialog_5_dataset);
		array_push($datasets,$dialog_25_dataset);
		array_push($datasets,$dialog_99_dataset);

		array_push($datasets,$hutch_5_dataset);
		array_push($datasets,$hutch_25_dataset);
		array_push($datasets,$hutch_99_dataset);

		array_push($datasets,$mobitel_5_dataset);
		array_push($datasets,$apple_6_dataset);
		array_push($datasets,$apple_10_dataset);
		array_push($datasets,$apple_15_dataset);
		array_push($datasets,$keels_5_dataset);

		array_push($datasets,$total_dataset);

		$chart_data=array(
			'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;
	}

	public function retentionChart()
	{
		
		return view('DashboardManage::chart.retention-chart');
	}
	public function retentionChartData(Request $request)
	{
		$start_month= implode('/', array_reverse(explode('/', $request->get('start_date'))));
		
		$start_date=str_replace('/','-', $start_month.'-01');
		$end_date=date("Y-m-t", strtotime($start_date));

		$prev_month_ts = strtotime($start_date.' -1 month');
		$prev_month_start_date = date('Y-m-d', $prev_month_ts);
		$prev_month_end_date = date("Y-m-t", strtotime($prev_month_start_date));
		$prev_month_end_date = date('Y-m-d', strtotime($prev_month_end_date . ' +1 day'));
		

		$data_array=array(
			'days'=>array(),

			'TOTAL'=>array(),
			'TOTAL_bar_colour'=>array(),
			'TOTAL_border_bar_colour'=>array(),

			'DIALOG_5'=>array(),
			'DIALOG_5_bar_colour'=>array(),
			'DIALOG_5_border_bar_colour'=>array(),
			
			'DIALOG_25'=>array(),
			'DIALOG_25_bar_colour'=>array(),
			'DIALOG_25_border_bar_colour'=>array(),
			
			'DIALOG_99'=>array(),
			'DIALOG_99_bar_colour'=>array(),
			'DIALOG_99_border_bar_colour'=>array(),

			'HUTCH_5'=>array(),
			'HUTCH_5_bar_colour'=>array(),
			'HUTCH_5_border_bar_colour'=>array(),
			
			'HUTCH_25'=>array(),
			'HUTCH_25_bar_colour'=>array(),
			'HUTCH_25_border_bar_colour'=>array(),

			'HUTCH_99'=>array(),
			'HUTCH_99_bar_colour'=>array(),
			'HUTCH_99_border_bar_colour'=>array(),

			'MOBITEL_5'=>array(),
			'MOBITEL_5_bar_colour'=>array(),
			'MOBITEL_5_border_bar_colour'=>array(),
			
			'APPLE_6'=>array(),
			'APPLE_6_bar_colour'=>array(),
			'APPLE_6_border_bar_colour'=>array(),

			'APPLE_10'=>array(),
			'APPLE_10_bar_colour'=>array(),
			'APPLE_10_border_bar_colour'=>array(),
			
			'APPLE_15'=>array(),
			'APPLE_15_bar_colour'=>array(),
			'APPLE_15_border_bar_colour'=>array(),

			'KEELS_5'=>array(),
			'KEELS_5_bar_colour'=>array(),
			'KEELS_5_border_bar_colour'=>array(),
		);

		$label=[];
		
			array_push($label,$request->get('start_date'));
			array_push($data_array['days'],$request->get('start_date'));

			array_push($data_array['TOTAL'],0);
			array_push($data_array['TOTAL_bar_colour'],Config::get('chart.service_provider.overall.rgba'));
			array_push($data_array['TOTAL_border_bar_colour'],Config::get('chart.service_provider.overall.rgba'));

			array_push($data_array['DIALOG_5'],0);
			array_push($data_array['DIALOG_5_bar_colour'],Config::get('chart.service_provider.dialog_5.rgba'));
			array_push($data_array['DIALOG_5_border_bar_colour'],Config::get('chart.service_provider.dialog_5.rgba'));
			
			array_push($data_array['DIALOG_25'],0);
			array_push($data_array['DIALOG_25_bar_colour'],Config::get('chart.service_provider.dialog_25.rgba'));
			array_push($data_array['DIALOG_25_border_bar_colour'],Config::get('chart.service_provider.dialog_25.rgba'));
			
			array_push($data_array['DIALOG_99'],0);
			array_push($data_array['DIALOG_99_bar_colour'],Config::get('chart.service_provider.dialog_99.rgba'));
			array_push($data_array['DIALOG_99_border_bar_colour'],Config::get('chart.service_provider.dialog_99.rgba'));
			
			array_push($data_array['HUTCH_5'],0);
			array_push($data_array['HUTCH_5_bar_colour'],Config::get('chart.service_provider.hutch_5.rgba'));
			array_push($data_array['HUTCH_5_border_bar_colour'],Config::get('chart.service_provider.hutch_5.rgba'));
			
			array_push($data_array['HUTCH_25'],0);
			array_push($data_array['HUTCH_25_bar_colour'],Config::get('chart.service_provider.hutch_25.rgba'));
			array_push($data_array['HUTCH_25_border_bar_colour'],Config::get('chart.service_provider.hutch_25.rgba'));
			
			array_push($data_array['HUTCH_99'],0);
			array_push($data_array['HUTCH_99_bar_colour'],Config::get('chart.service_provider.hutch_99.rgba'));
			array_push($data_array['HUTCH_99_border_bar_colour'],Config::get('chart.service_provider.hutch_99.rgba'));

			array_push($data_array['MOBITEL_5'],0);
			array_push($data_array['MOBITEL_5_bar_colour'],Config::get('chart.service_provider.mobitel_5.rgba'));
			array_push($data_array['MOBITEL_5_border_bar_colour'],Config::get('chart.service_provider.mobitel_5.rgba'));
			
			array_push($data_array['APPLE_6'],0);
			array_push($data_array['APPLE_6_bar_colour'],Config::get('chart.service_provider.apple_6.rgba'));
			array_push($data_array['APPLE_6_border_bar_colour'],Config::get('chart.service_provider.apple_6.rgba'));
			
			array_push($data_array['APPLE_10'],0);
			array_push($data_array['APPLE_10_bar_colour'],Config::get('chart.service_provider.apple_10.rgba'));
			array_push($data_array['APPLE_10_border_bar_colour'],Config::get('chart.service_provider.apple_10.rgba'));
			
			array_push($data_array['APPLE_15'],0);
			array_push($data_array['APPLE_15_bar_colour'],Config::get('chart.service_provider.apple_15.rgba'));
			array_push($data_array['APPLE_15_border_bar_colour'],Config::get('chart.service_provider.apple_15.rgba'));

			array_push($data_array['KEELS_5'],0);
			array_push($data_array['KEELS_5_bar_colour'],Config::get('chart.service_provider.keels_5.rgba'));
			array_push($data_array['KEELS_5_border_bar_colour'],Config::get('chart.service_provider.keels_5.rgba'));
		// }
	
		$datasets=array();

		 $privious_transaction_data_list = DB::select("SELECT count(DISTINCT( viewer_id )) AS previous_user_count
							 FROM   subscription_invoice
							 WHERE  success = 1
									AND amount > 0
									AND createddate BETWEEN
									"."'".$prev_month_start_date."'"." AND "."'".$prev_month_end_date."'");

		$transaction_data_list = DB::select("SELECT Count(DISTINCT( viewer_id )) AS user_count,type,amount as package
		FROM   subscription_invoice
		WHERE  viewer_id IN (SELECT DISTINCT( viewer_id )
							 FROM   subscription_invoice
							 WHERE  success = 1
									AND amount > 0
									AND createddate BETWEEN
									"."'".$prev_month_start_date."'"." AND "."'".$prev_month_end_date."'".") 
			   AND success = 1
			   AND amount > 0
			   AND createddate BETWEEN "."'".$start_date."'"." AND "."'".$end_date."'"."  group by type,amount ");
		

		foreach($transaction_data_list AS $data){
			// return $data->user_count;
			$key = array_search ($data->type.'_'.$data->package, $data_array['days']);
			$data_array[$data->type.'_'.$data->package][$key]=$data->user_count;
			$data_array['TOTAL'][$key]=$data_array['TOTAL'][$key]+$data->user_count;
			// $data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}
		$total_dataset=array(
			"label"=>"TOTAL",
			"data"=> $data_array['TOTAL'],
			'backgroundColor'=>$data_array['TOTAL_bar_colour'],
			"borderColor"=>$data_array['TOTAL_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$dialog_5_dataset=array(
			"label"=>"Dialog Rs 5",
			"data"=> $data_array['DIALOG_5'],
			'backgroundColor'=>$data_array['DIALOG_5_bar_colour'],
			"borderColor"=>$data_array['DIALOG_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$dialog_25_dataset=array(
			"label"=>"Dialog Rs 25",
			"data"=> $data_array['DIALOG_25'],
			'backgroundColor'=>$data_array['DIALOG_25_bar_colour'],
			"borderColor"=>$data_array['DIALOG_25_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$dialog_99_dataset=array(
			"label"=>"Dialog Rs 99",
			"data"=> $data_array['DIALOG_99'],
			'backgroundColor'=>$data_array['DIALOG_99_bar_colour'],
			"borderColor"=>$data_array['DIALOG_99_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_5_dataset=array(
			"label"=>"Hutch Rs 5",
			"data"=> $data_array['HUTCH_5'],
			'backgroundColor'=>$data_array['HUTCH_5_bar_colour'],
			"borderColor"=>$data_array['HUTCH_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$hutch_25_dataset=array(
			"label"=>"Hutch Rs 25",
			"data"=> $data_array['HUTCH_25'],
			'backgroundColor'=>$data_array['HUTCH_25_bar_colour'],
			"borderColor"=>$data_array['HUTCH_25_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$hutch_99_dataset=array(
			"label"=>"Hucth Rs 99",
			"data"=> $data_array['HUTCH_99'],
			'backgroundColor'=>$data_array['HUTCH_99_bar_colour'],
			"borderColor"=>$data_array['HUTCH_99_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$mobitel_5_dataset=array(
			"label"=>"Mobitel Rs 5",
			"data"=> $data_array['MOBITEL_5'],
			'backgroundColor'=>$data_array['MOBITEL_5_bar_colour'],
			"borderColor"=>$data_array['MOBITEL_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$apple_6_dataset=array(
			"label"=>"APPLE $6",
			"data"=> $data_array['APPLE_6'],
			'backgroundColor'=>$data_array['APPLE_6_bar_colour'],
			"borderColor"=>$data_array['APPLE_6_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		$apple_10_dataset=array(
			"label"=>"APPLE $10",
			"data"=> $data_array['APPLE_10'],
			'backgroundColor'=>$data_array['APPLE_10_bar_colour'],
			"borderColor"=>$data_array['APPLE_10_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);
		
		$apple_15_dataset=array(
			"label"=>"APPLE $15",
			"data"=> $data_array['APPLE_15'],
			'backgroundColor'=>$data_array['APPLE_15_bar_colour'],
			"borderColor"=>$data_array['APPLE_15_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		$keels_5_dataset=array(
			"label"=>"KEELS Rs 5",
			"data"=> $data_array['KEELS_5'],
			'backgroundColor'=>$data_array['KEELS_5_bar_colour'],
			"borderColor"=>$data_array['KEELS_5_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);


		//Assign to Dataset Array
		array_push($datasets,$dialog_5_dataset);
		array_push($datasets,$dialog_25_dataset);
		array_push($datasets,$dialog_99_dataset);

		array_push($datasets,$hutch_5_dataset);
		array_push($datasets,$hutch_25_dataset);
		array_push($datasets,$hutch_99_dataset);

		array_push($datasets,$mobitel_5_dataset);
		array_push($datasets,$apple_6_dataset);
		array_push($datasets,$apple_10_dataset);
		array_push($datasets,$apple_15_dataset);
		array_push($datasets,$keels_5_dataset);

		array_push($datasets,$total_dataset);

		$chart_data=array(
			'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		$total_retention_user_counts= $data_array['TOTAL'][0];
		$previous_user_count=$privious_transaction_data_list[0]->previous_user_count;
		$retention=0;
		if($previous_user_count>0){
			$retention=number_format(($total_retention_user_counts/$previous_user_count)*100,2);
		}

		return $finel_data=array('chart_data'=>$chart_data,
		'total_retention_user_counts'=>$total_retention_user_counts,
		'retention'=>$retention,
		);
		

		
	}

	public function getActionCount(Type $var = null)
	{
		// select count(distinct (va.viewer_id) ) as user_count,cast(va.action_time as date)  as create_date from viewer_actions va where va.action_type =3 and va.action_time between cast('2017-03-05' as date) and 
		//  cast('2017-03-08' as date)  group by create_date

		// select va.action_type as type,count(distinct (va.viewer_id) ) as user_count,cast(va.action_time as date)  as create_date from viewer_actions va where (va.action_type =3 or va.action_type =8 ) and va.action_time between cast('2021-01-05' as date) and 
		//  cast('2021-01-21' as date)  group by create_date,type
	}
	public function dailyActivity()
	{
		return view('DashboardManage::chart.dailyActiveUser-chart');
	}

	public function dailyActivityData(Request $request)
	{
		$start_date=$request->get('start_date');
		$end_date_initial=$request->get('end_date');
		$end_date = date('Y-m-d', strtotime($end_date_initial . ' +1 day'));

		$data_array=array(
			'days'=>array(),

			'TOTAL'=>array(),
			'3'=>array(),
			'8'=>array(),
			

		);

		$label=[];
		$result = CarbonPeriod::create($start_date, '1 day', $end_date_initial);
		foreach ($result as $dt) {
			array_push($label,$dt->format("d-D-M-Y"));
			array_push($data_array['days'],$dt->format("Y-m-d"));

			array_push($data_array['TOTAL'],0);
			array_push($data_array['3'],0);
			array_push($data_array['8'],0);
			
		}
	
		$datasets=array();

		// $transaction_data_list = DB::select("select va.action_type as type,count(distinct (va.viewer_id) ) as user_count,cast(va.action_time as date)  as create_date from viewer_actions va where (va.action_type =3 or va.action_type =8 ) and va.action_time between cast("."'".$start_date."'"." as date) and 
		// cast("."'".$end_date."'"." as date)  group by create_date,type");
		
		$transaction_data_list = DB::select(" SELECT   va.action_type AS type,
			Count(DISTINCT ( va.viewer_id )) AS user_count,
			Cast(va.action_time AS DATE)     AS create_date
			FROM     viewer_actions va
			WHERE   ( va.rec_id IN (
					(
							SELECT     va2.rec_id
							FROM       viewer_actions va2
							INNER JOIN tbl_episode te
							ON         va2.content_id =te.episodeid
							WHERE      va2.action_type =3 and te.isTrailer =0)
			) or va.action_type =8)
			AND      va.action_time BETWEEN Cast("."'".$start_date."'"."  AS DATE) AND      Cast( "."'".$end_date."'"."  AS DATE)
			GROUP BY create_date,
					type ");

		foreach($transaction_data_list AS $data){
			$key = array_search ($data->create_date, $data_array['days']);
			$data_array[$data->type][$key]=$data_array[$data->type][$key]+($data->user_count);
			$data_array['TOTAL'][$key]=$data_array['TOTAL'][$key]+($data->user_count);
		}
		$total_dataset=array(
			"label"=>"TOTAL",
			"data"=> $data_array['TOTAL'],
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.overall.rgba'),
			// 'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.overall.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.overall.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);

		$video_dataset=array(
			"label"=>"VIDEO",
			"data"=> $data_array['3'],
			"borderWidth"=>1,
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.dialog.rgba'),
			'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.dialog.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.dialog.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 1,
			'pointStyle'=> 'rectRounded'
		);


		$audio_dataset=array(
			"label"=>"AUDIO",
			"data"=> $data_array['8'],
			"borderWidth"=>1,
			"hidden"=> false,
			'lineTension'=> 0,
			'fill'=> false,
			'borderColor'=> Config::get('chart.service_provider.hutch.rgba'),
			'backgroundColor'=> 'transparent',
			'pointBorderColor'=> Config::get('chart.service_provider.hutch.rgba'),
			'pointBackgroundColor'=> Config::get('chart.service_provider.hutch.rgba'),
			'pointRadius'=> 5,
			'pointHoverRadius'=> 10,
			'pointHitRadius'=> 30,
			'pointBorderWidth'=> 0.1,
			'pointStyle'=> 'rectRounded'
		);

		//Assign to Dataset Array
		array_push($datasets,$video_dataset);
		array_push($datasets,$audio_dataset);
		array_push($datasets,$total_dataset);

		$chart_data=array(
			// 'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;
	}

	public function cohort()
	{
		return view('DashboardManage::chart.cohort-chart');
	}
	public function cohortData(Request $request)
	{
		/*
		$from_date='2020-10-01';
		$end_date='2021-02-31';
		$main_array=array();
	
			$privious_transaction_data_list = DB::select("SELECT Year(si.createddate),
		Month(si.createddate),
		Count(DISTINCT ( si.viewer_id )) retention_users
		FROM   subscription_invoice si
		WHERE  si.createddate between "."'".$from_date."'"." and "."'".$end_date."'"." 
				AND si.success = 1
				AND si.amount > 0
				AND si.viewer_id IN (SELECT DISTINCT si2.viewer_id
									FROM   subscription_invoice si2
									WHERE  Month(si2.createddate) = 10
											AND Year(si2.createddate) = 2020
											AND si2.success = 1
											AND si2.amount > 0)
		GROUP  BY 1,
				2");
				array_push($main_array,$privious_transaction_data_list);
		
		return $main_array;
		*/

		// $start_month= implode('/', array_reverse(explode('/', $request->get('start_date'))));
		
		// $start_date=str_replace('/','-', $start_month.'-01');
		// $end_date=date("Y-m-t", strtotime($start_date));

		// $prev_month_ts = strtotime($start_date.' -1 month');
		// $prev_month_start_date = date('Y-m-d', $prev_month_ts);
		// $prev_month_end_date = date("Y-m-t", strtotime($prev_month_start_date));
		// $prev_month_end_date = date('Y-m-d', strtotime($prev_month_end_date . ' +1 day'));
		

		$start_date=$request->get('start_date');
		// $start_date='2021-01-01';
		$end_date_initial=$request->get('end_date');
		// $end_date_initial='2021-01-05';
		$end_date = date('Y-m-d', strtotime($end_date_initial . ' +1 day'));

		$data_array=array(
			'days'=>array(),

			'TOTAL'=>array(),
			'TOTAL_bar_colour'=>array(),
			'TOTAL_border_bar_colour'=>array(),			
		);

		$label=[];
		$result = CarbonPeriod::create($start_date, '1 day', $end_date_initial);
		foreach ($result as $dt) {
			array_push($label,$dt->format("d-D-M-Y"));
			array_push($data_array['days'],$dt->format("Y-m-d"));


			array_push($data_array['TOTAL'],0);
			array_push($data_array['TOTAL_bar_colour'],Config::get('chart.service_provider.overall.rgba'));
			array_push($data_array['TOTAL_border_bar_colour'],Config::get('chart.service_provider.overall.rgba'));

		}
	
		$datasets=array();
		//Transaction Data Retreview
		//  "select count(viewer_id)  as subscriber_count, amount as package,type,
		// cast(createDate as date)  as create_date from subscription_data 
		// where createDate between cast("."'".$start_date."'"." as date) and 
		// cast("."'".$end_date."'"." as date)  and subscribe = 1 and status = 1 
		// group by create_date, package,type";
		// echo "JJJ";
		// $transaction_data_list = DB::select("select count(viewer_id)  as subscriber_count, amount as package,type,
		//  cast(createDate as date)  as create_date from subscription_data 
		//  where createDate between cast("."'".$start_date."'"." as date) and 
		//  cast("."'".$end_date."'"." as date)  and subscribe = 1 and status = 1 and amount >0
		//  group by create_date, package,type");
		$transaction_data_list = DB::select("SELECT Cast(si.createddate as Date) Created_date,
		Count(DISTINCT ( si.viewer_id )) retention_users
		FROM   subscription_invoice si
		WHERE  si.createddate between "."'".$start_date."'"." and "."'".$end_date."'"."
				AND si.success = 1
				AND si.amount > 0
				AND si.viewer_id IN (SELECT DISTINCT si2.viewer_id FROM   subscription_invoice si2
									WHERE  cast(si2.createddate as DATE)  = "."'".$start_date."'"." AND si2.success = 1
											AND si2.amount > 0)
		GROUP  BY 1 ");

		foreach($transaction_data_list AS $data){
			$key = array_search ($data->Created_date, $data_array['days']);
			// $data_array[$data->type.'_'.$data->package][$key]=$data->subscriber_count;
			$data_array['TOTAL'][$key]=$data_array['TOTAL'][$key]+$data->retention_users;
			// $data_array['overall'][$key]=($data_array['overall'][$key]+$dSubscribe->subscriber_count);
		}
		 $total_dataset=array(
			"label"=>"TOTAL",
			"data"=> $data_array['TOTAL'],
			'backgroundColor'=>$data_array['TOTAL_bar_colour'],
			"borderColor"=>$data_array['TOTAL_border_bar_colour'],
			"borderWidth"=>1,
			"hidden"=> false,
		);

		

		//Assign to Dataset Array
		array_push($datasets,$total_dataset);

		$chart_data=array(
			'type'=>'bar',
			'labels'=>$label,
			'datasets'=>$datasets
		);

		return $chart_data;


		
	}


	
	

}
