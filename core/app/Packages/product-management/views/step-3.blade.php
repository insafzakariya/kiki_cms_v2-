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
        <h2>Product Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/products')}}">PRODUCT</a>
            </li>
            <li class="active">
                <strong>ADD</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <div class="panel-body">

                    <div class="steps-form">
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

                    <div class="table-responsive" style="margin-top: 50px">
                        <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>Song ID</th>
                                <th>Name</th>
                                <th>Artist</th>
                                <th>Genre</th>
                                <th>Category</th>
                                <th>ISRC Code</th>
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
                                <div class="col-lg-8">
                                    <div class="col-sm-offset-2">
                                        <a href="{{url('admin/song/step-1').'?product_id='.$product_id.'&type='.$type}}" class="btn btn-sm" type="button" style="color: #000; font-weight: bold;">
                                            <img src="{{url('assets/back/img/add_song.png')}}" alt="" width="100" height="100">
                                            <br>
                                            Add a new song
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="col-sm-offset-8">
                                        <a href="{{url('admin/products/'.$product_id.'/add/songs').'?type='.$type}}" class="btn btn-sm" type="button" style="color: #000; font-weight: bold;">
                                            <img src="{{url('assets/back/img/exisitng.png')}}" alt="" width="100" height="100">
                                            <br>
                                            Add from existing
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-offset-10">
                        <form method="POST" action="{{url('admin/products/add/step-3')}}">
                            {!!Form::token()!!}
                            <input type="hidden" id="productId" value="{{$product_id}}" name="id">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
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

            let url = '{{ url('admin/products/{id}/get/songs') }}';
            url = url.replace('{id}', $('#productId').val());

            table=$('#example1').DataTable( {
                "ajax": url,
                processing: true,
                serverSide: true,
                rowReorder: {
                    dataSrc: [[0]],
                    update: false
                },
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "columnDefs": [
                    { "type": "num", "targets": 0 }
                ],
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'Menu List', className: 'btn-sm'},
                    {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'}
                ],
                "autoWidth": false,
                columns:[
                    {name :  'id', data :  'id'},
                {name : 'name', data : 'name'} ,
                {name : 'artist_name', data : 'artist_name'} ,
                {name : 'genre_name', data : 'genre_name'} ,
                {name : 'category_name', data : 'category_name'},
                {name : 'isrc_code', data : 'isrc_code'},
                {name : 'writer_name', data : 'writer_name'} ,
                {name : 'music_by', data : 'music_by'} ,
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



        });

        function changeSongOrder(newOrder) {
            $.ajax({
                method: "POST",
                url: '{{url('admin/products/songs/order')}}',
                data:{
                    'product_id' : $('#productId').val(),
                    order: newOrder
                }
            }).done(function( msg ) {
                table.ajax.reload();
            });
        }

        function confirmAction(id, state) {
            $.ajax({
                method: "POST",
                url: '{{url('admin/product/song/remove')}}',
                data:{ 'id' : id  }
            }).done(function( msg ) {
                table.ajax.reload();
            });
        }


    </script>
@stop