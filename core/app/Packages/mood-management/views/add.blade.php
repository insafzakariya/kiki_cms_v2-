@extends('layouts.back.master') @section('current_title','Mood/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Mood Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li>
            <a href="{{url('/admin/moods')}}">Mood</a>
        </li>
        <li class="active">
            <strong>New</strong>
        </li>
    </ol>
</div>
@stop
@section('content')

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">

                <form method="POST" class="form-horizontal" id="form" action="{{route('admin.moods.store')}}" enctype="multipart/form-data" data-parsley-validate>
                	{!!Form::token()!!}

                	<div class="form-group"><label class="col-sm-2 control-label">Name</label>
                    	<div class="col-sm-10"><input type="text" class="form-control" name="name"></div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                    	<div class="col-sm-10">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                	</div>
                    <div class="form-group"><label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10"><select class="form-control tags"  name="tags[]" multiple></select></div>
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

        $(".tags").select2({
            tags: true,
            multiple: true,
            tokenSeparators: [','],
            dropdownCssClass: 'select2-hidden'
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
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
	});


</script>
@stop
