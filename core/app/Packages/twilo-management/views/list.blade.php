@extends('layouts.back.master') @section('current_title','Lyricist/VIEW')
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
        <h2>Twillio Channel Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Channel List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
   
        <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{url('twillio/add')}}';">
            <p class="plus">+</p>
        </div>
    
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Friendly Name</th>
                            <th>Unique Name</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')

    <script type="text/javascript">
        let table;
        $(document).ready(function(){
            table=$('#example1').DataTable( {
                "ajax": '{{url('twillio/list/json')}}',
                "columns": [
                    { "data": "id" },
                    { "data": "friendly_name" },
                    { "data": "unique_name" },
                    { "data": "delete" }
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [1, 2] }
                ],
                processing: true,
                serverSide: true,
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
                $('.channel-status-toggle').click(function(e){
                    e.preventDefault();
                    id = $(this).data('id');
                    state = $(this).data('status');
                    changeStatus(id, state);


                });
                $('.channel-delete').click(function(e){
                    e.preventDefault();
                    id = $(this).data('id');
                    deleteChannel(id);


                });

            });



        });

        function deleteChannel(id){
            swal({
                title: "Are you sure?",
                text:"Delete the Channel",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        method: "POST",
                        url: '{{url('twillio/channel/delete')}}',
                        data:{ 'id' : id }
                    }).done(function( msg ) {
                        table.ajax.reload();
                    });
                } else {
                    swal("Cancelled", "Cancelled the Channel Delete", "error");
                }
            });
        }

        function changeStatus(id, state) {
            swal({
                title: "Are you sure?",
                text:"Change the status",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        method: "POST",
                        url: '{{url('channel/changeState')}}',
                        data:{ 'id' : id, 'state' : state  }
                    }).done(function( msg ) {
                        table.ajax.reload();
                    });
                } else {
                    swal("Cancelled", "Cancelled the status change", "error");
                }
            });
        }


    </script>
@stop