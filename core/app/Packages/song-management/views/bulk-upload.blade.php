@extends('layouts.back.master') @section('current_title','Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}"/>
    <link href="{{url('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/front/css/datepicker/bootstrap-datepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}"/>

    <style type="text/css">

        .overlay {
            background-color:#EFEFEF;
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 10000;
            top: 0px;
            left: 0px;
            opacity: .5; /* in FireFox */ 
            filter: alpha(opacity=50); /* in IE */
            text-align: center;
        }

        .overlay-gif{
            margin: 15% 0;
            vertical-align: middle;
        }

        .steps-form {
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
            background: #F7F7F7;
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
        .add-padding{
            padding-left: 15px !important;
            padding-right: 15px !important;
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
                <strong>Bulk Upload</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <form method="POST" class="form-horizontal" id="form" enctype="multipart/form-data">
                    {!!Form::token()!!}

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class=" control-label">Date*</label>
                        </div>
                        <div class="add-padding input-group col-lg-4 col-sm-5">
                            <input type="text" id="upload_date" name="upload_date" class="form-control boot-date"
                                   autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class=" control-label">Template</label>
                        </div>
                        <div class="col-lg-4 col-sm-5">
                            <div class="">
                                <a href="{{url('assets/back/bulk_upload_template/song_upload.xlsx')}}" download>Download
                                    Template</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class=" control-label">Upload File*</label>
                        </div>
                        <div class="col-lg-4 col-sm-5">
                            <div class="">
                                <input id="upload_file" name="upload_file" class="form-control after-error-placement"
                                       type="file">
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-primary submitFormBtn" id="submit-upload" type="submit">Upload</button>
                        </div>
                    </div>

                </form>


                <div id="canvas" style="background:#F7F7F7;">
                    <img style="display: inline-block; margin: 15% auto;" src="{{url('assets/back/img/loading.gif')}}">
                </div>

            </div>
        </div>
    </div>

    <div class="overlay" style="display: none;">
        <img class="overlay-gif" src="{{url('assets/back/img/loading.gif')}}">
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{url('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/front/js/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/back/js/jquery-validation-extension.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            // document.getElementById("btn").onclick = disableScreen;

            function disableScreen() {
                $(".overlay").show();
                // creates <div class="overlay"></div> and 
                // adds it to the DOM
                // var div= document.createElement("div");
                // div.className += "overlay";
                // document.body.appendChild(div);
            }

            $('.boot-date').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true
            });

            $("#form").validate({
                rules: {
                    upload_date: {
                        required: true
                    },
                    upload_file: {
                        required: true,
                        extension: "xlsx",
                    },
                },
                messages: {},
                errorPlacement: function (error, element) {
                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    } else if (element.hasClass('after-error-placement')) {
                        element.parent().parent().parent().after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form) {
                    $(".submitFormBtn").prop('disabled', true);
                    console.log("working");
                    disableScreen();
                    let newForm = $('#form')[0];
                    // Create an FormData object
                    let data = new FormData(newForm);

                    $.ajax({
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        url: "{{url('/admin/song/bulk-upload')}}",
                        data:  data,
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        async:false,
                        success: function(response) {
                            $(".submitFormBtn").prop('disabled', true);
                            $(".submit-btn-loader").css('display', 'block');
                            console.log("response");
                            $(".overlay").hide();

                            toastr.success("Song was successfully Updated");
                            //location.reload();
                        },
                        error: function(data) {
                            $(".overlay").hide();
                            $(".submitFormBtn").prop("disabled", false);
                            $(".submit-btn-loader").css('display', 'none');
                            if( data.status === 422 ) {
                                let errors = data.responseJSON.errors;
                                $.each(errors, function(index, value){
                                    $.each(value, function (index, message) {
                                        toastr.error(message);
                                    });
                                });
                            }else if(data.status === 500 ){
                                toastr.error('Whoops, looks like something went wrong.. Please contact Admin');
                            }else{

                                toastr.error(data.responseJSON.message);
                            }
                        }
                    });
                }

            });


        });


    </script>
@stop