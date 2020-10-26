@extends('layouts.back.master') @section('current_title','Programme/Edit')
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
            <strong>Programme/Edit</strong>
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
                    <input type="hidden" name="thumb_image_removed" id="thumb_image_removed" value="0">
                    <input type="hidden" name="cover_image_removed" id="cover_image_removed" value="0">
                	<div class="form-group"><label class="col-sm-2 control-label">Programme Name</label>
                    	<div class="col-sm-10"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_name_en" value="{{$exsist_programme->programName}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_name_si" value="{{$exsist_programme->programmeName_si}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="programme_name_ta" value="{{$exsist_programme->programmeName_ta}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Channels </label>
                    	<div class="col-sm-5">
                        <select  name="channels[]" class="form-control" >
                        @foreach ($channels as $channel)
                            <option value="{{$channel->channelId}}" >{{$channel->channelName}}</option>
                        @endforeach
                      
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Programme Type </label>
                    	<div class="col-sm-5">
                        <select  name="programme_type" class="form-control" >
                        @if($exsist_programme->programType =='live')
                            <option value="live"  selected >Live</option>
                        @else
                            <option value="live"  selected >Live</option>
                        @endif

                        @if($exsist_programme->programType =='vod')
                            <option value="vod" selected>VOD</option>
                        @else
                            <option value="vod">VOD</option>
                        @endif
                       
                        @if($exsist_programme->programType =='reality')
                            <option value="reality" selected>Reality</option>
                        @else
                            <option value="reality">Reality</option>
                        @endif
                            
                            
                            
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Start Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="start_date" name="start_date" value="{$exsist_programme->start_date}}" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">End Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="end_date" name="end_date" value="{$exsist_programme->end_date}}" class="">
                        </div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Thumb Image</label>
                        <div class="col-sm-4">
                            <input id="thumb_image" name="thumb_image[]" type="file" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="programme_description_en">{!! $exsist_programme->description !!}</textarea>
                        </div>
                	</div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="programme_description_si">{!! $exsist_programme->programmeDesc_si !!}</textarea>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="programme_description_ta">{!! $exsist_programme->programmeDesc_ta !!}</textarea>
                        </div>
                	</div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Cover Image</label>
                        <div class="col-sm-4">
                            <input id="cover_image" name="cover_image[]" type="file" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required"></label>
                        <div class="col-sm-4 form-check">
                        <input type="checkbox" class="form-check-input" name="kids_programme" 
                        <?php if( $exsist_programme->kids){ ?> checked <?php } ?> id="exampleCheck1">
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
                                        @foreach ($exsist_programme->getContentPolices as $policy)
                                        <option value="{{$policy->PolicyID}}" selected>{{$policy->getPolicy->Name}}</option>
                                        @endforeach
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
                                <?php if($advertismentPolicy->PolicyID == $exsist_programme->advertisementPolicy){?>
                                    <option value="{{$advertismentPolicy->PolicyID}}" selected>{{$advertismentPolicy->Name}}</option>
                                <?php }else{?>
                                    <option value="{{$advertismentPolicy->PolicyID}}">{{$advertismentPolicy->Name}}</option>
                                <?php }?>
                            @endforeach
                      
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Likes </label>
                    	<div class="col-sm-5">
                        <select  name="likes" class="form-control" >
                            @if($exsist_programme->likes)
                            <option value="1" selected>enable</option>
                            <option value="0" >disable</option>
                            @else
                            <option value="1" >enable</option>
                            <option value="0" selected>disable</option>
                            @endif
                           
                            
                            </select>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Subtitles </label>
                    	<div class="col-sm-5">
                            @if($exsist_programme->subtitles)
                            <input type="radio" checked id="yes" name="subtitle" value="1">
                            <label for="yes">Yes</label><br>
                            <input type="radio" id="no" name="subtitle" value="0">
                            <label for="no">No</label><br>
                            @else
                            <input type="radio"  id="yes" name="subtitle" value="1">
                            <label for="yes">Yes</label><br>
                            <input type="radio" checked id="no" name="subtitle" value="0">
                            <label for="no">No</label><br>
                            @endif
                            
                            
                      
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Search Tags</label>
                        <div class="col-sm-8">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                            @if(json_decode($exsist_programme->search_tag) !== null)
                            @foreach (json_decode($exsist_programme->search_tag) as $tag)
                            <option value="{{$tag}}" selected="selected">{{$tag}}</option>
                            @endforeach
                            @endif
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
                defaultDate: ["<?php echo $exsist_programme->start_date; ?> "],
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: ["<?php echo $exsist_programme->end_date; ?> "],
            }
        );


        $('select[name="advertisment_policy"]').select2({
            // multiple: true,
        });
        $('select[name="channels[]"]').select2({
            // placeholder: "Choose Channels ",
            multiple: true,
            
        }).select2('val', <?php echo json_encode($used_channel_ids); ?>);

        $('select[name="programme_type"]').select2({
            // multiple: true,
        });
        $('select[name="likes"]').select2({
            // multiple: true,
        });

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
                // tags: {
                //     required: true

                // }
                // ,
                // 'thumb_image[]': {
                //     required: true,
                //     accept: "image/*",
                //     // dimension: [175,175],
                //     // filesize_max_kb: {{ env('Upload_Image_Size') }}

                // }
                // ,
                // 'cover_image[]': {
                //     required: true,
                //     accept: "image/*",
                //     // dimension: [175,175],
                //     // filesize_max_kb: {{ env('Upload_Image_Size') }}

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
        minFileCount: 3,
        showRemove: false,
        showUpload:false,
        overwriteInitial: true,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
        initialPreview: <?php echo json_encode($thumb_image); ?>,
        initialPreviewConfig: <?php echo json_encode($thumb_image_config) ?>
        
    }).on('filecleared', function() {
        $("#thumb_image_removed").val(1);
    });;
	
    $("#cover_image").fileinput({
        uploadUrl: "", // server upload action
        dropZoneEnabled: true,
        uploadAsync: false,
        minFileCount: 2,
        showRemove: false,
        showUpload:false,
        overwriteInitial: true,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
        initialPreview: <?php echo json_encode($cover_image); ?>,
        initialPreviewConfig: <?php echo json_encode($cover_image_config) ?>
        
    }).on('filecleared', function() {
        $("#cover_image_removed").val(1);
    });
	
	
</script>
@stop