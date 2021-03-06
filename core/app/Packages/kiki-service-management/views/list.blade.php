@extends('layouts.back.master') @section('current_title','Programme Slider/view')
@section('css')
    <style type="text/css">
        #floating-button{
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #db4437;
            position: fixed;
            bottom: 50px;
            right: 30px;
            cursor: pointer;
            box-shadow: 0px 2px 5px #666;
            z-index:2
        }
        .btn.btn-secondary{
            margin: 0 2px 0 2px;
        }
        .plus{
            color: white;
            position: absolute;
            top: 0;
            display: block;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 0;
            margin: 0;
            line-height: 55px;
            font-size: 38px;
            font-family: 'Roboto';
            font-weight: 300;
            animation: plus-out 0.3s;
            transition: all 0.3s;
        }
        .btn.btn-primary.btn-sm.ad-view{
            font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-weight: 600;
            text-shadow: none;
            font-size: 13px;
        }

        .row-highlight-clr{
            /*background-color: rgba(244, 67, 54, 0.1)  !important;*/
            background-color: rgba(0, 0, 0, 0.5) !important;
            color: #fff !important;
        }
    </style>

@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Kiki Service Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Kiki Service List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
  
    @if (session('programme-error-details'))
    <div class="alert alert-danger">
        {{ session('programme-error-details') }}
    </div>
    @endif
    @if (session('programme-details'))
    <div class="alert alert-success">
        {{ session('programme-details') }}
    </div>
    @endif
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
            <div class="col-sm-10 col-sm-offset-2" style="padding-bottom:10px"> 
                  <button class="btn btn-primary pull-right" onclick="reorderSave()"  type="button" id="btn-bulk-policy-update">Save Reorder List</button>
            </div>
                <div class="panel-body">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Move</th>
                      <th>Service ID</th>
                      <th>Name</th>
                      <th>Parent</th>
                      <th>Edit</th>
                      <th>Active</th>
                      <th>Delete</th>
                      
                    </tr>
                  </thead>
                  <tbody id="tablecontents">
                    @foreach($services as $service)
                    <tr class="row1" data-id="{{ $service->id }}" id="row_{{ $service->id }}">
                      <td>
                        <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                        </div>
                      </td>
                      <td>{{ $service->id }}</td>
                      <td>{{ $service->name }}</td>
                      @if(isset($service->getService->name))
                      <td>{{ $service->getService->name}}</td>
                      @else
                      <td>-</td>
                      @endif
                     
                    
                      <td align="center"><a href="{{url('service/'.$service->id.'/edit')}}" class="blue" onclick="window.location.href=\''.url('service/'.$service->id.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Service"><i class="fa fa-pencil"></i></a></td>
                      <td align="center">
                        @if($service->status==1)
                        <input type="checkbox" checked="true" name="checkfield{{ $service->id }}" id="{{ $service->id }}"  onchange="statusChange(this)"/>
                        @elseif($service->status==2)
                        <input type="checkbox"  name="checkfield{{ $service->id }}" id="{{ $service->id }}"  onchange="statusChange(this)"/>
                        @endif
                       
                      </td>
                      <td>
                      <center><a href="#" class="blue" onclick="delete_service(<?php echo $service->id; ?>)" data-toggle="tooltip" data-placement="top" title="View/ Edit Service"><i class="fa fa-trash"></i></a></center>
                      </td>
                    
                     
                    </tr>
                    @endforeach
                  </tbody>                  
                </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')

    <script type="text/javascript">
      

      function statusChange(checkboxElem) {
        if (checkboxElem.checked) {
            changeStatus(checkboxElem.id, 1);
          
        } else {
            changeStatus(checkboxElem.id, 2);
        }
      }
    function changeStatus(id, state) {
        $.ajax({
            method: "POST",
            url: '{{url('service/changeState')}}',
            data:{ 'id' : id, 'state' : state  }
        }).done(function( msg ) {
            console.log("CHANGED");
            
        });
     }
     function delete_service(id) {
            swal({
                title: "Are you sure?",
                text:"Delete Service",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
            
                if (isConfirm.value) {
                    $.ajax({
                        method: "POST",
                        url: '{{url('service/deleteservice')}}',
                        data:{ 'id' : id  }
                    }).done(function( msg ) {
                        console.log("CHANGED");
                        $('#row_'+id).remove();
                      
                    });
                } else {
                    swal("Cancelled", "Cancelled the status change", "error");
                }
            });
        }

        function reorderSave() {
        swal({
                title: "Are you sure?",
                text:"Save Order List",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
             
                if (isConfirm.value) {
                  sendOrderToServer();
                } else {
                    swal("Cancelled", "Cancelled the save change", "error");
                }
            });
       }
    
       //Save Order Table
       function sendOrderToServer() {

          var order = [];
          $('tr.row1').each(function(index,element) {
            order.push({
              id: $(this).attr('data-id'),
              position: index+1
            });
          });

          $.ajax({
            type: "POST", 
            dataType: "json", 
            url: "{{ url('service/sortabledatatable') }}",
            data: {
              order:order,
              _token: '{{csrf_token()}}'
            },
            success: function(response) {
                if (response.status == "success") {
                  console.log(response);
                } else {
                  console.log(response);
                }
            }
          });

      }

    $(function () {
  
      var table=$("#table").DataTable({
          "bPaginate": false
          });

      $( "#tablecontents" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        update: function() {
            // sendOrderToServer();
        }
      });
    });

  

 

</script>
@stop