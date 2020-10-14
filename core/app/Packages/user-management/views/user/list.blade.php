@extends('layouts.back.master') @section('current_title','Song/View')
@section('css')
  <link rel="stylesheet" href="{{asset('assets/back/css/plugins/switchery/switchery.css')}}">
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
    <h2>User</h2>
    <ol class="breadcrumb">
      <li>
        <a href="{{url('/')}}">Home</a>
      </li>
      <li class="active">
        <strong>User List</strong>
      </li>
    </ol>
  </div>
@stop
@section('content')
  {{--    @if(\Sentinel::getUser()->hasAnyAccess(['admin.projects.show', 'admin']))--}}
  <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{url('user/add')}}';">
    <p class="plus">+</p>
  </div>
  {{--    @endif--}}
  <div class="row">
    <div class="col-lg-12 margins">
      <div class="ibox-content">
        <div class="panel-body">
          <div class="table-responsive">
            <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
              <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Activate/ Inactivate</th>
                <th>Edit</th>
              </tr>
              </thead>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
@stop
@section('js')
  <script src="{{asset('assets/back/js/plugins/switchery/switchery.js')}}"></script>
  <script type="text/javascript">
    let table;
    $(document).ready(function () {
      table = $('#example1').DataTable({
        "ajax": '{{url('user/json/list/'.$type)}}',
        processing: true,
        serverSide: true,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        /* "columnDefs": [
             { "searchable": false, "targets": [-1, -2, -5] },
             { "orderable": false, "targets": [-1, -2, -5] }
         ],*/
        columns: [
          {name: 'id', data: 'id'},
          {name: 'name', data: 'name'},
          {name: 'email', data: 'email'},
          {name: 'designation', data: 'designation', "searchable": false, "orderable": false},
          {name: 'status', data: 'status'},
          {name : 'status_edit', data: 'status_edit', 'orderable' : false, 'searchable' : false},
          {name: 'edit', data: 'edit', "searchable": false, "orderable": false},
        ],

        buttons: [
          {extend: 'copy', className: 'btn-sm'},
          {extend: 'csv', title: 'Menu List', className: 'btn-sm'},
          {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
          {extend: 'print', className: 'btn-sm'}
        ],
        "autoWidth": false,
        "order": [[ 0, "desc" ]]
      });

      table.on('draw.dt', function () {
        $('.song-status-toggle').click(function (e) {
          e.preventDefault();
          id = $(this).data('id');
          state = $(this).data('status');
          changeStatus(id, state);

        });

      });


    });

    function changeStatus(id, state) {
      swal({
        title: "Are you sure?",
        text: "Change the status",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, change it!"

      }).then(function (isConfirm) {
        if (isConfirm) {
          $.ajax({
            method: "POST",
            url: '{{url('user/status')}}',
            data: {'id': id, 'status': state}
          }).done(function (msg) {
           // console.log("CHANGED");
            table.ajax.reload();
          });
        } else {
          swal("Cancelled", "Cancelled the status change", "error");
        }
      });
    }


  </script>
@stop