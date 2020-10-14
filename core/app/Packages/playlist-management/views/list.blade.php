@extends('layouts.back.master') @section('current_title','Playlist/View')
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
        <h2>Playlist Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Playlist List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
{{--    @if(\Sentinel::getUser()->hasAnyAccess(['admin.projects.show', 'admin']))--}}
        <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{url('admin/playlist/step-1')}}';">
            <p class="plus">+</p>
        </div>
{{--    @endif--}}
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Playlist Name</th>
                            <th>No of Songs</th>
                            <th>Playlist Type</th>
                            <th>Publish Date</th>
                            <th>Release Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th width="1%">Active/ Deactivate</th>
                            <th width="1%">Edit</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/js/plugins/switchery/switchery.js')}}"></script>
    <script type="text/javascript">
        let table;
        $(document).ready(function(){

            table=$('#example1').DataTable( {
                "ajax": '{{url('admin/playlist/json/list')}}',
                processing: true,
                serverSide: true,
                "columns": [
                    { "data": "id"},
                    { "data": "name"},
                    { "data": "songs_count"},
                    { "data": "type_name", defaultContent : '-' },
                    { "data": "publish_date" },
                    { "data": "release_date" },
                    { "data": "expiry_date" },
                    { "data": "status" },
                    { "data": "toggle-status" },
                    { "data": "edit" }
                ],
                "columnDefs": [
                    { "searchable": false, "targets": [-1, -2, 2] },
                    { "orderable": false, "targets": [-1, -2, 2] },
                    {
                        "targets": 6,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var expireDate = new Date(cellData);
                            var today = new Date();
                            today.setHours(0,0,0,0);

                            if(today >= expireDate) {
                                $(td).css('background-color', 'rgb(217, 83, 79, 0.5)');
                            }
                        }
                    }
                ],
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'Menu List', className: 'btn-sm'},
                    {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'}
                ],
                "autoWidth": false,
                "order": [[ 0, "desc" ]]
            });

            table.on( 'draw.dt', function () {
                $('.playlist-status-toggle').click(function(e){
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
                text:"Change the status",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        method: "POST",
                        url: '{{url('admin/playlist/changeState')}}',
                        data:{ 'id' : id, 'state' : state  }
                    }).done(function( msg ) {
                        console.log("CHANGED");
                        table.ajax.reload();
                    });
                } else {
                    swal("Cancelled", "Cancelled the status change", "error");
                }
            });
        }


    </script>
@stop