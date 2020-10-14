@extends('layouts.back.master') @section('current_title','Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Song Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/playlist')}}">Playlist</a>
            </li>
            <li class="active">
                <strong>Step 2</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-5 margins">
            <div class="ibox-content">

                <form method="POST" class="form-horizontal" id="form">
                    {!!Form::token()!!}

                    <div class="form-group">
                        <div class="col-lg-4">
                            <label class="col-sm-2 control-label">Explicit</label>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="explicit" value="no" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    No
                                </label>
                            </div><div class="form-check">
                                <input class="form-check-input" type="radio" name="explicit" value="clean" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Clean Version (Clean)
                                </label>
                            </div><div class="form-check">
                                <input class="form-check-input" type="radio" name="explicit" value="yes" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Yes (Explicit)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-4">
                            <label class="col-sm-2 control-label">Content Policy</label>
                        </div>
                        <div class="col-lg-8" style="display: inline">
                            <div class="col-md-4">
                                <select class="form-control" id="policySelector" style="width: 120px" multiple="multiple">
                                    @foreach($content_policies as $policy)
                                        <option value="{{$policy->PolicyID}}">{{$policy->Name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select class="form-control" id="policySelect" name="content_policies[]" style="width: 120px; margin-left: 20px" multiple="multiple">

                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-4">
                            <label class="col-sm-2 control-label">Advertisement Policy</label>
                        </div>
                        <div class="col-lg-4"><select id="advertisementPolicy" name="advertisement_policy" class="form-control">
                                @foreach($advertisement_policies as $policy)
                                    <option value="{{$policy->PolicyID}}">{{$policy->Name}}</option>
                                @endforeach
                            </select></div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="location.reload();">Back</button>
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
        $(document).ready(function(){

            $('#advertisementPolicy').select2();

        });

        $('#policySelector').on('change', function (e) {
            var optionSelected = $("option:selected", this);
            var valueSelected = optionSelected.val();
            var textSelected = optionSelected.text();
            $('#policySelect').append($('<option>', {
                value: valueSelected,
                text : textSelected
            }));
        });

        $('#policySelect').click(function() {
            $(this).find('option:selected').remove();
        });


    </script>
@stop