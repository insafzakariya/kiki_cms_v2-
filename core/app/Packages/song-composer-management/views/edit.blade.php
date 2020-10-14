@extends('layouts.back.master') @section('current_title','Song Composer/EDIT')
@section('css')
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}"/>
    <style>
        .fileinput-remove-button {
            margin-right: 5px;
        }
    </style>
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Song Composer Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/song-composers')}}">Song Composer</a>
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

                <form method="POST" class="form-horizontal" id="form"
                      action="{{route('admin.song-composers.update', $songComposer->id)}}" enctype="multipart/form-data"
                      data-parsley-validate>
                    {!!Form::token()!!}
                    {{method_field('PUT')}}
                    <input type="hidden" name="image_removed" id="image_removed" value="0">
                    <div class="form-group"><label class="col-sm-2 control-label">Name<span class="text-danger">*</span></label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="name"
                                                      value="{{$songComposer->name}}"
                                                      data-parsley-required-message="Name field is required"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control"
                                      name="description">{!! $songComposer->description !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Song Composer Image </label>
                        <div class="col-sm-6">
                            {{--<img style="max-width: 90%;" src="{{Config('constants.bucket.url').Config('filePaths.front.song-composer').$songComposer->image}}" alt="Composer Image">--}}
                            <input id="image" name="image" type="file" class="form-control after-error-placement"
                                   accept="image/*"
                                   >
                            <p class="nameError float-left"></p>
                            <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size
                                should be 175px *175px</p>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                                @if(isset($songComposer->tags) AND is_array($songComposer->tags))
                                    <?php foreach ($songComposer->tags as $key => $value):
                                        echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
                                    endforeach ?>
                                @endif
                            </select>
                            {{--<input type="text" class="form-control"  name="tags" >--}}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button"
                                    onclick="location.href = '{{url('admin/song-composers')}}'">Cancel
                            </button>
                            <button class="btn btn-primary" type="submit">Done</button>
                        </div>
                    </div>
                    <input type="text" id="image_delete" name="image_delete" value="0" hidden>

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
        $(document).ready(function () {
            $('.select-simple-tag').select2({
                tags: true,
                multiple: true,
                tokenSeparators: [','],
            }).on('select2:open', function (e) {
                $('.select2-container--open .select2-dropdown--below').css('display', 'none');
            });

            $("#image").fileinput({
                theme: "fa",
                maxFileCount: 1,
                showUpload: false,
                showRemove: true,
                //required: true,
                validateInitialCount: true,
                overwriteInitial: true,
                //initialPreviewAsData: true,
                //maxFileSize: 20,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                initialPreview: <?php echo json_encode($image); ?>,
                initialPreviewConfig: <?php echo json_encode($image_config) ?>,
                deleteExtraData: {
                    '_token': '{{csrf_token()}}',
                }
            }).on('filecleared', function() {
                $("#image_removed").val(1);
            });



           /* $.validator.addMethod("fileCount", function (val, ele, arg) {
                if($('#image').fileinput('getFilesCount') > 1){
                    return false;
                }else{
                    return true;
                }
            }, "Number of files selected for upload (2) exceeds maximum allowed limit of 1..");*/

            $("#form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    image: {
                        fileSize_max:{{ env('Upload_Image_Size') }}

                    },
                },
                messages: {
                    image: {
                        max: "Number of files selected for upload (2) exceeds maximum allowed limit of 1."
                    },
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    } else if (element.hasClass('next-error-placement')) {
                        element.parent().after(error);
                    } else if (element.hasClass('after-error-placement')) {
                        element.parent().parent().parent().after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form) {
                    //window.alert('submit');
                    form.submit();
                }
            });

            $('#image').on('filecleared', function (event) {
                $('#image_delete').val(1);
            });

        });
    </script>
@stop
