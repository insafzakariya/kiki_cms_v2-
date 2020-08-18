@extends('layouts.back.master') @section('current_title','Update Product Creation')
@section('css')


@stop
@section('page_header')
 <div class="col-lg-9">
    <h2>Product Management</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li class="active">
            <strong>Update</strong>
        </li>
    </ol>
</div>                  
@stop
@section('content')

<div class="row">
                <div class="col-lg-12">

                    <div class="ibox product-detail">
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-md-5">


                                    <div class="product-images">
                                        <?php foreach ($products->getImages as $key => $image): ?>
                                            <div>
                                            <div class="image-imitation">
                                               <img alt="image"  src="{{url('/').'/core/storage/'.$image->path.'/'.$image->filename}}"></img>
                                            </div>
                                        </div>
                                        <?php endforeach ?>
                                        


                                    </div>

                                </div>
                                <div class="col-md-7">

                                    <h2 class="font-bold m-b-xs">
                                        {{$products->name}}
                                    </h2>
                                   <!--  <small>Many desktop publishing packages and web page editors now.</small> -->
                                    <div class="m-t-md">
                                        <h2 class="product-main-price">Rs {{number_format($products->price,2)}} <!-- <small class="text-muted">Exclude Tax</small> --> </h2>
                                    </div>
                                    <hr>

                                    <h4>Product description</h4>

                                    <div class="small text-muted">
                                        {{$products->description}}

                                        <!-- <br/>
                                        <br/>
                                        There are many variations of passages of Lorem Ipsum available, but the majority
                                        have suffered alteration in some form, by injected humour, or randomised words
                                        which don't look even slightly believable. -->
                                    </div>
                                    <dl class="small m-t-md">
                                        <dt>Seller</dt>
                                        <dd> {{$products->getUser->first_name}} {{$products->getUser->last_name}}</dd>
                                        <dt>Business Details</dt>
                                        <dd>{{$products->getBuninessDetails->business_name}}</dd>
                                        <dd>{{$products->getBuninessDetails->company_address}}</dd>
                                        <dd>{{$products->getBuninessDetails->contact_number}}</dd>
                                        <dd><a href="{{url('/').'/'.$products->getBuninessDetails->business_page_name}}">{{$products->getBuninessDetails->business_page_name}}</a></dd>
                                        
                                    </dl>
                                    <hr>

                                    <div>
                                        <div class="btn-group">
                                            <?php if ($products->status==0): ?>
                                                <button class="btn btn-primary btn-sm" onclick="product_approve({{$products->id}})"><i class="fa fa-check">Approve</i> </button>
                                                <button class="btn btn-danger btn-sm" onclick="product_reject({{$products->id}})"><i class="fa fa-close">Reject</i> </button>
                                            <?php else: ?>
                                                <?php if ($products->status==1): ?>
                                                    <small class="label label-primary"><i class="fa fa-check"></i>Approved</small>
                                                <?php elseif($products->status==2): ?>
                                                    <small class="label label-danger"><i class="fa fa-close"></i>Rejected</small>
                                                <?php endif ?>
                                            <?php endif ?>
                                            
                                            
                                        </div>
                                    </div>



                                </div>
                            </div>

                        </div>
                      <!--   <div class="ibox-footer">
                            <span class="pull-right">
                                Full stock - <i class="fa fa-clock-o"></i> 14.04.2016 10:04 pm
                            </span>
                            The generated Lorem Ipsum is therefore always free
                        </div> -->
                    </div>

                </div>
            </div>
@stop
@section('js')

<script type="text/javascript">
	$(document).ready(function(){
		$(".js-source-states").select2();
        $('.product-images').slick({
            dots: true
        });

       
	});

     function product_approve(id) {
    $.ajax({
      method: "POST",
      url: '{{url('product/approve')}}',
      data:{ 'id' : id  }
    })
      .done(function( msg ) {
         location.reload();
      }); 
  }
  function product_reject(id) {
    $.ajax({
      method: "POST",
      url: '{{url('product/reject')}}',
      data:{ 'id' : id  }
    })
      .done(function( msg ) {
        location.reload();
      }); 
  }

   
      
	
	
</script>
@stop
