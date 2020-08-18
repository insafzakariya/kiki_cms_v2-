@extends('layouts.back.master') @section('current_title','Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}"/>
    <link href="{{url('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/front/css/datepicker/bootstrap-datepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}"/>

    <style type="text/css">

        .hide{
            display: none;
        }

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
        <h2>Notification Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/notification')}}">Notification</a>
            </li>
            <li class="active">
                <strong>add</strong>
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
                        <label class="col-sm-2 control-label">Section*<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select id="section" name="section" class="form-control select-simple" required>
                                <option value="GENERAL" selected >General</option>
                                <option value="MUSIC" >Music</option>
                                <option value="VIDEO" >Video</option>
                            </select>
                        </div>
                    </div>
                    <div id="div-video" >
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Program</label>
                            <div class="col-sm-7">
                                <select id="program" name="program" class="form-control select-simple">
                                    <option value="GENERAL" selected>General</option>
                                    <option value="MUSIC" >Music</option>
                                    <option value="VIDEO" >Video</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Episode</label>
                            <div class="col-sm-7">
                                <select id="episode" name="episode" class="form-control select-simple">
                                    <option value="MUSIC" selected>Music</option>
                                    <option value="VIDEO" >Video</option>
                                    <option value="GENERAL" >General</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="div-music" >

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Music</label>
                            <div class="col-sm-7">
                                <select id="music" name="music" class="form-control select-simple">
                                    <option value="SONG" selected>Song</option>
                                    <option value="ALBUM" >Album</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="div-song">
                            <label class="col-sm-2 control-label">Song</label>
                            <div class="col-sm-7">
                                <select id="song" name="song" class="form-control select-simple">
                                    <option value="SONG" selected>Song</option>
                                    <option value="ALBUM" >Album</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="div-album">
                            <label class="col-sm-2 control-label">Album</label>
                            <div class="col-sm-7">
                                <select id="album" name="album" class="form-control select-simple">
                                    <option value="SONG" selected>Song</option>
                                    <option value="ALBUM" >Album</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Date</label>
                        <div class="add-padding input-group col-sm-7">
                            <input type="text" id="notificatio-date" class="form-control boot-date" name="notificatio-date"  autocomplete="off" required>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Time</label>
                        <div class="add-padding col-sm-7">
                            <input type="time" class="form-control" id="notificatio-date" name="notificatio-date"  autocomplete="off" required>
                          
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">User Group</label>
                        <div class="add-padding  col-sm-7">
                            <select id="user-group" name="user-group" class="form-control select-simple" required>
                                <option value="SONG" selected>Song</option>
                                <option value="ALBUM" >Album</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">All Audience</label>
                        <div class="add-padding  col-sm-7">
                            <input type="checkbox" name="all-audiance"  id="all-audiance">
                        </div>
                    </div>

                    <div id="div-language">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Language</label>
                            <div class="add-padding  col-sm-7">
                                <select id="language" name="language" class="form-control select-simple">
                                    <option value="SINHALA" >Sinhala</option>
                                    <option value="ENGLISH" >English</option>
                                    <option value="TAMIL" >Tamil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><span style="font-weight: bold">Sinhala</span> </label>
                    </div>
                    <div id="div-sinhala">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="si-title"  id="si-title">
                            </div>
                        </div>
    
                        <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="si-description" name="si-description"></textarea>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Image </label>
                            <div class="col-sm-6">
                                <input id="si-image" name="si-image" type="file" class="image form-control after-error-placement" accept="image/*"  >
                                <p class="nameError float-left"></p>
                                <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size
                                    should be 175px *175px</p>
                            </div>
                        </div>    
                    </div>
                    
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><span style="font-weight: bold">English</span> </label>
                    </div>

                    <div id="div-english">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="en-title"  id="en-title">
                            </div>
                        </div>
    
                        <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="en-description" name="en-description"></textarea>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Image </label>
                            <div class="col-sm-6">
                                <input id="en-image" name=en-image" type="file" class="image form-control after-error-placement" accept="image/*"  >
                                <p class="nameError float-left"></p>
                                <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size
                                    should be 175px *175px</p>
                            </div>
                        </div>
    
                    </div>
                    
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><span style="font-weight: bold">Tamil</span> </label>
                    </div>

                    <div id="div-tamil">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="ta-title"  id="ta-title">
                            </div>
                        </div>
    
                        <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="ta-description" name="ta-description"></textarea>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">Image </label>
                            <div class="col-sm-6">
                                <input id="ta-image" name="ta-image" type="file" class="image form-control after-error-placement" accept="image/*"  >
                                <p class="nameError float-left"></p>
                                <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size
                                    should be 175px *175px</p>
                            </div>
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

            $("#div-video").addClass('hide');
            $("#div-music").addClass('hide');
            $("#div-album").addClass('hide');
            $( "#language" ).prop( "disabled", true );

            function disableScreen() {
                $(".overlay").show();
            }

            $('.boot-date').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true
            });

            $(".image").fileinput({
                theme: "fa",
                maxFileCount: 1,
                showUpload: false,
                validateInitialCount: true,
                //showRemove: false,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
                maxFileSize: {{ env('Upload_Image_Size') }},
            });


        });

        $("#section").change(function(){
            var val = $("#section").val();
            switch (val) {
                case 'GENERAL':
                    $("#div-video").addClass('hide');
                    $("#div-music").addClass('hide');
                    break;
                case 'MUSIC':
                    $("#div-video").addClass('hide');
                    $("#div-music").removeClass('hide');
                    break;
                case 'VIDEO':
                    $("#div-video").removeClass('hide');
                    $("#div-music").addClass('hide');
                    break;
            
                default:
                    break;
            }
        });

        $('#music').change(function(){
            var val = $("#music").val();
            switch (val) {
                case 'SONG':
                    $("#div-album").addClass('hide');
                    $("#div-song").removeClass('hide');
                    break;
                case 'ALBUM':
                    $("#div-song").addClass('hide');
                    $("#div-album").removeClass('hide');
                    break;
            
                default:
                    break;
            }
        });

        $("#all-audiance").click(function() {
            if ($('#all-audiance').prop('checked')){
                $("#language").prop( "disabled", false );

                $("#ta-title").prop( "disabled", true );
                $("#ta-description").prop( "disabled", true );
                $("#ta-image").prop( "disabled", true );

                $("#en-title").prop( "disabled", true );
                $("#en-description").prop( "disabled", true );
                $("#en-image").prop( "disabled", true );
            } else{
                $("#language").prop( "disabled", true );
                
                $("#si-title").prop( "disabled", false );
                $("#si-description").prop( "disabled", false );
                $("#si-image").prop( "disabled", false );

                $("#en-title").prop( "disabled", false );
                $("#en-description").prop( "disabled", false );
                $("#en-image").prop( "disabled", false );

                $("#ta-title").prop( "disabled", false );
                $("#ta-description").prop( "disabled", false );
                $("#ta-image").prop( "disabled", false );
            }
        });

        $('#language').change(function(){
            var val = $("#language").val();
            switch (val) {
                case 'SINHALA':
                    $("#si-title").prop( "disabled", false );
                    $("#si-description").prop( "disabled", false );
                    $("#si-image").prop( "disabled", false );

                    $("#en-title").prop( "disabled", true );
                    $("#en-description").prop( "disabled", true );
                    $("#en-image").prop( "disabled", true );

                    $("#ta-title").prop( "disabled", true );
                    $("#ta-description").prop( "disabled", true );
                    $("#ta-image").prop( "disabled", true );
                    break;
                case 'ENGLISH':
                    $("#si-title").prop( "disabled", true );
                    $("#si-description").prop( "disabled", true );
                    $("#si-image").prop( "disabled", true );

                    $("#en-title").prop( "disabled", false );
                    $("#en-description").prop( "disabled", false );
                    $("#en-image").prop( "disabled", false );

                    $("#ta-title").prop( "disabled", true );
                    $("#ta-description").prop( "disabled", true );
                    $("#ta-image").prop( "disabled", true );
                    break;
                case 'TAMIL':
                    $("#si-title").prop( "disabled", true );
                    $("#si-description").prop( "disabled", true );
                    $("#si-image").prop( "disabled", true );

                    $("#en-title").prop( "disabled", true );
                    $("#en-description").prop( "disabled", true );
                    $("#en-image").prop( "disabled", true );

                    $("#ta-title").prop( "disabled", false );
                    $("#ta-description").prop( "disabled", false );
                    $("#ta-image").prop( "disabled", false );
                    break;
            
                default:
                    break;
            }
        });




    </script>
@stop