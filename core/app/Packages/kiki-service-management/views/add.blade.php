@extends('layouts.back.master') @section('current_title','Programme/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Kiki Service Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Kiki Service /Add</strong>
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

                	<div class="form-group"><label class="col-sm-2 control-label">Name *</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="name"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Parent *</label>
                    	<div class="col-sm-5">
                        <select  name="parent_service" id="parent_service"  class="form-control select-simple" >
                        <option value="">Root</option>
                            @foreach ($kiki_services As $kiki_service)
                                <option value="{{$kiki_service->id}}">{{$kiki_service->name}}</option>
                            @endforeach
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Thumb Image *</label>
                        <div class="col-sm-4">
                            <input id="thumb_image" name="thumb_image[]" type="file" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Url</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="url"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Landing Url</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="landing_url"></div>
                	</div>

                    <div class="form-group"><label class="col-sm-2 control-label">Referance</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="reference"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Bridge Id</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="bridge_id"></div>
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
       

        //Image Validation
        jQuery.validator.addMethod("thumb_image_va", function(value, element){
            const exsist_file_count = $('#thumb_image').data("fileinput").filestack.length;
            if(exsist_file_count >=1){
                return true;
            }else{
                return false;
            }
         
        }, "You must select at least 1 files to upload. Please retry your upload!"); 


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
                ,
                'thumb_image[]': {
                    // required: true,
                    thumb_image_va:true
                }
               
            },
            submitHandler: function(form) {
               
                form.submit();
            }
        });
	});

    $("#thumb_image").fileinput({
        uploadUrl: "", // server upload action
        dropZoneEnabled: true,
        uploadAsync: false,
        showRemove: true,
        showUpload:false,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
        
    });

    //Parent Search
    $('#parent_service').select2(
        // {
        //     ajax               : 
        //     {
        //         url            : '{{url('service/search/parent')}}',
        //         type           : 'GET',
        //         dataType       : 'json',
        //         delay          : 250,
        //         processResults : function (data) {
        //             return {
        //                 results: $.map(data, function (item, i) {
        //                     return {
        //                         text: item.programName,
        //                         id: item.programId
        //                     }
        //                 })
        //             };
        //         }
        //     }
        // }
        );
	
</script>
@stop