@extends('layouts.back.master') @section('current_title','Programme/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/flatpicker/flatpickr.min.css')}}" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Programme Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Programme/ADD</strong>
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

                	<div class="form-group"><label class="col-sm-2 control-label">Programme Name</label>
                    	<div class="col-sm-10"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_name_en"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_name_si"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_name_ta"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Channels </label>
                    	<div class="col-sm-5">
                        <select  name="channels[]" class="form-control" >
                        @foreach ($channels as $channel)
                        <option value="{{$channel->channelId}}">{{$channel->channelName}}</option>
                        @endforeach
                      
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Programme Type </label>
                    	<div class="col-sm-5">
                        <select  name="programme_type" class="form-control" >
                            <option value="live">Live</option>
                            <option value="vod">VOD</option>
                            <option value="reality">Reality</option>
                            </select>
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
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Thumb Image</label>
                        <div class="col-sm-4">
                            <input id="thumb_image" name="thumb_image[]" type="file" multiple class="form-control" accept="image/*">
                            <label id="thumb_image-error" class="error" for="thumb_image"></label>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_description_en"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_description_si"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_description_ta"></div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Cover Image</label>
                        <div class="col-sm-4">
                            <input id="cover_image" name="cover_image[]" type="file" multiple class="form-control" accept="image/*">
                            <label id="cover_image-error" class="error" for="cover_image"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required"></label>
                        <div class="col-sm-4 form-check">
                        <input type="checkbox" class="form-check-input" name="kids_channel" id="exampleCheck1">
                        <label class="form-check-label"  for="exampleCheck1">Kids Channel</label>
                        </div>
                    </div>
                 
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Programme Policies</label>
                        <div class="col-sm-8" style="display: inline">
                            <div class="col-md-5 no-padding">
                                <select class="form-control policy" id="policySelector" name="content_policies_lft" style="width: 90%;" multiple="multiple">
                                  
                                @foreach ($programmeContentPolicies as $programmeContentPolicy)
                                <option value="{{$programmeContentPolicy->PolicyID}}">{{$programmeContentPolicy->Name}}</option>
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
                    <div class="form-group"><label class="col-sm-2 control-label">Advertisment Policy</label>
                    	<div class="col-sm-5">
                        <select  name="advertisment_policy" class="form-control" >
                        @foreach ($advertismentPolicies as $advertismentPolicy)
                        <option value="{{$advertismentPolicy->PolicyID}}">{{$advertismentPolicy->Name}}</option>
                        @endforeach
                      
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Likes </label>
                    	<div class="col-sm-5">
                        <select  name="likes" class="form-control" >
                            <option value="1">enable</option>
                            <option value="0">disable</option>
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Subtitles </label>
                    	<div class="col-sm-5">
                            <input type="radio" checked id="yes" name="subtitle" value="1">
                            <label for="yes">Yes</label><br>
                            <input type="radio" id="no" name="subtitle" value="0">
                            <label for="no">No</label><br>
                      
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Search Tags *</label>
                        <div class="col-sm-8">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                            </select>
                            <label id="tags[]-error" class="error" for="tags[]"></label>
                        </div>
                    </div>
                    
                	<div class="hr-line-dashed"></div>
	                <div class="form-group">
	                    <div class="col-sm-8 col-sm-offset-2">
	                        <button class="btn btn-default" type="button" onclick="location.reload();">Cancel</button>
	                        <button class="btn btn-primary" id="btn_submit_form" type="button">Done</button>
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
                allowInput:true,
                dateFormat: "Y-m-d H:i",
                defaultDate: ["today"]
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                allowInput:true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: [ "2099-12-31"]
            }
        );

        $('select[name="advertisment_policy"]').select2({
            // multiple: true,
        });
        $('select[name="channels[]"]').select2({
            minimumResultsForSearch: -1,
            placeholder: function(){
                $(this).data('placeholder');
            },
            multiple: true,
            allowClear: true
            
        });

        $('select[name="programme_type"]').select2({
            // multiple: true,
        });
        $('select[name="likes"]').select2({
            // multiple: true,
        });

        //Custom Validation


        jQuery.validator.addMethod("thumb_image_va", function(value, element){
            const min_file_count = $('#thumb_image').data("fileinput").options.minFileCount;
            const exsist_file_count = $('#thumb_image').data("fileinput").filestack.length;
            if(exsist_file_count >=1){
                return true;
            }else{
                return false;
            }
         
        }, "You must select at least 1 files to upload. Please retry your upload!"); 

        jQuery.validator.addMethod("cover_image_va", function(value, element){
            const min_file_count = $('#cover_image').data("fileinput").options.minFileCount;
            const exsist_file_count = $('#cover_image').data("fileinput").filestack.length;
            if(exsist_file_count >=1){
                return true;
            }else{
                return false;
            }
         
        }, "You must select at least 1 files to upload. Please retry your upload!"); 

		$("#form").validate({
            rules: {
                programme_name_en: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                programme_name_si: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                programme_name_ta: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                programme_description_en: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                programme_description_si: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                programme_description_ta: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                'tags[]': {
                    required: true

                }
                ,
                'thumb_image[]': {
                    // required: true,
                    thumb_image_va:true
                   

                },
                'cover_image[]': {
                    // required: true,
                    cover_image_va:true
                   

                }
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
               
            }
            // ,
            // submitHandler: function(form) {
               
            //     form.submit();
            // }
        });

        $('#btn_submit_form').click(function() {
           
            if($("#form").valid()){   // test for validity
                $("#form").submit();
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
        // minFileCount: 3,
        showRemove: true,
        showUpload:false,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
        
    });;
	
    $("#cover_image").fileinput({
        uploadUrl: "", // server upload action
        dropZoneEnabled: true,
        uploadAsync: false,
        // minFileCount: 2,
        showRemove: true,
        showUpload:false,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
        
    });;
	
	
</script>
@stop