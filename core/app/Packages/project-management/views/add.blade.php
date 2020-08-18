@extends('layouts.back.master') @section('current_title','Project/ADD')
@section('css')
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}"/>
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Project Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li>
            <a href="{{url('/admin/projects')}}">Project</a>
        </li>
        <li class="active">
            <strong>New</strong>
        </li>
    </ol>
</div>
@stop
@section('content')

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">

                <form method="POST" class="form-horizontal" id="form" action="{{route('admin.projects.store')}}" enctype="multipart/form-data" data-parsley-validate>
                	{!!Form::token()!!}

                	<div class="form-group"><label class="col-sm-2 control-label">Project Name<label class="text-danger">*</label></label>
                    	<div class="col-sm-10"><input type="text" class="form-control" name="name"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Project Image </label>
                        <div class="col-sm-5">
                            <input id="image" name="image" type="file"  class="form-control after-error-placement" accept="image/*">
                            <p class="nameError float-left"></p>
                            <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size should be 175px *175px</p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
	                <div class="form-group">
	                    <div class="col-sm-8 col-sm-offset-2">
	                        <button class="btn btn-default" type="button" onclick="location.href = '{{url('admin/projects')}}';">Cancel</button>
	                        <button class="btn btn-primary" type="submit">Done</button>
	                    </div>
	                </div>

                </form>
        </div>
    </div>
</div>
@stop
@section('js')
    <!-- file input -->
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/sortable.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/purify.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/theme.min.js')}}"></script>
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/back/js/jquery-validation-extension.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
        $('#advertisement_policies').select2();

        $("#image").fileinput({
            theme: "fa",
            maxFileCount: 1,
            multiple: false,
            showUpload: false,
            allowedFileTypes: ['image'],
            allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
            overwriteInitial: true,
            // maxFileSize: 20,
        });

        jQuery.validator.addMethod("filesize_max_kb", function(value, element, param) {

            console.log("PAR = " + param);
            var isOptional = this.optional(element),
                file;

            if(isOptional) {
                return isOptional;
            }
            if ($(element).attr("type") === "file") {
                if (element.files && element.files.length) {
                    file = element.files[0];
                    return ( file.size && file.size <= (param * 1024));
                }
            }
            return false;
        },
            $.validator.format("File size is larger than  {0}kb")
        );
        $('#image').change(function() {
            $('#image').removeData('imageWidth');
            $('#image').removeData('imageHeight');
            var file = this.files[0];
            var tmpImg = new Image();
            tmpImg.src=window.URL.createObjectURL( file );
            tmpImg.onload = function() {
                const width = tmpImg.naturalWidth,
                    height = tmpImg.naturalHeight;
                $('#image').data('imageWidth', width);
                $('#image').data('imageHeight', height);
            }
        });

        $.validator.addMethod('dimension', function(value, element, param) {
            if (element.files.length === 0) {
                return true;
            }
            var width = $(element).data('imageWidth');
            var height = $(element).data('imageHeight');
            if (width == param[0] && height == param[1]) {
                return true;
            } else {
                return false;
            }
        },
            $.validator.format("Upload image size should be {0}px X {1}px")
        );
		$("#form").validate({
            rules: {
                name: {
                    required: true
                },
                image:{
                    fileSize_max:{{ env('Upload_Image_Size') }}
                }
            },
            errorPlacement: function (error, element) {
                if (element.hasClass('select-simple')) {
                    element.next().after(error);
                }else if(element.hasClass('next-error-placement')){
                    element.parent().after(error);
                }else if(element.hasClass('after-error-placement')){
                    element.parent().parent().parent().after(error);
                }  else {
                    element.after(error);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
	});

	function allProjects() {
        window.location = "projects";
    }


</script>
@stop
