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
        <h2>Programme Slider Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Programme Slider List</strong>
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
                <div class="panel-body">
                <table id="table" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Move</th>
                      <th>Slider ID</th>
                      <th>Name</th>
                      <th>Programme</th>
                      <th>Start</th>
                      <th>End</th>
                      <th>Edit</th>
                      <th>Active</th>
                      
                    </tr>
                  </thead>
                  <tbody id="tablecontents">
                    @foreach($sliders as $slider)
                    <tr class="row1" data-id="{{ $slider->ID }}">
                      <td>
                        <div style="color:rgb(124,77,255); padding-left: 10px; float: left; font-size: 20px; cursor: pointer;" title="change display order">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                        </div>
                      </td>
                      <td>{{ $slider->ID }}</td>
                      <td>{{ $slider->name }}</td>
                      @if(isset($slider->getProgramme->programName))
                      <td>{{ $slider->getProgramme->programName}}</td>
                      @else
                      <td>-</td>
                      @endif
                     
                      <td>{{ $slider->start_date_time }}</td>
                      <td>{{ $slider->end_date_time }}</td>
                      <td align="center"><a href="{{url('programme-slider/'.$slider->ID.'/edit')}}" class="blue" onclick="window.location.href=\''.url('programme-slider/'.$slider->ID.'/edit').'\'" data-toggle="tooltip" data-placement="top" title="View/ Edit Slider"><i class="fa fa-pencil"></i></a></td>
                      <td align="center">
                      @if($slider->status==1)
                      <a href="javascript:void(0)"  class="blue programme-status-toggle " data-id="'.$value->programId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a>
                      @elseif($slider->status==2)
                      <a href="javascript:void(0)" form="noForm" class="blue programme-status-toggle " data-id="'.$value->programId.'" data-status="0"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-off"></i></a>
                      @endif
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
        

    </script>
    <script type="text/javascript">
  $(function () {
    $("#table").DataTable({
        "bPaginate": false
        });

    $( "#tablecontents" ).sortable({
      items: "tr",
      cursor: 'move',
      opacity: 0.6,
      update: function() {
          sendOrderToServer();
      }
    });

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
        url: "{{ url('programme-slider/sortabledatatable') }}",
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
  });

</script>
@stop