<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>Admin Panel | KIKI CMS  web portal</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="shortcut icon" href="/favicon.ico">

    <link href="{{asset('assets/back/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{asset('assets/back/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">

    <!-- Gritter -->
   
    <link href="{{asset('assets/back/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/css/style.css')}}" rel="stylesheet">

</head>
<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                 @if($errors->has('login'))
                                <div class="alert alert-danger">
                                  Oh snap! {{$errors->first('login')}}
                                </div>
                              @endif

                {{-- <h6 class="logo-name">&nbsp;</h6> --}}

            </div>
            <h3>Welcome to KIKI CMS </h3>           
            <form class="m-t" role="form" action="{{URL::to('user/login')}}" method="POST">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" required="" name="username">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" required="" name="password">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <small><a href="{{ url('front/forget-password') }}">Forgot password?</a> Contact system administrators</small>
                <!-- <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a> -->
            </form>
            <p class="m-t"> <small>Framework based on Laraval 5.1</small> </p>
        </div>
    </div>

<script src="{{asset('assets/back/js/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('assets/back/js/bootstrap.min.js')}}"></script>   

</body>

</html>
