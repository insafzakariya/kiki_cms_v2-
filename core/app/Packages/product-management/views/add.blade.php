@extends('layouts.back.master') @section('current_title','Product / ADD')
@section('css')
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
{{--    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />--}}
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}" />

    <style>
        .margin-div {
            margin-top: 30px;
        }
    </style>

@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Product Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/products')}}">PRODUCT</a>
            </li>
            <li class="active">
                <strong>ADD</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <div class="steps-form">
                    <div class="steps-row setup-panel">
                        <div class="steps-step steps-success">
                            <span class="btn btn-success btn-circle ">1</span>
                            <p>Step 1</p>
                        </div>
                        <div class="steps-step">
                            <span class="btn btn-default btn-circle">2</span>
                            <p>Step 2</p>
                        </div>
                        <div class="steps-step">
                            <span class="btn btn-default btn-circle">3</span>
                            <p>Step 3</p>
                        </div>
                    </div>
                </div>

                <form method="POST" class="form-horizontal" style="margin-top: 50px" id="form" action="{{route('admin.products.store')}}" enctype="multipart/form-data" data-parsley-validate>
                    {!!Form::token()!!}

                    <div class="form-group"><label class="col-sm-2 control-label">Product Type <span class="text-danger">*</span> </label>
                        <div class="col-sm-10">
                            <select name="product_type" id="product_type" class="form-control">
                                <option value="">--Please select project type--</option>
                                <option value="Ep">Ep</option>
                                <option value="Singles">Singles</option>
                                <option value="Album">Album</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group margin-div"><label class="col-sm-2 control-label">Product Name <span class="text-danger">*</span> </label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="name"></div>
                    </div>
                    <div class="form-group margin-div"><label class="col-sm-2 control-label">UPC Code <span class="text-danger">*</span> </label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="upc_code"></div>
                    </div>
                    <div class="form-group margin-div"><label class="col-sm-2 control-label">Primary Artist <span class="text-danger">*</span> </label>
                        <div class="col-sm-10">
                            <select name="primary_artist[]" id="primary_artist" class="form-control" multiple>
{{--                                @foreach($artists as $artist)--}}
{{--                                    <option value="{{$artist->artistId}}">{{$artist->name}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group margin-div"><label class="col-sm-2 control-label">Product Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="form-group margin-div"><label class="col-sm-2 control-label">Product Category <span class="text-danger">*</span> </label>
                        <div class="col-sm-10">
                            <select name="product_category" id="product_category" class="form-control">
                                <option value="">Please select a category</option>
                                @foreach($songCats as $songCat)
                                        <option value="{{$songCat->categoryId}}">{{$songCat->name}}</option>
                                    @foreach($songCat->childs as $child)
                                        <option value="{{$child->categoryId}}"> &nbsp; &nbsp; {{$child->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <p class="nameError float-left"></p>
                        </div>
                    </div>
                    <div class="form-group margin-div">
                        <label class="col-sm-2 control-label required">Product Image <span class="text-danger">*</span>  </label>
                        <div class="col-sm-5">
                            <input id="image" name="image" type="file" class="form-control after-error-placement" accept="image/*"
                                   data-parsley-required-message="The product image is required"
                                   data-parsley-trigger="change"
                                   data-parsley-errors-container=".nameError">
                            <p class="nameError float-left"></p>
                            <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size
                                should be 175px *175px</p>

                        </div>
                    </div>
                    <div class="form-group margin-div"><label class="col-sm-2 control-label">Project <span class="text-danger">*</span> </label>
                        <div class="col-sm-10">
                            <select name="project" id="project" class="form-control">
                                <option value="">Please select a project</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="location.href = '{{url('admin/products')}}';">Cancel</button>
                            <button class="btn btn-primary" type="submit">Done</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
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


            $("#product_type").select2();
            $("#project").select2();
            $("#product_category").select2();
            $("#advertisement_policy").select2();
            $("#content_policy").select2();

            let url = '{{url('admin/artists/search')}}';

            $("#primary_artist").select2({
                placeholder: "Please select a primary artist",
                tokenSeparators: [','],
                tags: true,
                minimumInputLength: 2,
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
                showUpload:false,
                //showRemove: false,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
                // maxFileSize: 20,
            });

            // $('#image').change(function() {
            //     $('#image').removeData('imageWidth');
            //     $('#image').removeData('imageHeight');
            //     var file = this.files[0];
            //     var tmpImg = new Image();
            //     tmpImg.src=window.URL.createObjectURL( file );
            //     tmpImg.onload = function() {
            //         const width = tmpImg.naturalWidth,
            //             height = tmpImg.naturalHeight;
            //         $('#image').data('imageWidth', width);
            //         $('#image').data('imageHeight', height);
            //     }
            // });
            //
            // $.validator.addMethod('dimension', function(value, element, param) {
            //         if (element.files.length === 0) {
            //             return true;
            //         }
            //         var width = $(element).data('imageWidth');
            //         var height = $(element).data('imageHeight');
            //         if (width == param[0] && height == param[1]) {
            //             return true;
            //         } else {
            //             return false;
            //         }
            //     },
            //     $.validator.format("Upload image size should be {0}px X {1}px")
            // );

            $("#form").validate({
                rules: {
                    product_type: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    product_category: {
                        required: true
                    },
                    "primary_artist[]": {
                        required: true
                    },
                    upc_code: {
                        required: true
                    },
                    image: {
                        required: true,
                        fileSize_max:{{ env('Upload_Image_Size') }}
                    },
                    project: {
                        required: true
                    }

                },
                errorPlacement: function (error, element) {
                    // console.log("ERROR");
                    // console.log(error);
                    // console.log("ELEMENT");
                    // console.log(element);

                    var name = element.attr('name');

                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    }else if(element.hasClass('next-error-placement')){
                        element.parent().after(error);
                    }else if(element.hasClass('after-error-placement')){
                        element.parent().parent().parent().after(error);
                    } else if(name == "product_category" || name == "project" || name == "product_type" || name == "primary_artist[]") {
                        error.insertAfter(element.next());
                    }  else {
                        element.after(error);
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });


        $('#product_type').on('change', function (e) {
            var val = $(this).val();
            if (val !== null && val !== "") {
                if($(this).hasClass("error")) {
                    $(this).removeClass("error");
                    $("#product_type-error").hide();
                }
            }
        });

        $('#product_category').on('change', function (e) {
            var val = $(this).val();
            if (val !== null && val !== "") {
                if($(this).hasClass("error")) {
                    $(this).removeClass("error");
                    $("#product_category-error").hide();
                }
            }
        });

        $('#project').on('change', function (e) {
            var val = $(this).val();
            if (val !== null && val !== "") {
                if($(this).hasClass("error")) {
                    $(this).removeClass("error");
                    $("#project-error").hide();
                }
            }
        });

        $('#primary_artist').on('change', function (e) {
            var val = $(this).val();
            if (val !== null && val !== "") {
                if($(this).hasClass("error")) {
                    $(this).removeClass("error");
                    $("#primary_artist-error").hide();
                }
            }
        });

        $("#image").change(function(){
            $('#image-error').hide();
        });

        // function readURL(input) {
        //     if (input.files && input.files[0]) {
        //         var reader = new FileReader();
        //
        //         reader.onload = function (e) {
        //             $('#imagePreview')
        //                 .attr('src', e.target.result)
        //                 .width(150)
        //                 .height(150);
        //         };
        //
        //         reader.readAsDataURL(input.files[0]);
        //
        //         $('#imagePreview').show();
        //     }
        // }


    </script>
@stop
