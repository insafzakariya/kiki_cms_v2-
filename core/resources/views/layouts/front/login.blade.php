@extends('layouts.front.master')

<!-- CSS FOR THIS PAGE -->
@section('css')
<style type="text/css">
    /* Media screens lesser than 480px */
    @media(max-width: 320px) and (max-height: 480px), (max-width: 320px){

        h1.sub{
            font-size: 20px;
            margin-bottom: -10px;
        }
        #heading {
            font-size: 14px;
            text-align: center;
            margin-bottom: -2px;
        }
        .checkbox label::before{
            left: 4px;
            margin-left: -18px;
        }
        .checkbox input[type="checkbox"]{
            margin-left: -10px;
        }
        .checkbox input[type="checkbox"]:checked + label::after{
            left: 11px;
        }
        .checkbox label{
            padding-left: 10px;
            line-height: 21px;
        }
        #btn .submit, button.reset{
            font-size: 12px;
            padding: 10px;
        }
        /*a.btn{
          margin-top: 5px!important;
        }*/
    }
    /* Media screens lesser than 640px and larger than 360px */
    @media(min-width: 360px) and (max-width: 640px){
     /* a.btn{
          margin-top: 5px!important;
        }*/
    }
    /* Media screens lesser than 1024px and larger than 768px */
    @media(min-width: 768px) and (max-width: 1024px){
      /*a.btn{
          margin-top: 5px!important;
        }*/
    }
    /* Media screens lesser than 1280px and larger than 800px - Portaite Version */
    @media(min-width: 800px) and (max-width: 1280px){
      
    }
    /* Media screens lesser than 1280px and larger than 980px - Portaite Version */
    @media(min-width: 980px) and (max-width: 1280px){

    }
    /* Media screens lesser than 600px and larger than 1280px - Landscape Version */
    @media(min-width: 1280px) and (max-width: 600px){

    }

    /* Media screens lesser than 900px and larger than 1920px - Landscape Version */
    @media(min-width: 1920px) and (max-width: 900px){

    }

    .btn-right{
        float: right!important;
        background: none!important;
        border: none!important;
        margin-top: 5px!important;
        padding-left: 0px;
        font-weight: 600;
        /*color: #bdbdbd;*/
        color: blue;
        text-decoration: underline;
    }
    .btn-right:hover{
        float: right!important;
        background: none!important;
        border: none!important;
        margin-top: 5px!important;
        padding-left: 0px;
        font-weight: 600;
        color: blue;
        text-decoration: none;
    }
    a.btn{
      color: #fff;
    }

    #success_message{ display: none;}
</style>

@stop


<!-- BODY -->

@section('content')
<section class="martop30">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="white-block" id="company-reg">
                    <div class="white-block-content">
                        <div class="page-content clearfix martop30">
                           <form class="well form-horizontal" id="login_form" method="POST" action="{{URL::to('front/login')}}">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <fieldset>
                            <legend>
                                <center>
                                    <h2>Login</h2>
                                </center>
                            </legend>                          
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input name="username" placeholder="Email" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        <input name="password" placeholder="Password" class="form-control"  type="password">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                  <div class="col-xs-6">
                                    <div class="checkbox checkbox-inline">
                                      <input type="checkbox" name="login-remember" id="login-remember">
                                      <label for="login-remember">Remember me</label>
                                    </div>
                                  </div>
                                  <div class="col-xs-6 text-right">
                                    <div class="checkbox checkbox-inline">
                                      <a href="{{ route('front.forget.password') }}" class="forgot-password" data-dismiss="modal">Forgot Password?</a>
                                    </div>
                                  </div>
                                  <!-- <div class="col-xs-6 text-right">
                                    <a href="javascript;" class="forgot-password" data-dismiss="modal">Forgot Password?</a>
                                  </div> -->
                                </div>
                              </div>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    
@stop
<!-- JS FOR THIS PAGE -->
@section('js')
    <script type="text/javascript">
    $(document).ready(function() {

      $('#login_form').bootstrapValidator({
      // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
      feedbackIcons: {
          // valid: 'glyphicon glyphicon-ok',
          // invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
           username: {
              validators: {
                    emailAddress: {
                      message: 'Please enter a valid Email Address'
                  },
                  notEmpty: {
                      message: 'Please enter your Username'
                  }
              }
          },
           password: {
              validators: {
                   stringLength: {
                      min: 6,
                      message: 'Please choose a password with at least 6 characters'
                  },
                  notEmpty: {
                      message: 'Please enter your Password'
                  }
              }
          },
        
          }
      })

      .on('success.form.bv', function(e) {
          $('#success_message').slideDown({ opacity: "show" }, "slow") // Do something ...
              $('#login_form').data('bootstrapValidator').resetForm();
    
          // Prevent form submission
          e.preventDefault();
    
          // Get the form instance
          var $form = $(e.target);
    
          // Get the BootstrapValidator instance
          var bv = $form.data('bootstrapValidator');
    
          // Use Ajax to submit form data
          $.post($form.attr('action'), $form.serialize(), function(result) {
              console.log(result);
          }, 'json');
      });

        $("#login_reset").click(function(){
         $('#login_form').bootstrapValidator("resetForm",true);    
      });

    });
    
</script>
    
@stop
