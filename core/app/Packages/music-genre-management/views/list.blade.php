@extends('layouts.back.master') @section('current_title','Genre/View')
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
        <h2>Music Genre Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active ">
                <strong >Genre List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
    @if(\Sentinel::getUser()->hasAnyAccess(['admin.music-genres.show', 'admin']))
        <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{route('admin.music-genres.create')}}';">
            <p class="plus">+</p>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Colour</th>
                            <th>SongCount</th>
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

    <script type="text/javascript">
        let table;
        $(document).ready(function(){
            table=$('#example1').dataTable( {
                "ajax": '{{route('admin.music-genres.index.list')}}',
                "columns": [
                    { "data": "GenreID"},
                    { "data": "Name"},
                    { "data": "Description"},
                    { "data": "color" },
                    { "data": "song_count" },
                    { "data": "status" },
                    { "data": "toggle_status" },
                    { "data": "edit" }
                ],
                "columnDefs": [
                    { "searchable": false, "targets": [-1, -2, -4, -5, -6] },
                    { "orderable": false, "targets": [-1, -2] }
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
                $('.genre-status-toggle').click(function(e){
                    e.preventDefault();
                    id = $(this).data('id');
                    confirmStatus(id);

                });

            });



        });

        function confirmStatusAction(id){
            let url = '{!! route('admin.music-genres.status.toggle')!!}';
            $.ajax({
                method: "POST",
                url: url.replace('%7Bmusic_genres%7D', id),
            })
                .done(function( msg ) {
                    table.fnReloadAjax();
                });

        }


    </script>
@stop
