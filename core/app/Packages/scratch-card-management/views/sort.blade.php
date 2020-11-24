@extends('layouts.back.master') @section('current_title','Programme/sort')
@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Channel Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Sort List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12  ">
            <div class="ibox-content ">
            <div class="form-group row">    
                <label class="col-sm-2 control-label">Channel</label>
                <div class="col-sm-5">
                    <select  name="channel" class="form-control" >
                    <option value=""></option>
                        @foreach ($channels as $channel)
                        <option value="{{$channel->channelId}}">{{$channel->channelName}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 margins">
            <div class="ibox-content">
            <div class="form-group row">    
                <label class="col-sm-6 control-label">Programme List</label>

            </div>
            <div id="example2Left" class="list-group col">
				<!-- <div class="list-group-item">Item 1</div>
				<div class="list-group-item"  data-id="2123456">Item 2</div>
				<div class="list-group-item">Item 3</div>
				<div class="list-group-item">Item 4</div>
				<div class="list-group-item">Item 5</div>
				<div class="list-group-item">Item 6</div> -->
			</div>
			
            </div>
        </div>
        <div class="col-lg-6 margins">
            <div class="ibox-content">
            <div class="form-group row">    
                <label class="col-sm-6 control-label center">Sorted List</label>

            </div>
			<div id="example2Right" class="list-group col">
				<!-- <div class="list-group-item tinted" data-id="7" >Item 7</div>
				<div class="list-group-item tinted"  data-id="8">Item 8</div>
				<div class="list-group-item tinted">Item 9</div>
				<div class="list-group-item tinted">Item 10</div>
				<div class="list-group-item tinted">Item 11</div>
				<div class="list-group-item tinted">Item 12</div> -->
			</div>
            <div class="hr-line-dashed"></div>
	                <div class="form-group row">
	                    <div class="col-sm-8 col-sm-offset-2">
	                        <button class="btn btn-default" type="button" onclick="location.reload();">Cancel</button>
	                        <button class="btn btn-primary" onclick="saveSortedList()" type="button">Save</button>
	                    </div>
	                </div>
               
			
            </div>
            
        </div>
    </div>
    
@stop
@section('js')
    <script src="{{asset('assets/back/sortable/Sortable.js')}}"></script>
    <script type="text/javascript">
    var selected_channel_id=null;
    //initiate Select2 to Channnel Select box
    $('select[name="channel"]').select2({
        placeholder: 'Select a channel'
    }).on("select2:select", function (e) { 
        const data = e.params.data;
        selected_channel_id=data.id;
        //call Unsorted & sorted list related to selected Channel
        $( "#example2Left" ).empty();
        $( "#example2Right" ).empty();

        loadUnsortedProgrammeList(data.id);
        loadSortedProgrammeList(data.id);


        });
;

    //Sorting Pluging
    var unsorted=new Sortable(example2Left, {
        group: 'shared', // set both lists to same group
        animation: 150,
        sort: false

    });

    var sorted=new Sortable(example2Right, {
        group: 'shared',
        animation: 150
    });

    //loading unsorted programme List
    function loadUnsortedProgrammeList(channel_id) {

        
        $.ajax({
            method: "GET",
            url: '{{url('programme/unsortedList')}}',
            data:{ 'channel_id' : channel_id  }
        }).done(function( data ) {
            $.each(data, function( index, value ) {
                console.log(value);
                
                $( "#example2Left" ).append(
                    '<div class="list-group-item"  data-id="'+value['id']+'">'+value['get_programme']['programName']+'</div>'
                    );
            });
           
            
        });
    }
    
     //Loading Sorted Programme List
    function loadSortedProgrammeList(channel_id) {
        
        $.ajax({
            method: "GET",
            url: '{{url('programme/sortedList')}}',
            data:{ 'channel_id' : channel_id  }
        }).done(function( data ) {
            $.each(data, function( index, value ) {
                console.log(value['get_programme']['programId']);
                
                $( "#example2Right" ).append(
                    '<div class="list-group-item"  data-id="'+value['id']+'">'+value['get_programme']['programName']+'</div>'
                    );
            });
            
        });
    }
   

    //Save Sorted list to Database
   
    function saveSortedList() {
        swal({
                title: "Are you sure?",
                text:"Update Order",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"

            }).then(function (isConfirm) {
                if (isConfirm) {
                    var sorted_list = sorted.toArray();
                    var unsorted_list = unsorted.toArray();
                    //Update Sorted & unsorted programme
                    $.ajax({
                        method: "POST",
                        url: '{{url('programme/updateSortedProgrammes')}}',
                        data:{ 
                            'channel_id' : selected_channel_id ,
                            'sorted_list': sorted_list,
                            'unsorted_list' :unsorted_list
                            }
                    }).done(function( data ) {
                        
                    });
                } else {
                    swal("Cancelled", "Cancelled the status change", "error");
                }
            });
       
    }
    </script>
@stop