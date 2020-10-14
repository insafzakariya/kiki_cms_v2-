@extends('layouts.back.master') @section('current_title','User View')
@section('css')

<style type="text/css">
.check-list{
  list-style-type: none;
}
.check-list li{
  float: left;
  margin-right: 8px;
  vertical-align: bottom;
}
.check-list li label{
  margin-left: 5px;
  vertical-align: middle;
}

</style>
@include('includes.opening-hours-css')
@stop
@section('page_header')
<div class="col-lg-9">
  <h2>User Management</h2>
  <ol class="breadcrumb">
    <li>
      <a href="{{url('/')}}">Home</a>
    </li>
    <li>
      <a href="{{url('user/list/all')}}">All users</a>
    </li>
    <li class="active">
      <strong>User View</strong>
    </li>
  </ol>
</div>         
@stop
@section('content')

<div class="row">
  <div class="col-lg-12 margins">
    <div class="ibox-content">
      <div class="row">
       <div class="col-md-12">
        <h3>{{ $user->first_name. ' ' .$user->last_name }}</h3>
        <h6><strong>{{ $user->created_at }}</strong></h6>
        <hr>

        <h4>
          <strong>Email: </strong>
          <span class="h5">           
            {{ $user->email }}
          </span>
        </h4>
        <br>

        {{-- <h4>
          <strong>Mobile: </strong>
          <span class="h5">           
            {{ $user->store->contact_number or '...' }}
          </span>
        </h4> --}}
        {{-- <br> --}}

        <h4>
          <strong>Business Name: </strong>
          <span class="h5">           
            {{ $user->store['business_name'] }}
          </span>
        </h4>
        <br>

        <h4>
          <strong>Company Address: </strong>
          <span class="h5">           
            {{ $user->store['company_address'] }}
          </span>
        </h4>
        <br>

        <h4>
          <strong>Contact Number: </strong>
          <span class="h5">           
            {{ $user->store['contact_number'] }}
          </span>
        </h4>
        
        {{-- @if ($user->inRole('merchant-user') || $user->inRole('super-admin-developer') || $user->inRole('system-admin') || $user->inRole('agent-admin'))
        <h4>
          <strong>Opening Hours: </strong>
        </h4> 
           @include('includes.opening-hours-module', ['openHourDisbale' => true])
        
        @endif --}}
        <br>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    @if($user->products)
    <div class="row">
      <div class="col-md-12">
          <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
            <thead>
              <tr>
                <th >Product image </th>
                <th>Product name</th>                  
                <th>Current stock</th>
                <th>Selling price</th>                           
              </tr>
            </thead>
        </table>

    </div>
  </div>
  @endif
  

  <div class="row">
    <div class="col-md-12">
      <hr>
      <div class="btn-group">
        @if($user->status == 3)
        <button class="btn btn-primary btn-sm user-approve" data-id="{{ $user->id }}"><i class="fa fa-check">Approve</i> </button>
        <button class="btn btn-danger btn-sm user-reject" data-id="{{ $user->id }}"><i class="fa fa-close">Reject</i> </button>
        @elseif($user->status == 4)
        <button class="btn btn-warning btn-sm disabled">Rejected</i> </button>
        @elseif($user->status == 2)
        <button class="btn btn-danger btn-sm disabled">Deleted</i> </button>
        @else
        <button class="btn btn-success btn-sm disabled">Approved</button>
        @endif
      </div>
    </div>
  </div>

</div>
</div>
</div>
</div>

@stop
@section('js')

<script>
  $(document).ready(function(){

    table=$('#example1').dataTable( {
      "ajax": '{{url('user/view/'.$user->id.'/products-data')}}',
      "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
      buttons: [
      {extend: 'copy',className: 'btn-sm'},
      {extend: 'csv',title: 'Merchant User Products List', className: 'btn-sm'},
      {extend: 'pdf', title: 'Merchant User Products List', className: 'btn-sm'},
      {extend: 'print',className: 'btn-sm'}
      ],
      "autoWidth": true,
      "processing": true,
      "serverSide": true,

    });

    $(".user-approve").click(function(e){
      $(this).attr('disabled', 'disabled');
      var $id = $(this).data('id');
      $.ajax({
        type: 'post',
        data: {
          'id' : $id,
          'status' : 1,
          '_token' : "{!! csrf_token() !!}"
        },
        url: '{{url('user/approve')}}',
        success: function(dd){
          // console.log(dd);
        },
        complete: function(data){
          var baseUrl = {!! json_encode(url('/')) !!}
          window.location = baseUrl + "/user/view/" + $id;
        }
      });
    });

    $(".user-reject").click(function(e){
      $(this).attr('disabled', 'disabled');
      var $id = $(this).data('id');
      $.ajax({
        type: 'post',
        data: {
          'id' : $id,
          'status' : 4,
          '_token' : "{!! csrf_token() !!}"
        },
        url: '{{url('user/approve')}}',
        success: function(dd){
          // console.log(dd);
        },
        complete: function(data){
          var baseUrl = {!! json_encode(url('/')) !!}
          window.location = baseUrl + "/user/view/" + $id;
        }
      });
    });
  });
</script>
@include('includes.opening-hours-js')
@stop