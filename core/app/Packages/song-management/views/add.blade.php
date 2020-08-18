@extends('layouts.back.master') @section('current_title','Artist/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Artist Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Artist/ADD</strong>
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

                	<div class="form-group"><label class="col-sm-2 control-label">Artist/Act/Duo/Band</label>
                    	<div class="col-sm-10"><input type="text" class="form-control" name="name"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                    	<div class="col-sm-10">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                	</div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Artist Image *</label>
                        <div class="col-sm-10">
                            <input id="image" name="image" type="file"  class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10"><input type="text" class="form-control"  name="tags"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Similar Artists</label>
                        <div class="col-sm-10"><select  name="similar_artists[]" class="form-control" multiple="multiple">
                                @foreach($artists as $artist)
                                    <option value="{{$artist->artistId}}">{{$artist->name}}</option>
                                @endforeach
                            </select></div>
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
	
	
</script>
@stop