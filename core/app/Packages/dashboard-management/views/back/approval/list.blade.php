@extends('layouts.back.master') @section('current_title','All Products')
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
    <h2>Pending Products</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Product Approve</strong>
        </li>
    </ol>
</div>                  
@stop
@section('content')


<div class="row">
    <div class="col-lg-12 margins">
        <div class="ibox-content">
            <div class="table-responsive">                
             	<table id="example1" class="table table-striped table-bordered table-hover dataTables-example" width="100%">
                    <thead>
                      <tr>
                          <th rowspan="2" width="4%">#</th>
                          <th rowspan="2" width="20%"> Name</th>                        
                          <th rowspan="2" width="5%"> Price</th>  
                          <th rowspan="2"  width="5%" >User</th>                        
                          <!-- <th rowspan="2"> Available</th>                         -->
                                                 
                          
                          <th colspan="2" class="text-center" width="4%" style="font-weight:normal;">Action</th>
                      </tr>
                      <tr style="display: none;">
                           <th style="display: none;" ></th>
                           <!-- <th style="display: none;" ></th> -->
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
            "ajax": '{{url('product/approve/json/list')}}',
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Product Creation List', className: 'btn-sm'},
                {extend: 'pdf', title: 'Product Creation List', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ],
             "autoWidth": false
        });

        table.on( 'draw.dt', function () {
            $('.product-delete').click(function(e){             
                  e.preventDefault();
                  id = $(this).data('id');                  
                  confirmAlert(id);                 
                                  
            });
         
        });

        
  });

  function confirmStatusAction(id){
     $.ajax({
      method: "POST",
      url: '{{url('product/creation/delete')}}',
      data:{ 'id' : id  }
    })
      .done(function( msg ) {
        table.fnReloadAjax();
      });    
     
  }
 
  
  
</script>


@stop
