@extends('layouts.back.master') @section('current_title','Lyricist/EDIT')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Lyricist Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{route('admin.lyricists.index')}}">Lyricist</a>
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
                <form method="POST" class="form-horizontal" id="form" action="{{route('admin.lyricists.update', $lyricist->writerId)}}" enctype="multipart/form-data" data-parsley-validate>
                    {!!Form::token()!!}
                    {{method_field('PUT')}}
                    <input type="hidden" name="image_removed" id="image_removed" value="0">
                    <div class="form-group"><label class="col-sm-2 control-label"><label class="text-danger">*</label>Name</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="name" value="{{$lyricist->name}}" required data-parsley-required-message="Name field is required"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description">{!! $lyricist->description !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Lyricist Image </label>
                        <div class="col-sm-6">
                            <input id="image" name="image" type="file"  class="form-control after-error-placement" accept="image/*" >
                            <p class="nameError float-left"></p>
                            <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size should be 175px *175px</p>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10"><select class="form-control tags" multiple name="tags[]">
                                @if(isset($lyricist->search_tag))
                                    @foreach($lyricist->search_tag as $key => $tag)
                                        <option value="{{$tag}}" selected="selected">{{$tag}}</option>
                                    @endforeach
                                @endif
                            </select></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <a class="btn btn-default" href="{{route('admin.lyricists.index')}}">Cancel</a>
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
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/sortable.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/purify.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/theme.min.js')}}"></script>
    <script src="{{asset('assets/back/js/jquery-validation-extension.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".tags").select2({
                tags: true,
                multiple: true,
                tokenSeparators: [','],
                dropdownCssClass: 'select2-hidden'
            });

            $("#image").fileinput({
                theme: "fa",
                showUpload: false,
                showRemove: true,
                multiple: false,
                initialPreviewShowDelete: false,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
                initialPreview: <?php echo json_encode($image); ?>,
                initialPreviewConfig: <?php echo json_encode($image_config) ?>,
                maxFileSize: {{ env('Upload_Image_Size') }},
            }).on('filecleared', function() {
                $("#image_removed").val(1);
            });

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
                    image: {
                        fileSize_max:{{ env('Upload_Image_Size') }}

                    },
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
    </script>
@stop
