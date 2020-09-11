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
                <form  class="form-horizontal" id="form" enctype="multipart/form-data" >
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
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Episode</label>
                            <div class="col-sm-7">
                                <select id="episode" name="episode" class="form-control select-simple">
                                    
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
                                   
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="div-album">
                            <label class="col-sm-2 control-label">Album</label>
                            <div class="col-sm-7">
                                <select id="album" name="album" class="form-control select-simple">
                                    
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
                            <input type="time" class="form-control" id="notificatio-time" name="notificatio-time"  autocomplete="off" required>
                          
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">User Group</label>
                        <div class="add-padding  col-sm-7">
                            <select id="user-group" name="user-group" class="form-control select-simple" required>
                         
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">All Audience</label>
                        <div class="add-padding  col-sm-7">
                            <input type="checkbox" name="all-audiance" value="check"  checked="checked" id="all-audiance" disabled>
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
                            <button class="btn btn-primary submitFormBtn" onclick="submitForm()" id="submit-upload" type="button">Done</button>
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

    function  submitForm(){
        var fd = new FormData();
        var files_si = $('#si-image')[0].files[0];
        var files_en = $('#en-image')[0].files[0];
        var files_ta = $('#ta-image')[0].files[0];
        var file=null;
       

        if(files_si!=null){
            file=files_si;
            fd.append('image_upload',file);

        }
        
        if(files_en!=null){
            file=files_en;
            fd.append('image_upload',file);

        }
        
        if(files_ta!=null){
            file=files_ta;
            fd.append('image_upload',file);

        }
        
                let section=document.getElementById('section');
                let contenttype=null;
                let contentid=null;
                let englishtittle=null;
                let englishdescription=null;
                let englishimage=null;
                let sinhalatittle=null;
                let sinhaladescription=null;
                let sinhalaimage=null;
                let tamiltittle=null;
                let tamildescription=null;
                let tamilimage=null;
                let uploadImage=null;



                if(section.value === 'MUSIC'){
                    contenttype=document.getElementById('music');

                    if(music.value === 'ALBUM'){
                        contentid=document.getElementById('album');
                    }else{                    
                        contentid=document.getElementById('song');
                    }
                }

                if(section.value === 'VIDEO'){
                  contenttype=document.getElementById('program');
                  contentid=document.getElementById('episode');
                  
                }
                
                let ddate=document.getElementById('notificatio-date');
                let dtime=document.getElementById('notificatio-time');
                let usergroup=document.getElementById('user-group');

            
                let all_audiance=document.getElementById('all-audiance');
                //ddate + dtime
                let notifiactiontime=ddate.value + ' ' + dtime.value;
        
                let language=document.getElementById('language');

                var selected=$('input[name="all-audiance"]:checked').val();



               if(selected === 'check'){
                   if(language.value === 'SINHALA'){
                    sinhalatittle=document.getElementById('si-title');
                    sinhaladescription=document.getElementById('si-description');
                    sinhalaimage=document.getElementById('si-image');
                   
                   }
                   if(language.value === 'ENGLISH'){
                    englishtittle=document.getElementById('en-title');
                    englishdescription=document.getElementById('en-description');
                    englishimage=document.getElementById('en-image');
                  
                   }
                   if(language.value === 'TAMIL'){
                    tamiltittle=document.getElementById('ta-title');
                    tamildescription=document.getElementById('ta-description');
                    tamilimage=document.getElementById('ta-image');
                  
                   }

               }else{
                    sinhalatittle=document.getElementById('si-title');
                    sinhaladescription=document.getElementById('si-description');
                    sinhalaimage=document.getElementById('si-image');
                   
               }
                
                let status=null;



                fd.append( 'user_group',usergroup.value);
                fd.append( 'section',section.value);
                fd.append( 'content_type',contenttype == undefined ? null : contenttype.value);
                fd.append( 'content_id',contentid == undefined ? null : contentid.value);
                fd.append( 'notification_time',notifiactiontime);
                fd.append( 'all_audiance',all_audiance.value);
                fd.append( 'language',language.value);
                fd.append( 'english_title',englishtittle == undefined ? null :englishtittle.value);
                fd.append( 'english_description', englishdescription== undefined ? null : englishdescription.value);
                fd.append( 'image_upload',file);
                fd.append( 'sinhala_title',sinhalatittle== undefined ? null : sinhalatittle.value);
                fd.append( 'sinhala_description',sinhaladescription== undefined ? null : sinhaladescription.value);
              
                fd.append( 'tamil_title', tamiltittle== undefined ? null : tamiltittle.value);
                fd.append( 'tamil_description',tamildescription == undefined ? null : tamildescription.value);
                
                fd.append( 'status',1);

        $.ajax({
            url: '{{ url('admin/notification/notification-add')}}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
               
            },
        });

      
    }
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
                maxFileSize: {{ env('Upload_Image_Size') }}
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

        $(document).ready(function () {
            // alert(document.getElementById('all-audiance').value);
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

        // $("#all-audiance").click(function() {
        //     if ($('#all-audiance').prop('checked')){
        //         $("#language").prop( "disabled", false );

        //         $("#ta-title").prop( "disabled", true );
        //         $("#ta-description").prop( "disabled", true );
        //         $("#ta-image").prop( "disabled", true );

        //         $("#en-title").prop( "disabled", true );
        //         $("#en-description").prop( "disabled", true );
        //         $("#en-image").prop( "disabled", true );
        //     } else{
        //         $("#language").prop( "disabled", true );
                
        //         $("#si-title").prop( "disabled", false );
        //         $("#si-description").prop( "disabled", false );
        //         $("#si-image").prop( "disabled", false );

        //         $("#en-title").prop( "disabled", false );
        //         $("#en-description").prop( "disabled", false );
        //         $("#en-image").prop( "disabled", false );

        //         $("#ta-title").prop( "disabled", false );
        //         $("#ta-description").prop( "disabled", false );
        //         $("#ta-image").prop( "disabled", false );
        //     }
        // });


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


        let url1 = '{{url('admin/song/songsearch')}}';
        $('#song').select2({
            placeholder: "Please select a song",
            tokenSeparators: [','],
            tags: true,
            minimumInputLength: 3,
            multiple: false,
            ajax: {
                type: "GET",
                url: url1,
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
                                id: item.songId
                            }
                        })
                    };
                },
                cache: true
            },
        });

        let url2 = '{{url('admin/playlist/searchalbum')}}';
        $('#album').select2({
            placeholder: "Please select a album",
            tokenSeparators: [','],
            tags: true,
            minimumInputLength: 3,
            multiple: false,
            ajax: {
                type: "GET",
                url: url2,
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
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });

        let url3 = '{{url('admin/notification/searchprogram')}}';
        $('#program').select2({
            placeholder: "Please select a program",
            tokenSeparators: [','],
            tags: true,
            minimumInputLength: 3,
            multiple: false,
            ajax: {
                type: "GET",
                url: url3,
                dataType: 'json',
                contentType: "application/json",
                delay: 250,
                data: function (params) {
                    return  'term='+params.term;
                     /*JSON.stringify({
                        term: params.term
                    });*/
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item, i) {
                            return {
                                text: item.programName,
                                id: item.programId
                            }
                        })
                    };
                },
                cache: true
            },
        });


        let url4 = '{{url('admin/notification/searchepisode')}}';
        $('#episode').select2({
            placeholder: "Please select a episode",
            tokenSeparators: [','],
            tags: true,
            minimumInputLength: 3,
            multiple: false,
            ajax: {
                type: "GET",
                url: url4,
                dataType: 'json',
                contentType: "application/json",
                delay: 250,
                data: function (params) {
                    return  'term='+params.term +'&programId='+$("#program").val();
                
                     /*JSON.stringify({
                        term: params.term
                    });*/
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item, i) {
                            return {
                                text: item.episodeName,
                                id: item.episodeId
                            }
                        })
                    };
                },
                cache: true
            },
        });



        let url5 = '{{url('admin/notification/searchuser')}}';
        $('#user-group').select2({
            placeholder: "Please select a user",
            tokenSeparators: [','],
            tags: true,
            minimumInputLength: 3,
            multiple: false,
            ajax: {
                type: "GET",
                url: url5,
                dataType: 'json',
                contentType: "application/json",
                delay: 250,
                data: function (params) {
                    return  'term='+params.term;
                
                     /*JSON.stringify({
                        term: params.term
                    });*/
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item, i) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });

        

        $('#form').on('submit',(function(e) {
            e.preventDefault();
               
                $.ajax({
                    url: '{{ url('admin/notification/notification-add')}}',
                    method: "POST",
                    data: new FormData(this),
                    success: function (resp) {

                        alert("Done!");
                    },
                    error: function (resp) {
                        if (resp.status == 401) {
                            $.ajax(this);
                        }
                    
                        alert("Fail!");
                    }
                });

        }));

        // $(document).ready(function () {
            
       
        //     $("#submit-upload").click(function () { 
          
             
        //         if(document.getElementById('section').value){

        //         }
                

        //         let section=document.getElementById('section');
        //         let contenttype=null;
        //         let contentid=null;
        //         let englishtittle=null;
        //         let englishdescription=null;
        //         let englishimage=null;
        //         let sinhalatittle=null;
        //         let sinhaladescription=null;
        //         let sinhalaimage=null;
        //         let tamiltittle=null;
        //         let tamildescription=null;
        //         let tamilimage=null;



        //         if(section.value === 'MUSIC'){
        //             contenttype=document.getElementById('music');

        //             if(music.value === 'ALBUM'){
        //                 contentid=document.getElementById('album');
        //             }else{                    
        //                 contentid=document.getElementById('song');
        //             }
        //         }

        //         if(section.value === 'VIDEO'){
        //           contenttype=document.getElementById('program');
        //           contentid=document.getElementById('episode');
                  
        //         }
                
        //         let ddate=document.getElementById('notificatio-date');
        //         let dtime=document.getElementById('notificatio-time');
        //         let usergroup=document.getElementById('user-group');

            
        //         let all_audiance=document.getElementById('all-audiance');
        //         //ddate + dtime
        //         let notifiactiontime=ddate.value + ' ' + dtime.value;
        
        //         let language=document.getElementById('language');

        //         var selected=$('input[name="all-audiance"]:checked').val();

        //        if(selected === 'check'){
        //            if(language.value === 'SINHALA'){
        //             sinhalatittle=document.getElementById('si-title');
        //             sinhaladescription=document.getElementById('si-description');
        //             sinhalaimage=document.getElementById('si-image');
        //            }
        //            if(language.value === 'ENGLISH'){
        //             englishtittle=document.getElementById('en-title');
        //             englishdescription=document.getElementById('en-description');
        //             englishimage=document.getElementById('en-image');
        //            }
        //            if(language.value === 'TAMIL'){
        //             tamiltittle=document.getElementById('ta-title');
        //             tamildescription=document.getElementById('ta-description');
        //             tamilimage=document.getElementById('ta-image');
        //            }

        //        }else{
        //             sinhalatittle=document.getElementById('si-title');
        //             sinhaladescription=document.getElementById('si-description');
        //             sinhalaimage=document.getElementById('si-image');
        //        }
                
        //         let status=null;


        //         let data = {};
        //         // let myForm = document.getElementById(this);
        //         var formData = new FormData(this);

        //         data={

        //             'user_group':usergroup.value,
        //             'section' :section.value,
        //             'content_type' : contenttype == undefined ? null : contenttype.value,
        //             'content_id':contentid == undefined ? null : contentid.value,
        //             'notification_time' :notifiactiontime,
        //             'all_audiance' : all_audiance.value,
        //             'language' :language.value,
        //             'english_title' :englishtittle == undefined ? null :englishtittle.value,
        //             'english_description' : englishdescription== undefined ? null : englishdescription.value,
        //             'english_image' :englishimage == undefined ? null : englishimage.value,
        //             'sinhala_title' :sinhalatittle== undefined ? null : sinhalatittle.value,
        //             'sinhala_description' :sinhaladescription== undefined ? null : sinhaladescription.value,
        //             'sinhala_image' :sinhalaimage== undefined ? null : sinhalaimage.value,
        //             'tamil_title' : tamiltittle== undefined ? null : tamiltittle.value,
        //             'tamil_description' :tamildescription == undefined ? null : tamildescription.value,
        //             'tamil_image' :tamilimage== undefined ? null : tamilimage.value,
        //             'status' :1,
        //             'formData':formData,
        //             "_token": "{{ csrf_token() }}"
                 
                    
        //         }

        //         console.log(data);
               
        //         $.ajax({
        //             url: '{{ url('admin/notification/notification-add')}}',
        //             method: "POST",
        //             async: true,
        //             data: data,
        //             tryCount: 0,
        //             retryLimit: 3,
        //             success: function (resp) {

        //                 alert("Done!");
        //             },
        //             error: function (resp) {
        //                 if (resp.status == 401) {
        //                     $.ajax(this);
        //                 }
                        
        //                 alert("Fail!");
        //             }
        //         });

        //     });
        // });



    </script>
@stop