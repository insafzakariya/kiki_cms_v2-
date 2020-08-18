@extends('layouts.back.master') @section('current_title','All Role')
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
/*tr:hover .fixed_float{
  width: 45px;
  padding-left:0px;
}*/

/* tr:hover .fixed_float{
  width: 50px;
  padding:  5px 0 0 0px;
} */
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
    <h2>User Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Role List </strong>
        </li>
    </ol>
</div>  
<!-- <div class="col-lg-7">
    <h2><small>Total number of</small></h2>
    <ol class="breadcrumb">
        <li>
            Ad users <span>(<strong>151,528</strong>)</span>
        </li> 
        <li>
            Merchant users <span>(<strong>78,315</strong>)</span>
        </li>
        <li>
            Agent users <span>(<strong>46</strong>)</span>
        </li>
        <li>
            Admin users <span>(<strong>8</strong>)</span>
        </li>
    </ol>
</div>  -->               
@stop
@section('content')
<div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="location.href = '{{url('user/role/add')}}';">
    <p class="plus">+</p>   
</div>

<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">
            <div class="panel-body">                
              <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Permissions</th>                       
                        <th width="1%">Edit</th>
                        <th width="1%">Delete</th>
                       
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
  var table;
  $(document).ready(function(){   
        table=$('#example1').dataTable( {
            "ajax": '{{url('user/role/json/list')}}',
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Menu List', className: 'btn-sm'},
                {extend: 'pdf', title: 'Menu List', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ],
             "autoWidth": false,
           
        });

        table.on( 'draw.dt', function () {
            $('.role-delete').click(function(e){             
                  e.preventDefault();
                  id = $(this).data('id');                  
                  confirmAlert(id);                 
                                  
            });
         
        });
       

        
  });

  function confirmAction(id){
   console.log(table);
   
    $.ajax({
      method: "POST",
      url: '{{url('user/role/delete')}}',
      data:{ 'id' : id  }
    })
      .done(function( msg ) {
        table.fnReloadAjax();
      });    
     
  }
  
  
</script>
@stop