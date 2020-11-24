@extends('layouts.back.master') @section('current_title','Scratch Card / ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Scrath Card Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Scratch Card /Add</strong>
        </li>
    </ol>
</div>                  
@stop
@section('content')

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">
                      
                <form method="POST" class="form-horizontal" id="form"  enctype="multipart/form-data">
                	{!!Form::token()!!}
                    <div class="form-group"><label class="col-sm-2 control-label">Type </label>
                    	<div class="col-sm-5">
                            <select  name="type" id="type" required class="form-control select-simple" >
                                <option>Single</option>
                                <option>Bulk</option>
                                
                            </select>
                        </div>
                	</div>
                    <div class="form-group" id="card_count_div" ><label class="col-sm-2 control-label">No of cards </label>
                    	<div class="col-sm-5">
                            <select  name="card_count" id="card_count" required class="form-control select-simple" >
                                <option>5</option>
                                <option>20</option>
                                <option>40</option>
                                <option>70</option>
                                <option>100</option>
                                
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Start Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="start_date" name="start_date" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">End Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="end_date" name="end_date" vale="31-12-2099" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Package </label>
                    	<div class="col-sm-5">
                            <select  name="package" id="package" required class="form-control select-simple" >
                            @foreach ($packages as $package)
                                <option value="{{$package->PackageID}}">{{$package->Description}}</option>
                            @endforeach
                            </select>
                        </div>
                	</div>
                    
                	<div class="hr-line-dashed"></div>
	                <div class="form-group">
	                    <div class="col-sm-8 col-sm-offset-2">
	                        <button class="btn btn-default" type="button" onclick="location.reload();">Cancel</button>
	                        <button class="btn btn-primary" type="submit">Done</button>
	                    </div>
	                </div>
                    
                	
                </form>
        </div>
    </div>
</div>
@stop
@section('js')
<script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/back/flatpicker/flatpicker')}}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->


<script type="text/javascript">
	$(document).ready(function(){
        $("#start_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: ["today"]
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: [ "2030-12-31"]
            }
        );


		$("#form").validate({
            rules: {
                name: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                }
               
            },
            submitHandler: function(form) {
               
                form.submit();
            }
        });
	});



    //Programme Search
    $('#card_count_div').hide();
    $('#package').select2();
    $('#type').select2({minimumResultsForSearch: Infinity}).on('select2:select', function (e) {
        var data = e.params.data;
        if(data.id=='Single'){
            $('#card_count_div').hide();
        }else if(data.id=='Bulk'){
            $('#card_count_div').show();
        }
        console.log(data.id);
    });
    $('#card_count').select2({minimumResultsForSearch: Infinity});
	
</script>
@stop