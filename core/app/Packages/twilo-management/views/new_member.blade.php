@extends('layouts.back.master') @section('current_title','Artist/ADD')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Twillo Channel Member Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Twillo Channel Member / Add</strong>
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

                	<div class="form-group"><label class="col-sm-2 control-label">Channel</label>
                    	<div class="col-sm-5">
                        <select  name="channel" class="form-control" >
                            @foreach ($channels as $channel)
                                <option value="{{$channel->id}}" selected>{{$channel->friendly_name}}</option>
                                @endforeach
                            </select>
                        </div>
                	</div>
                   
                    <div class="form-group"><label class="col-sm-2 control-label">Members</label>
                    	<div class="col-sm-10">
                            <table style="" id="memeber_table">
                                <tr>
                                    <td style="width:50%">
                                    
                                    <select style="width:100%" class="member" name="member[]" id="member" required class="form-control select-simple" >
                                   </select>
                                    </td>
                                    <td >
                                    <input data-jscolor="{}" name="c_picker[]">
                                    </td>
                                    <td style="width:10%">
                                    <input type="button" value="+" onclick="addNewRow(1)"></input>
                                    </td>
                                </tr>
                            </table>
                        
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
<script src="{{asset('assets/back/colorpicker/jscolor.js')}}"></script>
<script type="text/javascript">
    var count=1;
	$(document).ready(function(){
        // jscolor.presets.default = {
		// palette: [
		// 	'#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26', '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
		// 	'#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d', '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
		// ],
		// //paletteCols: 12,
		// //hideOnPaletteClick: true,
		// //width: 271,
		// //height: 151,
		// //position: 'right',
		// //previewPosition: 'right',
		// //backgroundColor: 'rgba(51,51,51,1)', controlBorderColor: 'rgba(153,153,153,1)', buttonColor: 'rgba(240,240,240,1)',
	    // }
       
        $('select[name="channel"]').select2({
            // multiple: true,
        });
        $("#channel_image").fileinput({
            uploadUrl: "", // server upload action
            dropZoneEnabled: true,
            uploadAsync: false,
            // minFileCount: 3,
            showRemove: true,
            showUpload:false,
            allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"]
            
        });
        
		$("#form").validate({
            rules: {
                friendly_name: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                },
                unique_name: {
                    required:  {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                  
                }
               
               
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
	});

      
    $('.member').select2({


            ajax               : 
            {
                url            : '{{url('twillio/search/viewer')}}',
                type           : 'GET',
                dataType       : 'json',
                delay          : 250,
                processResults : function (data) {
                    return {
                        results: $.map(data, function (item, i) {
                            return {
                                text: item.ViewerID +"- "+ item.Name +'- '+ item.MobileNumber,
                                id: item.ViewerID +"_"+item.Name
                            }
                        })
                    };
                }
            }
        });

        function addNewRow(){
            count=count+1;
            $('#memeber_table').append(
                '<tr id="row_'+count+'"><td>'
                +'<select style="width:70%" class="member" name="member[]" id="member'+count+'" class="form-control select-simple" ></select>'
                +'</td>'
                +'<td>'
                +'<input class="jscolor" name="c_picker[]">'
                +'</td>'
                +'<td>'
                +'<input type="button" onclick="removeRow('+count+')" value="-">'
                +'</td>'
                +'</tr>'
            );
            new jscolor($('.jscolor').last()[0]);
            $('#member'+count).select2({
            ajax               : 
            {
                url            : '{{url('twillio/search/viewer')}}',
                type           : 'GET',
                dataType       : 'json',
                delay          : 250,
                processResults : function (data) {
                    return {
                        results: $.map(data, function (item, i) {
                            return {
                                text: item.ViewerID +"- "+ item.Name +'- '+ item.MobileNumber,
                                id: item.ViewerID +"_"+item.Name
                            }
                        })
                    };
                }
            }
            });

            
        }
        function removeRow(id){
            $('#row_'+id).remove();
        }
    
   
	
	
</script>
@stop