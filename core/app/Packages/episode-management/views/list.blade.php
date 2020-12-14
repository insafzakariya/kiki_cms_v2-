@extends('layouts.back.master') @section('current_title','Episode / view')
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
        <h2>Episode Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Episode List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
    <!-- @if(\Sentinel::getUser()->hasAnyAccess(['admin.lyricists.show', 'admin']))
        <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{route('admin.lyricists.create')}}';">
            <p class="plus">+</p>
        </div>
    @endif -->
    @if (session('episode-details'))
    <div class="alert alert-success">
        {{ session('episode-details') }}
    </div>
    @endif
    
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
              
                    <div class="col-sm-10 col-sm-offset-2" style="padding-bottom:10px"> 
                       
                        <button class="btn btn-primary pull-right" type="button" id="btn-bulk-policy-update">Bulk Policy Update</button>
                    </div>
                    
                

                <div class="panel-body">
                
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                        
                            <th style="align:center"><center><input type="checkbox" id="select-all" class="form-check-input select-all"><center></th>
                            <th>ID</th>
                            <th>Episode Name en</th>
                            <th>Programme</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th width="1%">Delete</th>
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
        // var selected_ids=[];
        let table;
        $(document).ready(function(){
            table=$('#example1').DataTable( {
                "ajax": '{{url('episode/list/json')}}',
                "columns": [
                    { "data": "checklist" ,'orderable' : false, 'searchable' : false},
                    { "data": "episodeId" },
                    { "data": "episodeName" },
                    { name : 'getProgramme.programName',"data": "programme" },
                    { "data": "start_date" },
                    { "data": "end_date" },
                    { "data": "status" },
                    { "data": "edit" ,searchable : false}
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [3, 4] }
                ],
                processing: true,
                serverSide: true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                buttons: [
                    {extend: 'copy',className: 'btn-sm'},
                    {extend: 'csv',title: 'Menu List', className: 'btn-sm'},
                    {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
                    {extend: 'print',className: 'btn-sm'},
                    {text: 'Reload',
                        action: function ( e, dt, node, config ) {
                            dt.ajax.reload();
                        }
                    }
                ],
                "autoWidth": false,
                "order": [[ 1, "desc" ]]
            });

            table.on( 'draw.dt', function () {
                $('.episode-status-toggle').click(function(e){
                    e.preventDefault();
                    id = $(this).data('id');
                    state = $(this).data('status');
                    changeStatus(id, state);


                });

            });



        });

        //Select All Checkbox
        $('.select-all').click(function(event) {   
            if(this.checked) {
                // Iterate each checkbox
                $('.episode-check').each(function() {
                    this.checked = true;   
                    // selected_ids.push(this.value);
                                       
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;   
                    // selected_ids.pop(this.value);                    
                });
            }

            
        });
        
        

        
        //Bulk Policy Update button function

        $('#btn-bulk-policy-update').click(function(event){
            var selected_ids=[];
            $('.episode-check').each(function() {
                if(this.checked){
                    selected_ids.push(this.value);
                }
            
                                    
            });
            if(selected_ids.length >0){
                swal({
                    title: "Are you sure?",
                    text:"Bulk Policy Update",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, change it!"

                }).then(function (isConfirm) {
                    if(isConfirm.value){
                        var episode_list_arry = '/episode/policyupdate/'+encodeURIComponent(JSON.stringify(selected_ids));
                    window.location.href = '{{url('/')}}'+episode_list_arry;
                
                    console.log(selected_ids);
                    }
                    
                });
            }else{
                swal("No Episode Selected", "Please select atleast one episode", "error");
            }
           
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
                        url: '{{url('episode/delete')}}',
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