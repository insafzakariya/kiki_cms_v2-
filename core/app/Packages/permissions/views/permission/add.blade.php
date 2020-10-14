@extends('layouts.back.master') @section('current_title','New Permission')
@section('css')
<link rel="stylesheet" href="{{asset('assets/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>User Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>New Permission</strong>
        </li>
    </ol>
</div>                  
@stop
@section('content')

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">
                          
                <form method="POST" class="form-horizontal" id="form">
                	{!!Form::token()!!}

                	<div class="form-group"><label class="col-sm-2 control-label ">NAME</label>
                    	<div class="col-sm-10"><input type="text" class="form-control required" name="label"></div>
                	</div>
                	<div class="form-group"><label class="col-sm-2 control-label">DESCRIPTION</label>
                    	<div class="col-sm-10"><textarea class="form-control" name="description"></textarea> </div>
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
<script src="{{asset('assets/vendor/select2-3.5.2/select2.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".js-source-states").select2();

		$("#form").validate({
            rules: {
                label: {
                    required: true
                  
                },
                menu_url:{
                	required: true
                },
                parent_menu:{
                	required: true
                },               
                tel:{
                	digits:true
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
	});
	
	
</script>
@stop