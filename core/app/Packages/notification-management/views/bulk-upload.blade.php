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
        <h2>User Group Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/user-group')}}">User Group</a>
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
                <form method="POST" class="form-horizontal" id="form" enctype="multipart/form-data">
                    {!!Form::token()!!}

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name"
                                                      data-parsley-required-message="Name field is required">
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Template</label>
                        <div class="col-sm-10">
                            <div class="">
                                <a href="{{url('assets/back/fcm_user_group_template/user_group_upload.xlsx')}}" download>Download
                                    Template</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Upload File<span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                            <input id="upload_file" name="upload_file" type="file"  class="form-control after-error-placement" >
                            <p class="nameError float-left"></p>
                        </div>
                    </div>

                    
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="location.reload();">Cancel</button>
                            <button class="btn btn-primary submitFormBtn" id="submit-upload" type="submit">Done</button>
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

            function disableScreen() {
                $(".overlay").show();
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
                    let data = new FormData(newForm);

                    $.ajax({
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        url: "{{url('/admin/user-group/user-group-upload')}}",
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
                            console.log(response);
                            $(".overlay").hide();

                            toastr.success("User Group was successfully Updated");
                            location.reload();
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
                                console.log(data);
                                toastr.error(data.responseJSON.message);
                            }
                        }
                    });
                }
            });


        });


    </script>
@stop