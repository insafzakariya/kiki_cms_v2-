@extends('layouts.back.master') @section('current_title','Product / ADD')
@section('css')
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
    {{--    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />--}}
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}" />

    <style>
        .margin-div {
            margin-left: 0px !important;
            margin-right: 0px !important;
        }

        .big-padding-bottom{
            padding-bottom: 50px;
        }

        .steps-form{
            padding-bottom: 45px;
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
                            <span  class="btn btn-success btn-circle ">1</span>
                            <p>Step 1</p>
                        </div>
                        <div class="steps-step steps-success">
                            <span   class="btn btn-success btn-circle" >2</span>
                            <p>Step 2</p>
                        </div>
                        <div class="steps-step">
                            <span  class="btn btn-default btn-circle" >3</span>
                            <p>Step 3</p>
                        </div>
                    </div>
                </div>

                <!-- <div class="row margin-div" style="margin-bottom: 30px">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Product Details</label>
                </div> -->

                <div class="row big-padding-bottom">
                    <div class="col-lg-8">

                        <div class="row margin-div" style="margin-bottom: 30px">
                            <h2>Product Details &nbsp <a href="{{route('admin.products.show', $product->id)}}" class="btn"><i class="fa fa-edit"></i></a></h2>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Product Name  -</label>
                            <div class="col-sm-9">
                                <label style="font-weight: 400 !important;" for="inputEmail3" class="col-form-label">{{$product->name}}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Product Code  -</label>
                            <div class="col-sm-9">
                                <label style="font-weight: 400 !important;" for="inputEmail3" class="col-form-label">{{$product->upc_code}}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Product Type  -</label>
                            <div class="col-sm-9">
                                <label style="font-weight: 400 !important;" for="inputEmail3" class="col-form-label">{{$product->type}}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label">Product Artists  -</label>
                            <div class="col-sm-9">
                                <label style="font-weight: 400 !important;" for="inputEmail3" class="col-form-label">{{$product->artistNames}}</label>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <img src="{{Config('constants.bucket.url').Config('filePaths.front.product').$product->image}}" width="200" height="200">
                    </div>

                </div>

                <div class="row" style="margin-top: 20px; margin-bottom: 40px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="col-sm-offset-2">
                                    <a href="{{url('admin/song/step-1').'?product_id='.$product->id.'&type=add'}}" class="btn btn-sm" type="button" style="color: #000; font-weight: bold;">
                                        <img src="{{url('assets/back/img/add_song.png')}}" alt="" width="100" height="100">
                                        <br>
                                        Add a new song
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="col-sm-offset-2">
                                    <a href="{{url('admin/products/'.$product->id.'/add/songs')."?type=add"}}" class="btn btn-sm" type="button" style="color: #000; font-weight: bold;">
                                        <img src="{{url('assets/back/img/exisitng.png')}}" alt="" width="100" height="100">
                                        <br>
                                        Add from existing songs
                                    </a>
                                </div>
                            </div>
{{--                            <div class="col-sm-10">--}}
{{--                                <div class="col-sm-offset-2">--}}
{{--                                    <a href="{{url('admin/playlist/step-2')}}" class="btn btn-sm" type="button" style="color: #000; font-weight: bold;">--}}
{{--                                        <img src="{{url('assets/back/img/add_song.png')}}" alt="" style="height: 34px;">--}}
{{--                                        <br>--}}
{{--                                        Add a new song--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-offset-6">--}}
{{--                                    <a href="{{url('admin/playlist/step-2')}}" class="btn btn-sm" type="button" style="color: #000; font-weight: bold;">--}}
{{--                                        <img src="{{url('assets/back/img/add_song.png')}}" alt="" style="height: 34px;">--}}
{{--                                        <br>--}}
{{--                                        Add from existing--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-offset-10">--}}
{{--                                    <form method="POST">--}}
{{--                                        {!!Form::token()!!}--}}
{{--                                        <button class="btn btn-primary" type="submit">Submit</button>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

{{--                <div class="col-sm-offset-10">--}}
{{--                    <form method="POST">--}}
{{--                       {!!Form::token()!!}--}}
{{--                       <button class="btn btn-primary" type="submit">Submit</button>--}}
{{--                    </form>--}}
{{--                </div>--}}

            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/sortable.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/purify.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/theme.min.js')}}"></script>
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){


            $("#product_type").select2();
            $("#project").select2();
            $("#product_category").select2();
            $("#advertisement_policy").select2();
            $("#content_policy").select2();

            let url = '{{url('admin/artists/search')}}';

            $("#primary_artist").select2({
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
                showUpload:false,
                //showRemove: false,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
                maxFileSize: 20,
            });

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
                    product_type: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    product_category: {
                        required: true
                    },
                    primary_artist: {
                        required: true
                    },
                    upc_code: {
                        required: true
                    },
                    image: {
                        required: true,
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
                    } else if(name == "product_category" || name == "project" || name == "product_type") {
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

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imagePreview')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);

                $('#imagePreview').show();
            }
        }


    </script>
@stop
