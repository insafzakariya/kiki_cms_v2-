@extends('layouts.back.master') @section('current_title','NEW USER')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />

@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>User</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Edit User </strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <form method="POST" class="form-horizontal" id="form">
                    {!!Form::token()!!}
                    <div class="form-group"><label class="col-sm-2 control-label">FIRST NAME <span class="required">*</span></label>

                        <div class="col-sm-10"><input type="text" name="first_name" class="form-control" value="{{$user->first_name}}"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">LAST NAME <span class="required">*</span></label>
                        <div class="col-sm-10"><input type="text" name="last_name" class="form-control" value="{{$user->last_name}}"></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">ROLES<span class="required">*</span></label>
                        <div class="col-sm-10">
                            <select class="js-source-states" style="width: 100%" name="roles[]" required multiple="multiple">
                                <?php foreach ($roles as $key => $value): ?>
                                <option value="{{$value->id}}" @if(in_array($value->id, $selected_roles)) selected @endif>{{$value->name}}</option>
                                <?php endforeach ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">EMAIL <span class="required">*</span></label>
                        <div class="col-sm-10"><input type="text" name="username" class="form-control" value="{{$user->email}}"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">PASSWORD <span class="required">*</span></label>
                        <div class="col-sm-10"><input type="password" name="password" id="password" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">CONFIRM PASSWORD <span class="required">*</span></label>
                        <div class="col-sm-10"><input type="password" name="password_confirmation" class="form-control"></div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="location.reload();">Cancel</button>
                            <button class="btn btn-primary" type="submit">Done</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        @stop
        @section('js')
            <script src="{{asset('assets/back/vendor/select2-3.5.2/select2.min.js')}}"></script>
            {{-- <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script> --}}

            <script type="text/javascript">
                $(document).ready(function(){
                    $(".js-source-states").select2();

                    jQuery.validator.addMethod("lettersonly", function(value, element) {
                        return this.optional(element) || /^[a-zA-Z_. ]+$/i.test(value);
                    }, "The first name can only consist of alphabetical & underscore");


                    $("#form").validate({
                        rules: {
                            first_name: {
                                required: true,
                                lettersonly: true,
                                maxlength: 20
                            },
                            last_name:{
                                required: true,
                                lettersonly: true,
                                maxlength: 20
                            },
                            roles:{
                                required: true,

                            },
                            branch:{
                                required: true
                            },
                            username:{
                                required: true,
                                email: true
                            },
                            password:{
                                required: true,
                                minlength: 6
                            },
                            password_confirmation:{
                                required: true,
                                minlength: 6,
                                equalTo: '#password'
                            },
                            "roles[]":{
                                required: true
                            }
                        },
                        submitHandler: function(form) {
                            form.submit();
                        }
                    });
                });


            </script>
@stop