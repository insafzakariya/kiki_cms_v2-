@extends('layouts.back.master') @section('current_title','Episode / ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Episode Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Episode/ADD</strong>
        </li>
    </ol>
</div>                  
@stop
@section('content')

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">
                      
                <form method="POST" class="form-horizontal" id="form"  enctype="multipart/form-data">
                	{!!Form::token()!!}

                	<div class="form-group"><label class="col-sm-2 control-label">Episode Name</label>
                    	<div class="col-sm-8"><input type="text" class="form-control" name="episode_name_en"></div>
                	</div>

                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="episode_description_en"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="episode_description_si"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="episode_description_ta"></div>
                	</div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Channels </label>
                    	<div class="col-sm-5">
                        <select  name="channels[]" class="form-control" multiple="multiple">
                        <option></option>
                        @foreach ($channels as $channel)
                        <option value="{{$channel->channelId}}">{{$channel->channelName}}</option>
                        @endforeach
                      
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Programme </label>
                    	<div class="col-sm-5">
                        <select  name="programme" id="programme" class="form-control select-simple" >
                            
                            </select>
                        </div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required"></label>
                        <div class="col-sm-4 form-check">
                        <input type="checkbox" class="form-check-input" name="trailer" id="trailer_checkbox">
                        <label class="form-check-label"  for="trailer_checkbox">Trailer</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Thumb Image</label>
                        <div class="col-sm-4">
                            <input id="thumb_image" name="thumb_image[]" type="file" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Start Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="start_date" name="start_date" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">End Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="end_date" name="end_date" vale="31-12-2099" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Publish Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="publish_date" name="publish_date" class="">
                        </div>
                	</div>

                    <div class="form-group"><label class="col-sm-2 control-label">Video Quality </label>
                    	<div class="col-sm-5">
                        <select  name="video_quality" class="form-control" multiple="multiple" >
                            <option value="720p">720p</option>
                            <option value="480p">480p</option>
                            <option value="360p">360p</option>
                            <option value="240p">240p</option>
                            <option value="144p">144p</option>
                            
                            </select>
                        </div>
                	</div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">content Policies</label>
                        <div class="col-sm-8" style="display: inline">
                            <div class="col-md-5 no-padding">
                                <select class="form-control policy" id="policySelector" name="content_policies_lft" style="width: 90%;" multiple="multiple">
                                  
                                @foreach ($episodeContentPolicies as $episodeContentPolicy)
                                <option value="{{$episodeContentPolicy->PolicyID}}">{{$episodeContentPolicy->Name}}</option>
                                @endforeach
                                  
                                </select>
                            </div>

                            <div class="col-md-5">
                                <select class="form-control policy" id="content_policies" name="content_policies[]"
                                        style="width:90%;" multiple >

                                </select>
                                <label id="content_error" class="text-danger" for="content_policies"></label>

                            </div>

                            <input type="hidden" id="content_count" value="" name="content_count">

                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Search Tags</label>
                        <div class="col-sm-8">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                            </select>
                        </div>
                    </div>
                    
                	<div class="hr-line-dashed"></div>
	                <div class="form-group">
	                    <div class="col-sm-8 col-sm-offset-2">
	                        <button class="btn btn-default" type="button" onclick="location.reload();">Cancel</button>
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
<script src="{{asset('assets/back/flatpicker/flatpicker')}}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->


<script type="text/javascript">
	$(document).ready(function(){
        $('.select-simple-tag').select2({
            tags: true,
            multiple: true,
            tokenSeparators: [','],
        }).on('select2:open', function (e) {
            $('.select2-container--open .select2-dropdown--below').css('display', 'none');
        });

        $("#start_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: ["today"]
            }
        );
        $("#publish_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: ["today"]
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: [ "2099-12-31"]
            }
        );

  
        $('#programme').select2({
            placeholder: "Please select a program",
            tokenSeparators: [','],
            tags: true,
            minimumInputLength: 3,
            multiple: false,
            ajax: {
                type: "GET",
                url: '{{url('episode/search/programme')}}',
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


        $('select[name="video_quality"]').select2({
            placeholder: "Choose Quality ",
            // multiple: true,
        });
        $('select[name="channels[]"]').select2({
            
            placeholder: "Choose Channels ",
            // multiple: true,
            
        });

      

		$("#form").validate({
            rules: {
                episode_name_en: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                episode_description_en: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                episode_description_si: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                episode_description_ta: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                // tags: {
                //     required: true

                // }
                // ,
                // 'thumb_image[]': {
                //     required: true,
                //     accept: "image/*",
                //     dimension: [175,175],
                //     filesize_max_kb: {{ env('Upload_Image_Size') }}

                // }
                // ,
                // 'cover_image[]': {
                //     required: true,
                //     accept: "image/*",
                //     dimension: [175,175],
                //     filesize_max_kb: {{ env('Upload_Image_Size') }}

                // }
                // ,
                // tags: {
                //     required: true

                // }
               
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
	});
    
    //Policy Selector
    $('#policySelector').on("click", "option", function() {
            let optionSelected = $(this);
            let valueSelected = optionSelected.val();
            let textSelected = optionSelected.text();
            if(valueSelected){
                $('#content_policies').append($('<option>', {
                    value: valueSelected,
                    text : textSelected,
                    selected: true
                }));

                $(this).remove();
            }
        });

    let contentPolicies = $('#content_policies');

    contentPolicies.on("click", "option", function() {
        let optionSelected = $(this);
        let valueSelected = optionSelected.val();
        let textSelected = optionSelected.text();
        if(valueSelected){
            $('#policySelector').append($('<option>', {
                value: valueSelected,
                text : textSelected
            }));
            $(this).remove();
            contentPolicies.find('option').prop('selected', true);
            //$('#content_count').val($('#content_policies').find('option').length);
        }
    });

    $("#thumb_image").fileinput({
        uploadUrl: "", // server upload action
        dropZoneEnabled: true,
        uploadAsync: false,
        minFileCount: 1,
        showRemove: true,
        showUpload:false,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
        
    });;
	
    $("#cover_image").fileinput({
        uploadUrl: "", // server upload action
        dropZoneEnabled: true,
        uploadAsync: false,
        minFileCount: 2,
        showRemove: true,
        showUpload:false,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
        
    });;
	
	
</script>
@stop