<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google" content="notranslate">

    <title>KIKI.CMS | Admin panel accessing pages</title>

    <link href="{{asset('assets/back/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{asset('assets/back/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">

    <!-- Gritter -->
    <link href="{{asset('assets/back/js/plugins/gritter/jquery.gritter.css')}}" rel="stylesheet">

    <link href="{{asset('assets/back/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('assets/back/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link href="{{asset('assets/back/css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/back/vendor/sweetalert/lib/sweet-alert.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/back/file/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" />
    <link rel="stylesheet" href="{{asset('assets/back/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/select2-bootstrap/select2-bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/plugins/slick/slick.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/plugins/slick/slick-theme.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/plugins/switchery/switchery.css')}}" />
      @yield('css')
</head>
<?php $loggedUser=Sentinel::getUser();?>
<body>
    <!-- MASTER BLADE BODY DIV  START-->
       <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
               @include('includes.menu2')

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                <form role="search" class="navbar-form-custom" action="">
                    <div class="form-group">
                        <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                    </div>
                </form>
                 <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="{{url('/')}}">Back to Home</a>
            </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Welcome {{$loggedUser->first_name}} {{$loggedUser->last_name}} to sambole.lk Admin Portal.</span>
                    </li>

                    <li>
                        <a href="{{URL::to('user/logout')}}">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
        <div class="row wrapper border-bottom white-bg page-heading">
            @yield('page_header')
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content">
                         @yield('content')
                </div>

            </div>
            <div class="footer">
                    <!-- <div class="pull-right">
                        10GB of <strong>250GB</strong> Free.
                    </div>
                    <div>
                        <strong>Copyright</strong> Example Company &copy; 2014-2017
                    </div> -->
                </div>
        </div>

        </div>

    </div>

    <!-- MASTER BLADE BODY DIV  END-->
    <!-- Mainly scripts -->
    <script src="{{asset('assets/back/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('assets/back/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Flot -->
    <script src="{{asset('assets/back/js/plugins/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/flot/jquery.flot.tooltip.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/flot/jquery.flot.spline.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/flot/jquery.flot.pie.js')}}"></script>

    <!-- Peity -->
<!--     <script src="{{asset('assets/back/js/plugins/peity/jquery.peity.min.js')}}"></script>
    <script src="{{asset('assets/back/js/demo/peity-demo.js')}}"></script> -->

    <!-- Custom and plugin javascript -->
    <script src="{{asset('assets/back/js/inspinia.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/pace/pace.min.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{asset('assets/back/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- GITTER -->
    <script src="{{asset('assets/back/js/plugins/gritter/jquery.gritter.min.js')}}"></script>

    <!-- Sparkline -->
    <script src="{{asset('assets/back/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Sparkline demo data  -->
    <script src="{{asset('assets/back/js/demo/sparkline-demo.js')}}"></script>

    <!-- ChartJS-->
    <script src="{{asset('assets/back/js/plugins/chartJs/Chart.min.js')}}"></script>

    <!-- Toastr -->
    <script src="{{asset('assets/back/js/plugins/toastr/toastr.min.js')}}"></script>
    <!-- dtatatable -->
    <script src="{{asset('assets/back/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/chosen/chosen.jquery.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/chosen/chosen.jquery.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('assets/back/vendor/sweetalert/lib/sweet-alert.min.js')}}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.12/api/fnReloadAjax.js"></script>
    <script src="{{asset('assets/back/file/bootstrap-fileinput-master/js/fileinput.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/back/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/back/select2-3.5.2/select2.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/slick/slick.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/switchery/switchery.js')}}"></script>

     @yield('js')
    <script type="text/javascript">
        $(document).ready(function(){
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });


    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

     @if(session('success'))
        Command: toastr["success"]("{{session('success.message')}} ", "{{session('success.title')}}")
      @elseif(session('error'))
        Command: toastr["error"]("{{session('error.message')}} ", "{{session('error.title')}}")
      @elseif(session('warning'))
        Command: toastr["warning"]("{{session('warning.message')}} ", "{{session('warning.title')}}")
      @elseif(session('info'))
        Command: toastr["info"]("{{session('info.message')}} ", "{{session('info.title')}}")
      @endif

    });


     function confirmAlert(id) {
        swal({
            title: "Are you sure?",
            text:"Your will not be able to recover this !",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!"
        },
        function (isConfirm) {
           if (isConfirm) {
                confirmAction(id);
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });
    }

    function handleData(data){
        if(data.status=='success'){
            sweetAlert('Delete Success','Record Deleted Successfully!',0);
            table.ajax.reload();
        }else if(data.status=='invalid_id'){
            sweetAlert('Delete Error','Menu Id doesn\'t exists.',3);
        }else{
            sweetAlert('Error Occured','Please try again!',3);
        }
    }

    </script>


</body>

<!-- Mirrored from webapplayers.com/inspinia_admin-v2.7.1/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 08 Apr 2018 19:34:55 GMT -->
</html>
