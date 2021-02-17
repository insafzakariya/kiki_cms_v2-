@extends('layouts.back.master') @section('current_title','WELLCOME tO SAMBOLE ADMIN WEB PORTAL')
@section('css')
<link href="{{asset('assets/back/monthpicker/monthpicker.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/back/cohort/css/retention-graph.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
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
                    <div id="demo">
                     </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.js" type="text/javascript"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

<script src="{{asset('assets/back/cohort/js/retention-graph.js')}}"></script>


<script>
    var options = {
        data : {
            days : {
                "22-05-2016": [200, 10, 20, 30, 40, 10, 20, 20],
                "23-05-2016": [300, 200, 150, 50, 20, 20, 90],
                "24-05-2016": [200, 110, 150, 50, 10, 20,100,100],
                "25-05-2016": [100, 10, 10, 50, 20],
                "26-05-2016": [300, 200, 150, 50],
                "27-05-2016": [200, 110, 40],
                "28-05-2016": [100, 50],
                "29-05-2016": [200]
            },
            weeks : {
                "week1": [200, 100, 60, 20, 5],
                "week2": [300, 200, 100, 50],
                "week3": [200, 100, 40],
                "week4": [200, 100]
            },
            months : {
                "month1": [200, 10, 20, 30],
                "month2": [300, 200, 150],
                "month3": [200, 110]
            }
        },
        //startDate : "22-05-2016",
        //endDate : "25-05-2016",
        inputDateFormat : "DD-MM-YYYY", //if not iso date given
        dateDisplayFormat : "MMM DD YYYY",
        title : "Retention Analysis",
        cellClickEvent : function(date, day){
            alert("date=" + date + "&day="+ day);
        },
        enableInactive: true,
        dayClickEvent : function(day, startDate, endDate){
            alert(day + "start" + startDate + "end" + endDate);
        },
        enableDateRange:true,
        showAbsolute : true,
        toggleValues : true
    };
    $("#demo").retention(options);
</script>

@stop