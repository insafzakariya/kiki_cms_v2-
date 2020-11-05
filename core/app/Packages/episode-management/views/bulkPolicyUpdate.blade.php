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
            <strong>Episode/ Bulk Policy Update</strong>
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

                
                    <div class="form-group">
                    <input type="hidden" value="{{$selected_episode}}"></input>
                    <div class="form-group"><label class="col-sm-2 control-label">Episodes </label>
                    	<div class="col-sm-8">
                        @foreach ($episode_list as $value)
                        <a class="badge badge-primary">{{$value->episodeName."(".$value->episodeId.")"}}</a>
                        @endforeach
                       
                        </div>
                	</div>
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

    $("#form").validate({
            rules: {
              
                'content_policies[]': {
                    required: true

                }
                
               
            },
            submitHandler: function(form) {

                form.submit();
            }
        });

    
	
	
</script>
@stop