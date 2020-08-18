@extends('layouts.back.master') @section('current_title','Radio Channel/ADD')
@section('css')
    <link href="{{asset('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
          type="text/css"/>
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Song Category Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li>
            <a href="{{url('/admin/songs-category')}}">Song Category</a>
        </li>
        <li class="active">
            <strong>Edit</strong>
        </li>
    </ol>
</div>
@stop
@section('content')

<div class="row">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="col-lg-12 margins">
        <div class="ibox-content">
            <form method="POST" class="form-horizontal" id="form" action="{{route('songs-category.update', $catDetails->categoryId)}}" enctype="multipart/form-data" data-parsley-validate>
                {!!Form::token()!!}
                <input type="hidden" name="image_removed" id="image_removed" value="0">
                <div class="form-group"><label class="col-sm-2 control-label">Category Name <span class="text-danger">*</span> </label>
                    <div class="col-sm-10"><input type="text" class="form-control" name="cat_name" value="{{ $catDetails->name }}"></div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Parent Category <span class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <select class="form-control selectpicker" name="parent_cat" data-live-search="true">
                            <option>- Select Parent Category -</option>
                            <option value="main" <?php if ($catDetails->parent_cat === 0) { echo 'selected'; } ?>>Main</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->categoryId }}" <?php if ($catDetails->parent_cat === $category->categoryId) { echo 'selected'; } ?>>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="description">{{ $catDetails->description }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label required">Category Image</label>
                    <div class="col-sm-4">
                        <input id="image" name="image" type="file"  class="form-control after-error-placement" accept="image/*"  data-parsley-trigger="change"  data-parsley-max-file-size="{{ env('Upload_Image_Size') }}"  data-parsley-errors-container=".nameError">
                        <p class="nameError float-left"></p>
                        <p class="text-danger pull-left" style="font-weight: 600; font-size: 13px;">image size should be 175px *175px</p>
                    </div>
                    {{--<div class="col-sm-6">
                        <img src="{{ Config('constants.bucket.url').$catDetails->image }}" alt=""  style="max-width: 90%;">
                    </div>--}}
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10">
                        <select class="select-simple-tag form-control" name="cat_tags[]" multiple="multiple">
                            @if(isset($catDetails->search_tag) AND is_array($catDetails->search_tag))
                                <?php foreach ($catDetails->search_tag as $key => $value):
                                    echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
                                endforeach ?>
                            @endif
                        </select>
                       {{-- <input type="text" class="form-control" name="cat_tags" value="{{ $catDetails->search_tag }}">--}}
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                        <a class="btn btn-default" href="{{route('songs-category.index')}}">Cancel</a>
                        <button class="btn btn-primary" type="submit">Done</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('js')
    <!-- file input -->
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/sortable.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/purify.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/theme.min.js')}}"></script>
<script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
        $('.selectpicker').select2();
        $('.select-simple-tag').select2({
            tags: true,
            multiple: true,
            tokenSeparators: [','],
        }).on('select2:open', function (e) {
            $('.select2-container--open .select2-dropdown--below').css('display', 'none');
        });

        $("#image").fileinput({
            theme: "fa",
            maxFileCount: 1,
            showUpload: false,
            showRemove: true,
            allowedFileTypes: ['image'],
            allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
            overwriteInitial: true,
            initialPreview: <?php echo json_encode($image); ?>,
            multiple: false,
            maxFileSize: {{ env('Upload_Image_Size') }},
            initialPreviewConfig: <?php echo json_encode($image_config) ?>,
            deleteExtraData: {
                '_token': '{{csrf_token()}}',
            }
        }).on('filecleared', function() {
            $("#image_removed").val(1);
        });

		$("#form").validate({
            rules: {
                cat_name: {
                    required: true

                },
                parent_cat: {
                    required: true

                }
            },
            errorPlacement: function (error, element) {
                if (element.hasClass('select-simple')) {
                    element.next().after(error);
                }else if(element.hasClass('next-error-placement')){
                    element.parent().after(error);
                }else if(element.hasClass('after-error-placement')){
                    element.parent().parent().parent().after(error);
                }  else {
                    element.after(error);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
	});


</script>
@stop
