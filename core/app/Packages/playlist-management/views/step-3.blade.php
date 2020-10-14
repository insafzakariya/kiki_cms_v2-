@extends('layouts.back.master') @section('current_title','Playlist/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link href="{{url('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.6/css/rowReorder.dataTables.min.css" />
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Playlist Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/playlist')}}">Playlist</a>
            </li>
            <li class="active">
                <strong>Step 3</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <div class="panel-body">
                    <div class="steps-form" style="margin-bottom: 40px;">
                        <div class="steps-row setup-panel">
                            <div class="steps-step steps-success">
                                <span  class="btn btn-success btn-circle ">1</span>
                                <p>Step 1</p>
                            </div>
                            <div class="steps-step steps-success">
                                <span   class="btn btn-success btn-circle" >2</span>
                                <p>Step 2</p>
                            </div>
                            <div class="steps-step steps-success">
                                <span  class="btn btn-success btn-circle" >3</span>
                                <p>Step 3</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 40px;">
                        <div class="form-check" style="margin-bottom: 20px;">
                            <input type="checkbox" class="form-check-input" id="enableSort">
                            <label class="form-check-label" for="enableSort">Enable Sorting</label>
                        </div>
                        <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>Sequence</th>
                                <th>Song ID</th>
                                <th>Name</th>
                                <th>Artist</th>
                                <th>Genre</th>
                                <th>ISRC Code</th>
                                <th>Category</th>
                                <th>Product Type</th>
                                <th>Writer</th>
                                <th>Music By</th>
                                <th width="1%">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="col-sm-offset-2">
                                    <a href="{{url('admin/playlist/step-2?id='.$playlistId)}}" class="btn btn-lg" type="button" style="color: #000; font-weight: bold;">
                                        <img src="{{url('assets/back/img/add_song.png')}}" alt="" style="height: 60px;">
                                        <br>
                                        Add Songs
                                    </a>
                                </div>
                                <div class="col-sm-offset-10">
                                    <form method="POST">
                                        {!!Form::token()!!}
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{url('assets/back/file/bootstrap-fileinput-master/js/file-input.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.6/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript">
        let table;
        $(document).ready(function(){
            disableSorting();
        });

        function changeSongOrder(newOrder) {
            $.ajax({
                method: "POST",
                url: '{{route('admin.playlist.songs-order')}}',
                data:{
                    'playlist_id' : {{$playlistId}},
                    order: newOrder
                }
            }).done(function( msg ) {
                table.ajax.reload();
            });
        }

        function confirmAction(id, state) {
            $.ajax({
                method: "POST",
                url: '{{url('admin/playlist/song/remove')}}',
                data:{ 'id' : id  }
            }).done(function( msg ) {
                table.ajax.reload();
            });
        }

        $("#enableSort").on("click", function () {
            if($("#enableSort").is(":checked")){
                enableSorting();
            }else{
                disableSorting();
            }
        });

        function enableSorting() {
            if(table){
                table.clear().destroy();
            }
            table=$('#example1').DataTable( {
                "ajax": {
                    url :   '{{url('admin/playlist/songs')}}',
                    data : {
                        'playlist_id' : {{$playlistId}},
                    }
                },
                processing: true,
                serverSide: true,
                searching: false,
                paging:   false,
                ordering: false,
                info:     false,
                rowReorder: {
                    dataSrc: [[0]],
                    update: false
                },
                columns:[
                    {name :  'order', data : 'order'},
                    {name :  'id', data :  'id'},
                    {name : 'name', data : 'name'} ,
                    {name : 'song.primaryArtists.name', data : 'artist_name'} ,
                    {name : 'song', data : 'genre_name'} ,
                    {name : 'isrc', data : 'isrc'},
                    {name : 'category_name', data : 'category_name'},
                    {name : 'song.category.name', data : 'product_type'},
                    {name : 'song.writer.name', data : 'writer_name'} ,
                    {name : 'song.music.name', data : 'music_by'} ,
                    {name : 'action', data: 'action'}
                ],
            });
            table.on('row-reorder.dt', function (dragEvent, data, nodes) {
                let i = 0, ien = data.length;
                if(ien > 0){
                    let newOrder = [];
                    for (i ; i<ien ; i++ ) {
                        const rowData = table.row(data[i].node).data();
                        newOrder.push({
                            song_id : table.row(data[i].node).data().id,
                            order: (data[i].newPosition + 1)
                        });
                    }
                    changeSongOrder(newOrder)
                }

            });
        }

        function disableSorting() {
            if(table){
                table.clear().destroy();
            }
            table=$('#example1').DataTable( {
                "ajax": {
                    url :   '{{url('admin/playlist/songs')}}',
                    data : {
                        'playlist_id' : {{$playlistId}},
                    }
                },
                processing: true,
                serverSide: true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "columnDefs": [
                    { "type": "num", "targets": 0 },
                    { "orderable": false, "targets": [-1, 3, 4, -4] },
                ],
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'Menu List', className: 'btn-sm'},
                    {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'}
                ],
                "autoWidth": false,
                columns:[
                    {name :  'song_order', data : 'order'},
                    {name :  'song_id', data :  'id'},
                    {name : 'song.name', data : 'name'} ,
                    {name : 'song.primaryArtists.name', data : 'artist_name'} ,
                    {name : 'song.genres.name', data : 'genre_name'} ,
                    {name : 'song.isbc_code', data : 'isrc'},
                    {name : 'song.category.name', data : 'category_name'},
                    {name : 'song.products.type', data : 'product_type'},
                    {name : 'song.writer.name', data : 'writer_name'} ,
                    {name : 'song.music.name', data : 'music_by'} ,
                    {name : 'action', data: 'action'}
                ],
            });
        }

    </script>
@stop
