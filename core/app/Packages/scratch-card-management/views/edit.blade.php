@extends('layouts.back.master') @section('current_title','Programme/Edit')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Scrath Card Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Scratch Card /Edit</strong>
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
                            <select  name="type" id="type" required class="form-control select-simple"  disabled>
                                @if($exsist_scratch_card->CardType==1)
                                <option value="1">Single</option>
                                @elseif($exsist_scratch_card->CardType==2)
                                <option value="2">Bulk</option>
                                @endif
                                
                            </select>
                        </div>
                	</div>
                    @if($exsist_scratch_card->CardType==2)
                    <div class="form-group" id="card_count_div" ><label class="col-sm-2 control-label">No of cards </label>
                    	<div class="col-sm-5">
                        <input type="text" id="code_count" name="code_count" value="{{$scratch_codes_count}}" class="" disabled>
                        </div>
                	</div>
                    @endif
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
                                @if($package->PackageID == $exsist_scratch_card->PackageID)
                                <option selected value="{{$package->PackageID}}">{{$package->Description}}</option>
                                @else
                                <option  value="{{$package->PackageID}}">{{$package->Description}}</option>
                                @endif
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
                defaultDate: ["<?php echo $exsist_scratch_card->ActivityStartDate; ?> "]
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: ["<?php echo $exsist_scratch_card->ActivityEndDate; ?> "]
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

    $('#package').select2();
    $('#type').select2();
   

	
</script>
@stop