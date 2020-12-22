@extends('layouts.back.master') @section('current_title','WELLCOME tO SAMBOLE ADMIN WEB PORTAL')
@section('css')
<link href="{{asset('assets/back/monthpicker/monthpicker.css')}}" rel="stylesheet" type="text/css">
<style type="text/css">
.clr-yellow div {
    background-color: #f8ac59;
    color: #ffffff;
}
.clr-blue div {
    background-color: #1c84c6;
    color: #FFFFFF;
}
.clr-red div {
    background-color: #ed5565;
    color: #ffffff;
}
.clr-green div {
    background-color: #1ab394;
    color: #ffffff;
}
.clr-dark-green div{
    background-color: #0b705b;
    color: #ffffff;
}
.clr-dark-blue div{
    background-color: #0f4d75;
    color: #ffffff;
}
.clr-ibox
{
    background-color: #595959;
}
#alertmod_table_list_2 {
    top: 900px!important;
}
.wrapper-content {
    padding-top: 0;
}
.no-margins {
    /*text-align: center;*/
    font-weight: bold;
}
.margins {
    margin-top: 20px;
}
.label{
    background-color: transparent;
    margin-top: -8px!important;
}





</style>
@stop
@section('current_path')
@stop
@section('page_header')
<!-- <div class="col-lg-5">
    <h2>Dashboard-Admin</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Dashboard</strong>
        </li>
    </ol>
</div> -->

@stop
@section('content')
<div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                <div class="modal"><!-- Place at bottom of page --></div>

                    <div class="col-sm-10">
                    <input id="startDate" type="text" />
                    <input id="endDate" type="text" />
                    <button onclick="findData();">Search</button>
                    <canvas id="subscribe_chart" width="200" height="100"></canvas>
                    </div>
                
                </div>
            </div>
        </div>
</div>

@stop
@section('js')
<script src="{{asset('assets/back/chartjs/Chart.min.js')}}"></script>
<script src="{{asset('assets/back/chartjs/utils.js')}}"></script>
<script src="{{asset('assets/back/monthpicker/monthpicker.min.js')}}"></script>


<script type="text/javascript">
    var d = new Date();
    var current_month = d.getMonth()+1;
    var current_year = d.getFullYear();
    $('#startDate').val((current_month-2)+'/'+current_year);
    $('#endDate').val(current_month+'/'+current_year);
    $('#startDate').Monthpicker({
        // format: 'yyyy-mm',
        monthLabels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "July", "Aug", "Sep", "Oct", "Nov", "Dec"],
        onSelect: function () {
            $('#endDate').Monthpicker('option', { minValue: $('#startDate').val() });
        }
    });
    $('#endDate').Monthpicker({
        // format: 'yyyy-mm',
        monthLabels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "July", "Aug", "Sep", "Oct", "Nov", "Dec"],
        onSelect: function () {
            $('#startDate').Monthpicker('option', { maxValue: $('#endDate').val() });
        }
    });
    var ctx = document.getElementById('subscribe_chart');
    var subscribe_chart = new Chart(ctx, {
        type: 'bar',
        data:[],
        options: {

                scales: {
                    xAxes: [{
            gridLines: {
                drawOnChartArea: false
            }
        }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    gridLines: {
                        drawOnChartArea: false
                    }
                }]
            },
            title: {
                display: true,
                text: 'Subscribe Count'
            }
            
        }
    });
    

    load_subscribe_chart(subscribe_chart);
    //Find Data
    function findData(){
        load_subscribe_chart(subscribe_chart);
    }
    //Load Data to Subscribe Chart
    function load_subscribe_chart(subscribe_chart){
        var start_date=$('#startDate').val();
        var end_date=$('#endDate').val();
        $.ajax({
            method: "GET",
            url: '{{url('dashboard/data/subscribe')}}',
            data: {start_date,end_date},
        }).done(function( chart_data ) {
            subscribe_chart.data= chart_data;
            subscribe_chart.update(); 
            console.log(chart_data);
            
        });
       

    }
    
    
</script>

@stop