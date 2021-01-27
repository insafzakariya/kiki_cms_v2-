@extends('layouts.back.master') @section('current_title','Artist/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Twillo Channel Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Twillo Channel / Add</strong>
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

                	
                    <div class="form-group"><label class="col-sm-2 control-label">Friendly Name *</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="friendly_name"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Unique Name *</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="unique_name"></div>
                	</div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Channel Image</label>
                        <div class="col-sm-4">
                            <input id="channel_image" name="channel_image" type="file"  class="form-control" accept="image/*">
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
<script type="text/javascript">
	$(document).ready(function(){
       

        $("#channel_image").fileinput({
            uploadUrl: "", // server upload action
            dropZoneEnabled: true,
            uploadAsync: false,
            // minFileCount: 3,
            showRemove: true,
            showUpload:false,
            allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
            
        });
        
		$("#form").validate({
            rules: {
                friendly_name: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                unique_name: {
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
    
   
	
	
</script>
@stop