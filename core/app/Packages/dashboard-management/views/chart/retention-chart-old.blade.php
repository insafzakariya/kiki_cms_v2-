@extends('layouts.back.master') @section('current_title','WELLCOME tO SAMBOLE ADMIN WEB PORTAL')
@section('css')
<link href="{{asset('assets/back/monthpicker/monthpicker.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
<link href="{{asset('assets/back/retention-graph/css/retention-graph.css')}}" rel="stylesheet">
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


@stop
@section('content')
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div id="demo">
        </div>
    </div>
    <div class="col-md-1"></div>
</div>

@stop
@section('js')
<script src="{{asset('assets/back/chartjs/Chart.min.js')}}"></script>
<script src="{{asset('assets/back/chartjs/utils.js')}}"></script>
<script src="{{asset('assets/back/monthpicker/monthpicker.min.js')}}"></script>
<script src="{{asset('assets/back/flatpicker/flatpicker')}}"></script>
<script src="{{asset('assets/back/retention-graph/moment.js')}}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="{{asset('assets/back/retention-graph/js/options.js')}}"></script>
<script src="{{asset('assets/back/retention-graph/js/retention-graph.js')}}"></script>
<script>
    $("#demo").retention(options);
</script>


<script type="text/javascript">

    
    
</script>

@stop