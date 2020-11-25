@extends('layouts.back.master') @section('current_title','Programme/Edit')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Programme  SliderManagement</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Programme Slider/Edit</strong>
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
                    <input type="hidden" name="thumb_image_removed" id="thumb_image_removed" value="0">
                    <div class="form-group"><label class="col-sm-2 control-label">Name</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="name" value="{{$exsist_programme_slider->name}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Programme </label>
                    	<div class="col-sm-5">
                        <select  name="programme" id="programme" class="form-control select-simple" >
                        @if(isset($exsist_programme_slider->getProgramme))
                        <option value="{{$exsist_programme_slider->getProgramme->programId}}" selected="selected">{{$exsist_programme_slider->getProgramme->programName}}</option>
                        @endif
                        
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Start Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="start_date" name="start_date" value="{{$exsist_programme_slider->start_date_time}}" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">End Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="end_date" name="end_date" value="{{$exsist_programme_slider->end_date_time}}" class="">
                        </div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Thumb Image</label>
                        <div class="col-sm-4">
                            <input id="thumb_image" name="thumb_image[]" type="file" multiple class="form-control" accept="image/*">
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
    var thumb_image_preview_deleted_list=[];
    var cover_image_preview_deleted_list=[];
	$(document).ready(function(){
        
        $("#start_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                
               
            }
        );

        $('#programme').select2({
            ajax               : 
            {
                url            : '{{url('episode/search/programme')}}',
                type           : 'GET',
                dataType       : 'json',
                delay          : 250,
                processResults : function (data) {
                    return {
                        results: $.map(data, function (item, i) {
                            return {
                                text: item.programName,
                                id: item.programId
                            }
                        })
                    };
                }
            }
        });



            //Custom Validation


            jQuery.validator.addMethod("thumb_image_va", function(value, element){
            
            const exsist_file_count = $('#thumb_image').data("fileinput").filestack.length;
            const initialPreview_file_count = $('#thumb_image').data("fileinput").initialPreview.length;
            console.log($('#thumb_image').data("fileinput"));
            // console.log(thumb_image_preview_deleted_list.length);
            if((exsist_file_count+(initialPreview_file_count-thumb_image_preview_deleted_list.length)) >=1){
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
                  
                },
                'thumb_image[]': {
                    // required: true,
                    thumb_image_va:true
                }
            
               
            },
            submitHandler: function(form) {
                $('#thumb_image_preview_deleted').val(JSON.stringify(thumb_image_preview_deleted_list));
               
                form.submit();
            }
        });
	});
    
   

    
    $("#thumb_image").fileinput({
        uploadUrl: "", // server upload action
        dropZoneEnabled: true,
        uploadAsync: false,
        maxFileCount: 1,
        showRemove: true,
        showUpload:false,
        // initialPreviewCount :1,
        validateInitialCount : true,
        overwriteInitial: true,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
        initialPreview: <?php echo json_encode($thumb_image); ?>,
        initialPreviewConfig: <?php echo json_encode($thumb_image_config) ?>
        
    }).on('filecleared', function() {
        $("#thumb_image_removed").val(1);
    }).on('filedeleted', function(event, key, jqXHR, data) {
        thumb_image_preview_deleted_list.push(key);
        console.log('Key = ' + key);
    });

    
	
</script>
@stop