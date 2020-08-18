@extends('layouts.back.master') @section('current_title','User-Group/View')
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

        #searching-gif{
            height: 30px;
            margin-left: 15px;
        }
    </style>

@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Notification Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Notification List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
{{--    @if(\Sentinel::getUser()->hasAnyAccess(['admin.projects.show', 'admin']))--}}
        <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{url('admin/notification/notification-add')}}';">
            <p class="plus">+</p>
        </div>
{{--    @endif--}}
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content" style="width: 100%; display: inline-block; padding-bottom: 0;">
                <div class="form-group col-sm-4 col-lg-4 row">
                    <label for="field_name" class="control-label col-sm-4 col-lg-4 text-right" style="line-height: 3rem;">Search By</label>
                    <div class="col-sm-8 col-lg-8">
                        <select id="field_name" name="field_name"
                                class="form-control select-simple" required>
                            <option value="section">Section</option>
                            <option value="content_type">Content Type</option>
                            <option value="content_id">Content</option>
                            <option value="language">Language</option>
                            <option value="title">Title</option>
                        </select>
                    </div>
                </div>

                <!-- <div class="form-group col-sm-5 col-lg-5"> -->
                    <label class="col-sm-1 col-lg-1 control-label" style="line-height: 3rem;">Keyword</label>
                    <div class="col-sm-3 col-lg-3">
                        <input type="text" name="search_param" id="search_param" class="form-control">
                    </div>
                <!-- </div> -->
                <div class="form-group col-sm-2 col-lg-2">
                    <button class="btn btn-primary" id="search_table">Search</button>
                    <img id="searching-gif" style="" src="{{url('assets/back/img/loading3.jpg')}}">
                </div>
            </div>
            <div class="ibox-content">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Group</th>
                                <th>Section</th>
                                <th>Content Type</th>
                                <th>Content</th>
                                <th>Notification time</th>
                                <th>Language</th>
                                <th>Title</th>
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
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/js/plugins/switchery/switchery.js')}}"></script>
    <script type="text/javascript">
        let table;
        $(document).ready(function(){

            table=$('#example1').DataTable( {
                "ajax":{
                   url: '{{url('admin/notification/json/list')}}',
                    data: function (d) {
                        d.field_name = $('select[name=field_name]').val();
                        d.search_param = $('input[name=search_param]').val();
                    }
                } ,
                processing: true,
                serverSide: true,
                "bFilter": false,
                //"pageLength": 50,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [25, 50, 100, -1], [ 25, 50, 100,"All"] ],
                "order": [[ 0, "desc" ]],
                "columnDefs": [
                    // { "searchable": false, "targets": [-1, -2, -5] },
                    // { "orderable": false, "targets": [-1, -2, -5] }
                    {
                        "targets": 10,
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
                columns:[
                    {name :  'id', data :  'id'},
                    {name : 'user_group', data : 'user_group'} ,
                    {name : 'section', data : 'section'} ,
                    {name : 'content_type', data : 'content_type'} ,
                    {name : 'content_id', data : 'content_id'} ,
                    {name : 'notification_time', data : 'notification_time'} ,
                    {name : 'language', data : 'language'} ,
                    {name : 'title', data : 'title'},
                    {name : 'status', data : 'status'},
                    {name : 'action', data: 'action', 'orderable' : false, 'searchable' : false},
                    {name : 'edit', data: 'edit', 'orderable' : false, 'searchable' : false}
                ],
                
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'Menu List', className: 'btn-sm'},
                    {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'}
                ],
                "autoWidth": false,
                fnDrawCallback:function (oSettings) {
                    console.log("after table create");
                    // console.log("worked");
                    $('#search_table').removeAttr("disabled");
                    $("#searching-gif").hide();
                }
            });

            table.on( 'draw.dt', function () {
                $('.song-status-toggle').click(function(e){
                    e.preventDefault();
                    id = $(this).data('id');
                    state = $(this).data('status');
                    changeStatus(id, state);

                });

            });

            $("#searching-gif").hide();


            $('#search_table').on('click', function(e) {
                
                //table.draw();
                e.preventDefault();
                var txt = $('#search_param').val().trim();

                if (txt !== "" && txt.length >= 0) {
                    console.log("true");
                    $("#search_table").attr("disabled", true);
                    // $("#searching-gif").show();
                    $("#searching-gif").css("display", "inline-block");
                    e.preventDefault();
                    $('#example1').DataTable().draw(true);
                    // $('#search_table').removeAttr("disabled");
                    // $("#searching-gif").hide();
                } else {
                    $('#search_table').removeAttr("disabled");
                    $("#searching-gif").hide();
                    toastr.error("Please enter a keyword with minimum 3 letters");
                }
            });



        });

        function changeStatus(id, state) {
            swal({
                title: "Are you sure? ",
                text:"Change the status",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
                if (isConfirm) {

                   $.ajax({
                        method: "POST",
                        url: '{{url('admin/notification/changeState')}}',
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