@extends('layouts.back.master') @section('current_title','Artist/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Channel Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Channel/Edit</strong>
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
                    <input type="hidden" name="image_removed" id="image_removed" value="0">
                    <input type="hidden" name="vedio_removed" id="vedio_removed" value="0">
                	<div class="form-group"><label class="col-sm-2 control-label">Channel Name</label>
                    	<div class="col-sm-10"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_name_en" value="{{$exsist_channel->channelName}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_name_si" value="{{$exsist_channel->channelName_si}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_name_ta" value="{{$exsist_channel->channelName_ta}}"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5">
                        <textarea class="form-control" name="channel_description_en">{!! $exsist_channel->channelDesc !!}</textarea>
                        </div>
                	</div>

                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="channel_description_si">{!! $exsist_channel->channelDesc_si !!}</textarea>    
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" name="channel_description_ta">{!! $exsist_channel->channelDesc_ta !!}</textarea>  
                        </div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Channel Image</label>
                        <div class="col-sm-4">
                            <input id="channel_image" name="channel_image" type="file"  class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Intro Vedio</label>
                        <div class="col-sm-4">
                            <input id="intro_vedio" name="intro_vedio" type="file"  class="form-control" accept="video/mp4,video/x-m4v,video/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required"></label>
                        <div class="col-sm-4 form-check">
                        <input type="checkbox" class="form-check-input" name="kids_channel" 
                        <?php if( $exsist_channel->kids){ ?> checked <?php } ?> id="exampleCheck1">
                        <label class="form-check-label"  for="exampleCheck1">Kids Channel</label>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Content Policies</label>
                        <div class="col-sm-8" style="display: inline">
                            <div class="col-md-5 no-padding">
                                <select class="form-control policy" id="policySelector" name="advertisementPolicy" style="width: 90%;" multiple="multiple">
                                  
                                @foreach ($channelContentPolicies as $channelContentPolicy)
                                <option value="{{$channelContentPolicy->PolicyID}}" selected>{{$channelContentPolicy->Name}}</option>
                                @endforeach
                                  
                                </select>
                            </div>

                            <div class="col-md-5">
                                <select class="form-control policy" id="content_policies" name="content_policies[]"
                                        style="width:90%;" multiple >
                                        @foreach ($exsist_channel->getContentPolices as $policy)
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
                            <?php if($advertismentPolicy->PolicyID == $exsist_channel->advertisementPolicy){?>
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
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple" >
                            <?php if(is_null(json_decode($exsist_channel->search_tag))){ ?>
                               
                           <?PHP  }else{ ?>
                            @foreach (json_decode($exsist_channel->search_tag) as $tag)
                                <option value="{{$tag}}" selected="selected">{{$tag}}</option>
                                @endforeach
                            <?php } ?>
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
<script type="text/javascript">
	$(document).ready(function(){
        $('.select-simple-tag').select2({
            tags: true,
            // data: ["Clare","Cork","South Dublin"],
            tokenSeparators: [','], 
            placeholder: "Add your tags here",
            /* the next 2 lines make sure the user can click away after typing and not lose the new tag */
            selectOnClose: true, 
            closeOnSelect: false
        });

        $('select[name="advertisment_policy"]').select2({
            // multiple: true,
        });

		$("#form").validate({
            rules: {
                channel_name_en: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                channel_name_si: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                channel_name_ta: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                channel_description_en: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                channel_description_si: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                channel_description_ta: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                }
                // ,
                // image: {
                //     required: true,
                //     accept: "image/*",
                //     dimension: [175,175],
                //     filesize_max_kb: {{ env('Upload_Image_Size') }}

                // },
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


        $("#channel_image").fileinput({
                theme: "fa",
                showUpload: false,
                showRemove: true,
                multiple: false,
                initialPreviewShowDelete: false,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                overwriteInitial: true,
                initialPreview: <?php echo json_encode($image); ?>,
                initialPreviewConfig: <?php echo json_encode($image_config) ?>
                
            }).on('filecleared', function() {
                $("#image_removed").val(1);
            });
        $("#intro_vedio").fileinput({
            
                showUpload: false,
                showRemove: true,
                multiple: false,
                initialPreviewShowDelete: false,
                initialPreviewAsData: false,
               
                allowedFileTypes: ['video'],
                allowedFileExtensions: ["mp4"],
                overwriteInitial: true,
                initialPreview: <?php echo json_encode($intro_vedio); ?>,
                initialPreviewConfig: <?php echo json_encode($intro_vedio_config) ?>
                
            }).on('filecleared', function() {
                $("#vedio_removed").val(1);
            });

	
	
</script>
@stop