@extends('layouts.back.master') @section('current_title','New Product Creation')
@section('css')


@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Product Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li>
            <a href="{{url('/product/')}}">Product</a>
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
                         
                <form method="POST" class="form-horizontal" id="form" method="post" enctype="multipart/form-data"> 
                	{!!Form::token()!!}
                    
                	<div class="form-group"><label class="col-sm-2 control-label">PRODUCT NAME</label>
                        <div class="col-sm-10"><input type="text" class="form-control" name="name"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">DESCRIPTION</label>
                         <div class="col-sm-10"><textarea name="description" class="form-control"></textarea></div>
                        
                    </div>
                     <div class="form-group"><label class="col-sm-2 control-label">CATEGORY</label>
                         <div class="col-sm-10"><select class="js-source-states form-control" name="category" style="width: 100%">                            
                            <?php foreach ($main_category as $key => $value): ?>
                                <optgroup label="{{$value['main_category']['name']}}">
                                    <?php foreach ($value['sub_categories'] as $key => $sub_value): ?>
                                         <option value="{{$sub_value['id']}}">{{$sub_value['name']}}</option>
                                    <?php endforeach ?>
                                   
                                   
                                </optgroup>
                            <?php endforeach ?>                         

                    </select>
                    </div>
                        
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">PRICE</label>
                        <div class="col-sm-5"><input type="text" class="form-control" name="price"></div>
                    </div>                   
                    
                	
                    <div class="form-group">
                        <label class="col-sm-2 control-label required">IMAGES</label>
                        <div class="col-sm-10">
                            <input id="product_image" name="product_image[]" type="file" multiple class="file-loading">
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




<script type="text/javascript">
	$(document).ready(function(){
        $(".js-source-states").select2();
        $("#form").validate({
            rules: {
                name: {
                    required: true
                  
                },
                description:{
                    required: true
                },
                price:{
                    required: true,
                    number:true
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        $("#product_image").fileinput({
            uploadUrl: "", // server upload action
            uploadAsync: true,
            maxFileCount: 5,
            showUpload:false,
            allowedFileExtensions: ["jpg", "gif", "png"]
        });

		
	});

     
	
</script>
@stop
