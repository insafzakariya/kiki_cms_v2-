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
            <strong>Channel/ADD</strong>
        </li>
    </ol>
</div>                  
@stop
@section('content')

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">
                      
                <form method="POST" class="form-horizontal" id="form" action="{{route('admin.artists.store')}}" enctype="multipart/form-data">
                	{!!Form::token()!!}

                	<div class="form-group"><label class="col-sm-2 control-label">Channel Name</label>
                    	<div class="col-sm-10"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_name_en"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_name_si"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_name_ta"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">English</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_description_en"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Sinhala</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_description_si"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tamil</label>
                    	<div class="col-sm-5"><input type="text" class="form-control" name="channel_description_ta"></div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Channel Image</label>
                        <div class="col-sm-4">
                            <input id="image" name="channel_image" type="file"  class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Intro Vedio</label>
                        <div class="col-sm-4">
                            <input id="image" name="intro_vedio" type="file"  class="form-control" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required"></label>
                        <div class="col-sm-4 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Kids Channel</label>
                        </div>
                    </div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">Search Tags</label>
                        <div class="col-sm-8">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Search Tags</label>
                        <div class="col-sm-8" style="display: inline">
                            <div class="col-md-5 no-padding">
                                <select class="form-control policy" id="policySelector" name="advertisementPolicy" style="width: 90%;" multiple="multiple">
                                  
                                <option value="d" selected>dd</option>
                                        <option value="d3" selected>dd</option>
                                  
                                </select>
                            </div>

                            <div class="col-md-5">
                                <select class="form-control policy" id="content_policies" name="content_policies[]"
                                        style="width:90%;" multiple >
                                        <option value="d4" selected>dd</option>
                           

                                </select>
                                <label id="content_error" class="text-danger" for="content_policies"></label>

                            </div>

                            <input type="hidden" id="content_count" value="" name="content_count">

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
            multiple: true,
            tokenSeparators: [','],
        }).on('select2:open', function (e) {
            $('.select2-container--open .select2-dropdown--below').css('display', 'none');
        });

        jQuery.validator.addMethod("filesize_max_kb", function(value, element, param) {
                var isOptional = this.optional(element),
                    file;

                if(isOptional) {
                    return isOptional;
                }
                if ($(element).attr("type") === "file") {
                    if (element.files && element.files.length) {

                        file = element.files[0];
                        return ( file.size && (file.size/1000) <= param );
                    }
                }
                return false;
            },
            $.validator.format("File size is larger than  {0}kb")
        );
        $('#image').change(function() {
            $('#image').removeData('imageWidth');
            $('#image').removeData('imageHeight');
            var file = this.files[0];
            var tmpImg = new Image();
            tmpImg.src=window.URL.createObjectURL( file );
            tmpImg.onload = function() {
                const width = tmpImg.naturalWidth,
                    height = tmpImg.naturalHeight;
                $('#image').data('imageWidth', width);
                $('#image').data('imageHeight', height);
            }
        });

        $.validator.addMethod('dimension', function(value, element, param) {
                if (element.files.length === 0) {
                    return true;
                }
                var width = $(element).data('imageWidth');
                var height = $(element).data('imageHeight');
                if (width == param[0] && height == param[1]) {
                    return true;
                } else {
                    return false;
                }
            },
            $.validator.format("Upload image size should be {0}px X {1}px")
        );

        $('select[name="similar_artists[]"]').select2({
            multiple: true,
        });

		$("#form").validate({
            rules: {
                name: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                image: {
                    required: true,
                    accept: "image/*",
                    dimension: [175,175],
                    filesize_max_kb: {{ env('Upload_Image_Size') }}

                },
                tags: {
                    required: true

                }
               
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
	
	
</script>
@stop