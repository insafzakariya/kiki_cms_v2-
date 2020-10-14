@extends('layouts.back.master') @section('current_title','Radio Channel/EDIT')
@section('css')
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Radio Channel Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/radio-channels')}}">Radio Channel</a>
            </li>
            <li class="active">
                <strong>Edit</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <form method="POST" class="form-horizontal" id="form" action="{{route('admin.radio-channels.update', $radioChannel->id)}}" enctype="multipart/form-data" data-parsley-validate>
                    {!!Form::token()!!}
                    {{method_field('PUT')}}
                    <input type="hidden" name="image_removed" id="image_removed" value="0">
                    <div class="form-group"><label class="col-sm-2 control-label">Channel Name<span class="text-danger">*</span> </label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="name" value="{{$radioChannel->name}}" required data-parsley-required-message="The channel name field is required"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description">{{$radioChannel->description}}</textarea>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Stream URL<span class="text-danger">*</span> </label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="url" value="{{$radioChannel->url}}"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Channel Logo
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input id="image" name="image" type="file"  class="form-control after-error-placement" accept="image/*"  data-parsley-trigger="change"  data-parsley-max-file-size="{{ env('Upload_Image_Size') }}"  data-parsley-errors-container=".nameError">
                            <p class="nameError float-left"></p>
                            <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size should be 175px *175px</p>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="location.href = '{{url('admin/radio-channels')}}'">Cancel</button>
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
            let initialFileCount = {{count($image_config)}}

            $("#image").fileinput({
                theme: "fa",
                maxFileCount: 1,
                showUpload: false,
                showRemove: true,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
                initialPreview: <?php echo json_encode($image); ?>,
                multiple: false,
                // maxFileSize: 20,
                initialPreviewConfig: <?php echo json_encode($image_config) ?>,
                deleteExtraData: {
                    '_token': '{{csrf_token()}}',
                }
            }).on('filecleared', function() {
                $("#image_removed").val(1);
            });

            jQuery.validator.addMethod("filesize_max_kb", function(value, element, param) {
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

             $.validator.addMethod("fileCount", function (val, ele, arg) {
                if($('#image').fileinput('getFilesCount') > 1){
                    return false;
                }else{
                    return true;
                }
            });


            $("#form").validate({
                rules: {
                    url: {
                        required: true,
                        url: true
                    },
                    image: {
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
                    // window.alert('submit');
                    //jQuery("#file_upload").fileinput("upload");
                }
            });
        });

        function radioChannelsPage() {
            window.location = "radio-channels";
        }


    </script>
@stop
