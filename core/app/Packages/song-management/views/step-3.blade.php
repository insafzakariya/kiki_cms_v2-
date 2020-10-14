@extends('layouts.back.master') @section('current_title','Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link href="{{url('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/front/css/durationpicker/timesetter.css')}}" />

    <style type="text/css">
        .steps-form{
            padding-bottom: 30px;
        }

        .steps-form .steps-row:before {
            width: 66.66% !important;
        }

        div.steps-step:nth-child(3) {
            width: 45px !important;
        }

        #explicit-error {
            margin-left: 27%;
        }

        #canvas {
            background:#F7F7F7;
            text-align: center;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: 2;
            opacity: 0.6;
            display: none;
        }


    </style>

@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Song Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/song')}}">Song</a>
            </li>
            <li class="active">
                <strong>Add</strong>
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
                            <span  class="btn btn-success btn-circle" >3</span>
                            <p>Step 3</p>
                        </div>
                    </div>
                </div>

                <form method="POST" class="form-horizontal" id="form" enctype="multipart/form-data">
                    {!!Form::token()!!}
                    <input hidden name="song_id" value="{{$data ? $data->songId : ''}}">
                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class=" control-label">Song Artwork*</label>
                        </div>
                        <div class="col-lg-6 col-sm-7">
                            <div class="">
                                    <input id="song_image" name="song_image" class="after-error-placement file-loading"
                                           type="file" accept="image/*" >
                               {{-- mage size should be 175px * 175px--}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class="control-label">Track Upload*</label>
                        </div>
                        <div class="col-lg-6 col-sm-7" style="display: inline">
                            <input type="file" class="form-control after-error-placement " id="track" name="track" accept="audio/*"
                                   data-msg-accept="Please enter a value with a valid type(audio)" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class=" control-label">Duration*</label>
                        </div>

{{--                        <div class="col-lg-2 col-sm-2">--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <input type="number" min="0" max="59" class="form-control" value="{{$data ? $data->duration_minutes : ''}}" name="duration_minutes">--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <label class="control-label">Minutes</label>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="col-lg-2 col-sm-2">--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <input type="number" min="0" max="59" class="form-control" value="{{$data ? $data->duration_seconds : ''}}" name="duration_seconds">--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <label class="control-label">Seconds</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-lg-5" id="durationPicker">

                        </div>
                        <input type="hidden" id="durationValue" value="{{$data ? $data->durations : 0}}" name="duration">
                        <input type="hidden" id="durationMin" name="duration_minutes">
                        <input type="hidden" id="durationSec" name="duration_seconds">
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="cancelRedirect()">Back</button>
                            <button class="btn btn-primary" id="submit-upload" type="submit">Save</button>
                        </div>
                    </div>

                </form>


                <div id="canvas" style="background:#F7F7F7;">
                    <img style="display: inline-block; margin: 20% auto;" src="{{url('assets/back/img/loading.gif')}}">
                </div>

            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{url('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/back/js/jquery-validation-extension.js')}}"></script>
    <script src="{{asset('assets/front/js/durationpicker/timesetter.js')}}"></script>
    <script type="text/javascript">
        let product_id = '';
        let product_type = '';
        $(document).ready(function(){

            let url_string = window.location.href;
            let url_new = new URL(url_string);
             product_id = url_new.searchParams.get("product_id");
             product_type = url_new.searchParams.get("type");


            if (product_id)
                $("#product_id").val(product_id);


            $(".js-source-states").select2();
            $("#form").validate({
                rules: {
                    song_image :{
                        required:{
                            depends: function () {
                                return $('#song_image').fileinput('getFilesCount', true) == 0
                            }
                        } ,
                        fileSize_max:{{ env('Upload_Image_Size') }},
                        extension:"jpg|gif|png|jpeg|jfif"
                    },
                    track: {
                        required: {
                            depends: function () {
                                return $('#track').fileinput('getFilesCount', true) == 0
                            }
                        } ,
                        extension: "mp3",
                    },
                    duration_minutes: {
                        required: true,
                        min: 0,
                        max: 59
                    },
                    duration_seconds: {
                        required: true,
                        min: 0,
                        max: 59
                    }

                },
                messages: {
                    'track' :{
                        extension: "Please enter a value with a valid extension(MP3).",
                        mimetype: "Please enter a value with a valid extension(MP3).",
                    },
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    }else if(element.hasClass('after-error-placement')){
                        element.parent().parent().parent().after(error);
                    }else {
                        element.after(error);
                    }
                },
                submitHandler: function(form) {
                   /* if(!validateQuestionCheckboxes() || !validateQuestionsSelects()){
                        return false;
                    }else{*/
                        console.log("working");
                        $("#canvas").show();
                        $(".submit-btn-loader").css('display', 'block');
                        $("#submit-banner").prop('disabled', true);
                        $("#cancel-banner").prop('disabled', true);

                        var mins = $('#txtHours').val() ? $('#txtHours').val() : 0;
                        var secs = $('#txtMinutes').val() ? $('#txtMinutes').val() : 0;
                        $('#durationMin').val(mins);
                        $('#durationSec').val(secs);
                        form.submit();
                                            }
               // }
            });
            /*$("#song_image").fileinput({
                theme: "fa",
                maxFileCount: 1,
                showUpload:false,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
               // maxFileSize: 20,
            });*/
            $("#song_image").fileinput({
                theme: "fa",
                maxFileCount: 1,
                showUpload: false,
                //showRemove: false,
               // required: true,
                validateInitialCount: true,
                overwriteInitial: false,
                initialPreviewAsData: false,
                allowedFileTypes: ['image'],
               // allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                initialPreview: <?php echo json_encode($image); ?>,
                initialPreviewConfig: <?php echo json_encode($image_config) ?>,
                deleteExtraData: {
                    '_token': '{{csrf_token()}}',
                }
            });

            $("#track").fileinput({
                theme: "fa",
                maxFileCount: 1,
                showUpload: false,
                //showRemove: false,
                required: true,
                validateInitialCount: true,
                overwriteInitial: false,
                initialPreviewAsData: false,
                //allowedFileTypes: ['audio'],
               // allowedFileExtensions: ["mp3"],
                initialPreview: <?php echo json_encode($track); ?>,
                initialPreviewConfig: <?php echo json_encode($track_config) ?>,
                deleteExtraData: {
                    '_token': '{{csrf_token()}}',
                }
            });

            // $("#submit-upload").click(function(){
            //   $("#canvas").show();
            //   console.log("done");
            // });

            var options1 = {
                hour: {
                    value: 0,
                    min: 0,
                    max: 59,
                    step: 1,
                    symbol: "Min"
                },
                minute: {
                    value: 0,
                    min: 0,
                    max: 59,
                    step: 1,
                    symbol: "Sec"
                },
                direction: "increment", // increment or decrement
                inputHourTextbox: null, // hour textbox
                inputMinuteTextbox: null, // minutes textbox
                postfixText: "", // text to display after the input fields
                numberPaddingChar: '0' // number left padding character ex: 00052
            };

            $("#durationPicker").timesetter(options1).setValuesByTotalMinutes($('#durationValue').val());

        });

        function cancelRedirect() {
            if (product_id && product_type) {
                let urlTest = '';
                if (product_type == 'add')
                    urlTest = '/admin/products/' + product_id + '/add/step-2?type=' + product_type;
                else
                    urlTest = '/admin/products/' + product_id + '/add/step-3?type=' + product_type;
                location.href = '{{url()}}' + urlTest;
            } else {
                location.href = '{{url('admin/song')}}'
            }
        }


    </script>
@stop