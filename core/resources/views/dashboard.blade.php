@extends('layouts.back.master') @section('current_title','WELLCOME tO SAMBOLE ADMIN WEB PORTAL')
@section('css')
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
<!-- <div class="row">
    <div class="col-lg-3 margins">
        <div class="ibox float-e-margins clr-green">
            <div class="ibox-title ">
                <span class="label label-danger pull-right clr-ibox">total</span>
                <h5>Number of Ads</h5>
            </div>
            <div class="ibox-content">
            
            </div>
        </div>
    </div>
    <div class="col-lg-3 margins">
        <div class="ibox float-e-margins clr-blue" >
            <div class="ibox-title ">
                <span class="label label-info pull-right clr-ibox">total</span> 
                <h5>Number of users</h5>
            </div>
            <div class="ibox-content">
              
            </div>
        </div>
    </div>
    <div class="col-lg-3 margins">
        <div class="ibox float-e-margins clr-yellow" >
            <div class="ibox-title">
                <span class="label label-success pull-right clr-ibox">total</span> 
                <h5>Revenue</h5>
            </div>
            <div class="ibox-content">
               
                <small>Total revenue</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 margins">
        <div class="ibox float-e-margins clr-red">
            <div class="ibox-title ">
                <span class="label label-primary pull-right clr-ibox">In <?php echo date("M")?></span> 
                <h5>Visitors</h5>
            </div>
            <div class="ibox-content">               
                <small>Total visitors</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 margins">
        <div class="ibox float-e-margins clr-dark-green">
            <div class="ibox-title ">
                <span class="label label-danger pull-right clr-ibox">active</span>
                <h5>Number of Ads</h5>
            </div>
            <div class="ibox-content">
                
                <small>Active ads</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 margins">
        <div class="ibox float-e-margins clr-dark-blue" >
            <div class="ibox-title ">
                <span class="label label-info pull-right clr-ibox">active</span> 
                <h5>Number of users</h5>
            </div>
            <div class="ibox-content">
                
                <small>Active users</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox float-e-margins clr-green">
            <div class="ibox-title ">
                <h5>Total Number of Posted Ads </h5>
                <span class="label label-primary pull-right clr-ibox">In <?php echo date("M")?></span> 
            </div>
            <div class="ibox-content">
                <div id="columnchart_material" style="width: 100%; min-height: 450px;"></div>
            </div>
    </div>
</div> -->

@stop
@section('js')

<script type="text/javascript">
    $(document).ready(function(){

    });
    
    
</script>
@stop