@extends('layouts.back.master') @section('current_title','Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}" />

    <style type="text/css">
        .steps-form{
            padding-bottom: 30px;
        }

        .no-padding{
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .form-group {
            margin-bottom: 25px !important;
        }
        .policy > option{
            margin-bottom: 5px;
        }

        #explicit-error {
            margin-left: 27%;
        }

    </style>
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Song Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/song')}}">Song</a>
            </li>
            <li class="active">
                <strong>Add</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <div class="steps-form">
                    <div class="steps-row setup-panel">
                        <div class="steps-step steps-success">
                            <span  class="btn btn-success btn-circle ">1</span>
                            <p>Step 1</p>
                        </div>
                        <div class="steps-step steps-success">
                            <span   class="btn btn-success btn-circle" >2</span>
                            <p>Step 2</p>
                        </div>
                        <div class="steps-step">
                            <span  class="btn btn-default btn-circle" >3</span>
                            <p>Step 3</p>
                        </div>
                    </div>
                </div>

                <form method="POST" class="form-horizontal" id="form" data-parsley-validate>
                    {!!Form::token()!!}

                    <input hidden name="song_id" value="{{$data ? $data->songId : ''}}">

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class="  control-label">Explicit*</label>
                        </div>
                        <div class="col-lg-6 col-sm-7">
                            <div class="form-check">
                                <input class="form-check-input after-error-placement" type="radio" name="explicit" @if($data && $data->explicit == "no") checked="checked" @endif value="no" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    No
                                </label>
                            </div><div class="form-check">
                                <input class="form-check-input after-error-placement" type="radio" name="explicit" @if($data && $data->explicit == "clean") checked="checked" @endif value="clean" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Clean Version (Clean)
                                </label>
                            </div><div class="form-check">
                                <input class="form-check-input after-error-placement" type="radio" name="explicit" @if($data && $data->explicit == "yes") checked="checked" @endif value="yes" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Yes (Explicit)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class=" control-label">Content Policy*</label>
                        </div>
                        <div class="col-lg-7 col-sm-7" style="display: inline">
                            <div class="col-md-5 no-padding">
                                <select class="form-control policy" id="policySelector" name="advertisementPolicy" style="width: 90%;" multiple="multiple">
                                    @foreach($content_policies as $policy)
                                        <option value="{{$policy->PolicyID}}">{{$policy->Name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <select class="form-control policy" id="content_policies" name="content_policies[]"
                                        style="width:90%;" multiple >
                                    {{--  @if($data && isset($data->content_policies))--}}
                                    @foreach($content as $policy)
                                        <option value="{{$policy->PolicyID}}" selected>{{$policy->Name}}</option>
                                    @endforeach
                                    {{--@endif--}}

                                </select>
                                <label id="content_error" class="text-danger" for="content_policies"></label>

                            </div>

                            <input type="hidden" id="content_count" value="{{$content_count}}" name="content_count">

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3 col-lg-3">
                            <label class="  control-label">Advertisement Policy</label>
                        </div>
                        <div class="col-lg-6 col-sm-7">
                            <select id="advertisementPolicy" name="advertisement_policy" class="form-control">
                                {{--<option value=""> Please select a advertisement policy</option>--}}
                                @foreach($advertisement_policies as $policy)
                                    <option value="{{$policy->PolicyID}}" @if($data && $data->advertisementPolicyId == $policy->PolicyID) selected @endif>{{$policy->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="cancelRedirect()">Back</button>
                            <button class="btn btn-primary" type="submit">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script type="text/javascript">

        let product_id = '';
        let product_type = '';
        $(document).ready(function(){

            let url_string = window.location.href;
            let url_new = new URL(url_string);
             product_id = url_new.searchParams.get("product_id");
             product_type = url_new.searchParams.get("type");


            if (product_id)
                $("#product_id").val(product_id);



            $('#advertisementPolicy').select2({
                placeholder: "Please select a advertisement policy",
            });
           // $('#content_policies').select2();

            $("#form").validate({
               // debug: true,
                ignore: [],
                rules: {
                    content_policies: {
                        required: true,
                    },
                    explicit: {
                        required: true
                    },
                },
                errorPlacement: function (error, element) {
                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    } else if (element.hasClass('after-error-placement')) {
                        element.parent().parent().after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function(form) {
                    $('#content_error').html('');

                    if($('#content_policies').find('option').length == 0){
                        $('#content_error').html('This field is required.');
                        return
                    }
                    form.submit();
                }
            });

        });

        $('#policySelector').on("click", "option", function() {
            let optionSelected = $(this);
            let valueSelected = optionSelected.val();
            let textSelected = optionSelected.text();
            if(valueSelected){
                $('#content_policies').append($('<option>', {
                    value: valueSelected,
                    text : textSelected,
                    selected: true
                }));
               // $('#content_count').val($('#content_policies').find('option').length);
                $(this).remove();
            }
        });

       /* $('#policySelector').click(function() {

            $(this).find('option:selected').remove();
        });*/

       let contentPolicies = $('#content_policies');

        contentPolicies.on("click", "option", function() {
            let optionSelected = $(this);
            let valueSelected = optionSelected.val();
            let textSelected = optionSelected.text();
            if(valueSelected){
                $('#policySelector').append($('<option>', {
                    value: valueSelected,
                    text : textSelected
                }));
                $(this).remove();
                contentPolicies.find('option').prop('selected', true);
                //$('#content_count').val($('#content_policies').find('option').length);
            }
        });

        function cancelRedirect() {
            if (product_id && product_type) {
                let urlTest = '';
                if (product_type == 'add')
                    urlTest = '/admin/products/' + product_id + '/add/step-2?type=' + product_type;
                else
                    urlTest = '/admin/products/' + product_id + '/add/step-3?type=' + product_type;
                location.href = '{{url()}}' + urlTest;
            } else {
                location.href = '{{url('admin/song')}}'
            }
        }


    </script>
@stop