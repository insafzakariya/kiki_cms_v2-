@extends('layouts.back.master') @section('current_title','Artist/EDIT')
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
        <h2>Artist Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/artists')}}">Artist</a>
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
                      action="{{route('admin.artists.update', $artist->artistId)}}" enctype="multipart/form-data"
                      data-parsley-validate>
                    {!!Form::token()!!}
                    {{method_field('PUT')}}
                    <input type="hidden" name="image_removed" id="image_removed" value="0">
                    <div class="form-group"><label class="col-sm-2 control-label">Artist/Act/Duo/Band<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name"
                                                      value="{{$artist->name}}"
                                                      data-parsley-required-message="Name field is required"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description">{!! $artist->description !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Artist Image<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            {{--<img style="max-width: 90%; margin-bottom: 15px;"
                                 src="{{Config('constants.bucket.url').Config('filePaths.front.artist').$artist->image}}"
                                 alt="">--}}
                                <input id="image" name="image" type="file" class="form-control after-error-placement" accept="image/*"
                                        data-parsley-required-message="The artist image is required"
                                       data-parsley-trigger="change" data-parsley-max-file-size="{{ env('Upload_Image_Size') }}"
                                       data-parsley-errors-container=".nameError">

                            <p class="nameError float-left"></p>
                            <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size
                                should be 175px *175px</p>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                                @if(isset($artist->search_tag) AND is_array($artist->search_tag))
                                    <?php foreach ($artist->search_tag as $key => $value):
                                        echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
                                    endforeach ?>
                                @endif
                            </select>
                            {{--<input type="text" class="form-control"  name="tags" value="{{$artist->search_tag}}">--}}
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Similar Artists</label>
                        <div class="col-sm-10">
                            <select name="similar_artists[]" class="form-control" id="similar_artists" multiple="multiple">
                                @foreach($similar as $similarArtist)
                                    <option value="{{$similarArtist->artistId}}" selected>{{$similarArtist->name}}</option>
                                @endforeach
                                {{--@foreach($artists as $similarArtist)
                                    <option value="{{$similarArtist->artistId}}">
                                        {{$similarArtist->name}}
                                    </option>
                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="location.href = '{{url('admin/artists')}}'">Cancel</button>
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
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/sortable.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/purify.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/theme.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select-simple-tag').select2({
                tags: true,
                multiple: true,
                tokenSeparators: [','],
                dropdownCssClass: 'select2-hidden'
            }).on('select2:open', function (e) {
                $('.select2-container--open .select2-dropdown--below').css('display', 'none');
            });

            let url = '{{url('admin/artists/search')}}';
            $("#similar_artists").select2({
                minimumInputLength: 3,
                multiple: true,
                ajax: {
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return  'term='+params.term; /*JSON.stringify({
                            term: params.term
                        });*/
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item, i) {
                                return {
                                    text: item.name,
                                    id: item.artistId
                                }
                            })
                        };
                    },
                    cache: true
                },
            });

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
                maxFileSize: {{ env('Upload_Image_Size') }},
                initialPreviewConfig: <?php echo json_encode($image_config) ?>,
                deleteExtraData: {
                    '_token': '{{csrf_token()}}',
                }
            }).on('filecleared', function() {
                $("#image_removed").val(1);
            });
            jQuery.validator.addMethod("filesize_max_kb", function (value, element, param) {
                    var isOptional = this.optional(element),
                        file;

                    if (isOptional) {
                        return isOptional;
                    }
                    if ($(element).attr("type") === "file") {
                        if (element.files && element.files.length) {

                            file = element.files[0];
                            return ( file.size && (file.size / 1000) <= param );
                        }
                    }
                    return false;
                },
                $.validator.format("File size is larger than  {0}kb")
            );
            $('#image').change(function () {
                $('#image').removeData('imageWidth');
                $('#image').removeData('imageHeight');
                var file = this.files[0];
                var tmpImg = new Image();
                tmpImg.src = window.URL.createObjectURL(file);
                tmpImg.onload = function () {
                    const width = tmpImg.naturalWidth,
                        height = tmpImg.naturalHeight;
                    $('#image').data('imageWidth', width);
                    $('#image').data('imageHeight', height);
                }
            });

            $.validator.addMethod('dimension', function (value, element, param) {
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
            /*$('select[name="similar_artists[]"]').select2({
                multiple: true,
            });*/
            {{--$('select[name="similar_artists[]"]').val({{$artist->similarArtists->pluck('similar_artist_id')->flatten()}});--}}
            {{--$('select[name="similar_artists[]"]').trigger('change');--}}


            $("#form").validate({
                rules: {
                    name: {
                        required: {
                            depends: function () {
                                $(this).val($.trim($(this).val()));
                                return true;
                            }
                        },
                    },
                    image:{
                        required: function (element) {
                            var image = <?php echo json_encode($image); ?>;
                            if (image) {
                                if ($('#image_removed').val() == 1) {
                                    return  true;
                                } else {
                                    return false;
                                }
                            } else {
                                return true;
                            }
                        },
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
                submitHandler: function (form) {

                    form.submit();
                }
            });

            $('#image').on('filecleared', function (event) {
                $('#image_delete').val(1);
            });
        });

        $("#image").change(function(){
            $('#image-error').hide();
        });


    </script>
@stop
