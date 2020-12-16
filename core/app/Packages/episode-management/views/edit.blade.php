@extends('layouts.back.master') @section('current_title','Programme/Edit')
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
        <li>
            <a href="{{url('/episode')}}">Episode</a>
        </li>
        <li class="active">
            <strong>Edit</strong>
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
                	<div class="form-group"><label class="col-sm-2 control-label">Episode Name</label>
                    	<div class="col-sm-8"><input type="text" class="form-control" name="episode_name_en" value="{{$exsist_episode->episodeName}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="episode_description_en">{!! $exsist_episode->description !!}</textarea>
                        </div>
                	</div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="episode_description_si">{!! $exsist_episode->episodeDesc_si !!}</textarea>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="episode_description_ta">{!! $exsist_episode->episodeDesc_ta !!}</textarea>
                        </div>
                	</div>
                    

                    <div class="form-group"><label class="col-sm-2 control-label">Programme </label>
                    	<div class="col-sm-5">
                        <select  name="programme" id="programme" class="form-control select-simple" >
                        @if(isset($exsist_episode->getProgramme))
                        <option value="{{$exsist_episode->getProgramme->programId}}" selected="selected">{{$exsist_episode->getProgramme->programName}}</option>
                        @endif
                        
                            </select>
                        </div>
                	</div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required"></label>
                        <div class="col-sm-4 form-check">
                        <input type="checkbox" class="form-check-input" name="trailer" 
                        <?php if( $exsist_episode->isTrailer){ ?> checked <?php } ?> id="exampleCheck1">
                        <label class="form-check-label"  for="exampleCheck1">Trailer</label>
                        </div>
                    </div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Start Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="start_date" name="start_date" value="{$exsist_episode->start_date}}" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">End Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="end_date" name="end_date" value="{$exsist_episode->end_date}}" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Publish Date</label>
                    	<div class="col-sm-5">
                        <input type="date" id="publish_date" name="publish_date" value="{$exsist_episode->publish_date}}" class="">
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Video Quality </label>
                    	<div class="col-sm-5">
                        <select  name="video_quality[]" class="form-control" multiple="multiple" >
                            @foreach($video_qualities as $key =>$video_quality  )
                            <option value="{{$key}}">{{$video_quality}}</option>  
                            @endforeach                          
                            </select>
                        </div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Thumb Image</label>
                        <div class="col-sm-4">
                            <input id="thumb_image" name="thumb_image[]" type="file" multiple class="form-control" accept="image/*">
                        </div>
                    </div>
      
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Episode Policies</label>
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
                                        @foreach ($exsist_episode->getContentPolices as $policy)
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
                                <?php if($advertismentPolicy->PolicyID == $exsist_episode->advertisement_policy){?>
                                    <option value="{{$advertismentPolicy->PolicyID}}" selected>{{$advertismentPolicy->Name}}</option>
                                <?php }else{?>
                                    <option value="{{$advertismentPolicy->PolicyID}}">{{$advertismentPolicy->Name}}</option>
                                <?php }?>
                            @endforeach
                      
                            </select>
                        </div>
                	</div>
                   
                    <div class="form-group"><label class="col-sm-2 control-label">Search Tags</label>
                        <div class="col-sm-8">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                            @if(json_decode($exsist_episode->search_tag) !== null)
                            @foreach (json_decode($exsist_episode->search_tag) as $tag)
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
                allowInput:true,
                dateFormat: "Y-m-d H:i",
                defaultDate: ["<?php echo $exsist_episode->start_date; ?> "],
            }
        );

        $("#end_date").flatpickr(
            {
                enableTime: true,
                allowInput:true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: ["<?php echo $exsist_episode->end_date; ?> "],
            }
        );
        $("#publish_date").flatpickr(
            {
                enableTime: true,
                allowInput:true,
                dateFormat: "Y-m-d H:i",
                // maxDate: "31.12.2099",
                defaultDate: ["<?php echo $exsist_episode->publish_date; ?> "],
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

        $('select[name="advertisment_policy"]').select2({
            // multiple: true,
        });
       

        $('select[name="video_quality[]"]').select2({
            placeholder: "Choose Quality ",
            // multiple: true,
        }).select2('val', <?php echo $exsist_episode->video_quality; ?>);;
        

        jQuery.validator.addMethod("programmeSelect", function(value, element){
            if (value) {
                return true;
            }else{
                return false;
            }
            // console.log(value);
            // // if (/^[0-9]{9}[vVxX]$/.test(value)) {
            //     return false;  // FAIL validation when REGEX matches
            // // } else {
            // //     return true;   // PASS validation otherwise
            // // };
        }, "No Programme selected"); 
        
        jQuery.validator.addMethod("thumb_image_va", function(value, element){
            const min_file_count = $('#thumb_image').data("fileinput").options.minFileCount;
            const exsist_file_count = $('#thumb_image').data("fileinput").filestack.length;
            const initialPreview_file_count = $('#thumb_image').data("fileinput").initialPreview.length;
            console.log($('#thumb_image').data("fileinput"));
            if((exsist_file_count+initialPreview_file_count) >=1){
                return true;
            }else{
                return false;
            }
         
        }, "You must select at least 1 files to upload. Please retry your upload!"); 

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
                
                programme: {
                    required: true,
                    programmeSelect: true

                },
                'video_quality[]': {
                    required: true

                }
                // ,
                // 'tags[]': {
                //     required: true

                // }
                ,
                
                'thumb_image[]': {
                    // required: true,
                    thumb_image_va:true
                }
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
        // uploadUrl: "", // server upload action
        dropZoneEnabled: false,
        uploadAsync: false,
        // minFileCount: 1,
        maxFileCount: 1,

        showRemove: false,
        validateInitialCount: true,
        showUpload:false,
        overwriteInitial: true,
        allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
        initialPreview: <?php echo json_encode($thumb_image); ?>,
        initialPreviewConfig: <?php echo json_encode($thumb_image_config) ?>
        
    }).on('filecleared', function() {
        $("#thumb_image_removed").val(1);
    });;
	
    
	
</script>
@stop