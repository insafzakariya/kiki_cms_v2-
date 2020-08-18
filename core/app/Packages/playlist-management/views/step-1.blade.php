@extends('layouts.back.master') @section('current_title','Playlist/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/plugins/jQueryUI/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/front/css/datepicker/bootstrap-datepicker.min.css')}}"/>
    <link href="{{url('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .form-group {
            margin-bottom: 40px;
        }
    </style>
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Playlist Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/playlist')}}">Playlist</a>
            </li>
            <li class="active">
                <strong>Step 1</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">

                <div class="steps-form" style="margin-bottom: 40px;">
                    <div class="steps-row setup-panel">
                        <div class="steps-step steps-success">
                            <span class="btn btn-success btn-circle ">1</span>
                            <p>Step 1</p>
                        </div>
                        <div class="steps-step">
                            <span class="btn btn-default btn-circle">2</span>
                            <p>Step 2</p>
                        </div>
                        <div class="steps-step">
                            <span class="btn btn-default btn-circle">3</span>
                            <p>Step 3</p>
                        </div>
                    </div>
                </div>

                <form method="POST" class="form-horizontal" id="form" enctype="multipart/form-data">
                    {!!Form::token()!!}

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Playlist Type*</label>
                        <div class="col-sm-8">
                            <select id="playlistType" name="type" class="form-control" required>
                                <option  value="">Please select a type</option>
                                @foreach($playlist_types as $type)
                                    <option value="{{$type->code}}"
                                            @if($data && ($data->playlist_type == $type->code))
                                            selected
                                            @elseif(!$data && $type->code == "g")
                                            selected
                                            @endif>
                                        {{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">Playlist Name*</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="name"
                                                     value="{{$data ? $data->name : ''}}" required></div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="description">{{$data ? $data->description : ''}}</textarea>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">Release Date*</label>
                        <div class="col-sm-8 ">
                            <div class="input-group">
                                <input type="hidden" id="releaseDateValue" value="{{$data ? $data->release_date : null}}">
                                <input type="text" id="releaseDate" class="form-control" name="release_date" autocomplete="off" required>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">Publish Date*</label>
                        <div class="col-sm-8 ">
                            <div class="input-group">
                                <input type="hidden" id="uploadedDateValue" value="{{$data ? $data->publish_date : null}}">
                                <input type="text" id="uploadedDate" class="form-control" name="publish_date" autocomplete="off" required>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-2 control-label">End Date*</label>
                        <div class="col-sm-8 ">
                            <div class="input-group">
                                <input type="hidden" id="endDateValue" value="{{$data ? $data->expiry_date : null}}">
                                <input type="text" id="endDate" class="form-control" name="end_date" required>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>

                    {{--                    <div class="input-group date">--}}
                    {{--                        <input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>--}}
                    {{--                    </div>--}}

                    <div class="form-group">
                        <label class="col-sm-2 control-label required">Image*</label>
                        <div class="col-sm-8">
                            <input id="image" name="image" type="file"   class="form-control after-error-placement" accept="image/*" >
                            <p class="text-danger" style="font-weight: 600; font-size: 13px;">image size should be 175px *175px</p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Content Policy*</label>


                        <div class="col-sm-8" style="display: inline">
                            <div class="col-md-6" style="padding-left: 0px;">
                                <select class="form-control" id="policySelector"  multiple="multiple">
                                    @foreach($content_policies as $policy)
                                        @if(($data && $data->contentPolicies()->where('PolicyID', $policy->PolicyID)->count() == 0) || !$data)
                                            <option value="{{$policy->PolicyID}}" >{{$policy->Name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" style="padding-left: 0px;">
                                <select class="form-control " name="policySelect" id="policySelect" style=" margin-left: 15px" multiple="multiple">
                                    @if($data)
                                        @foreach($data->contentPolicies as $policy)
                                            <option value="{{$policy->PolicyID}}" >{{$policy->Name}}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <input type="hidden" id="testContentPolicy"  name="content_policies" value="{{$data ? json_encode($data->contentPolicies->pluck("PolicyID")->toArray()) : "[]"}}">
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Advertisement Policy</label>
                        <div class="col-sm-8"><select id="advertisementPolicy" name="advertisement_policy" class="form-control">
                                @foreach($advertisement_policies as $policy)
                                    <option value="{{$policy->PolicyID}}" @if($data && $data->advertisement_policy == $policy->PolicyID) selected @endif>{{$policy->Name}}</option>
                                @endforeach
                            </select></div>
                    </div>

                    <input type="hidden" id="playlistId" value="{{$data ? $data->id : ''}}">

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <a class="btn btn-default" href="{{url("/admin/playlist")}}" style="width: 100px; margin-right: 10px;">Cancel</a>
                            <button class="btn btn-primary" type="submit" style="width: 100px;">Next</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
@section('js')

    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/back/css/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/js/fileinput.min.js"></script>
    <script src="{{asset('assets/front/js/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/back/js/jquery-validation-extension.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#image").fileinput({
                theme: "fa",
                showUpload: false,
                showRemove: true,
                multiple: false,
                validateInitialCount: true,
                initialPreviewShowDelete: true,
                allowedFileTypes: ['image'],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "jfif"],
                @if(count($image) > 0)
                initialPreview: <?php echo json_encode($image); ?>,
                initialPreviewConfig: <?php echo json_encode($image_config) ?>,
                @endif
                //maxFileSize: 20,
            });

            const date = new Date();
            var todayString = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            $('#advertisementPolicy').select2();
            $('#playlistType').select2();

            $('#releaseDate').datepicker({
                format: 'yyyy-mm-dd',
            });
            $('#uploadedDate').datepicker({
                format: 'yyyy-mm-dd' ,
            });
            var expiryDate = $('#endDateValue').val() ? $('#endDateValue').val() : "2999-12-12";
            if (expiryDate == "2999-12-12") {
                $('#endDate').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: "today"
                });
            } else {
                $('#endDate').datepicker({
                    format: 'yyyy-mm-dd',
                });
            }

            var releaseDate = todayString;
            var publishDate = todayString;

            if ($('#playlistId').val() != '') {
                releaseDate = $('#releaseDateValue').val() ? $('#releaseDateValue').val() : "";
                publishDate = $('#uploadedDateValue').val() ? $('#uploadedDateValue').val() : "";
            }

            $('#uploadedDate').datepicker('update', publishDate);
            $('#releaseDate').datepicker('update', releaseDate);
            $('#endDate').datepicker('update', expiryDate);

            jQuery.validator.addMethod("contentPolicyRequired", function(value, element) {
                return JSON.parse(value).length !== 0;
            }, "This field is required.");

            $("#form").validate({
                ignore: "",
                rules: {
                    release_date: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    publish_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    },
                    content_policies: {
                        contentPolicyRequired: true ,
                    },
                    image: {
                        required:{
                            depends: function () {
                                return $('#image').fileinput('getFilesCount', true) === 0
                            }
                        } ,
                        fileSize_max:{{ env('Upload_Image_Size') }}
                    },
                    tags: {
                        required: true

                    }

                },
                errorPlacement: function (error, element) {
                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    }else if(element.hasClass('after-error-placement')){
                        element.parent().parent().parent().after(error);
                    }else {
                        element.after(error);
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

        });

        $('#policySelector').on('change', function () {
            $(this).find('option:selected').each(function () {
                if($(this).text().length !== 0) {
                    var optionSelected = $(this);
                    var valueSelected = optionSelected.val();
                    var textSelected = optionSelected.text();
                    if (updateContentPolicy(valueSelected)) {
                        $(this).hide();
                        $('#policySelect').append($('<option>', {
                            value: valueSelected,
                            text: textSelected
                        }));
                    }
                }
            });

        });

        $('#policySelect').click(function() {
            $(this).find('option:selected').each(function () {
                if($(this).text().length !== 0){
                    removeContenPolicy($(this).val(), $(this).text());
                    $(this).remove();
                }

            });

        });

        function updateContentPolicy(value) {
            let existing = $('#testContentPolicy');
            if (existing.val()) {
                let ar = JSON.parse(existing.val());
                if (!ar.includes(value) && value.length !== 0) {
                    ar.push(value);
                    existing.val(JSON.stringify(ar));
                    return true;
                }
                return false;
            } else {
                let ar = [];
                ar.push(value);
                existing.val(JSON.stringify(ar));
                return true;
            }

        }

        function removeContenPolicy(value, text) {
            let existing = JSON.parse($('#testContentPolicy').val());
            let existingVals = [];
            existing.forEach((val) => {
                if(parseInt(val) !== parseInt(value) && val.length !== 0){
                    existingVals.push(val);
                }
            });

            if(existingVals.length === 0){
                $('#testContentPolicy').val("[]");
            }else{
                $('#testContentPolicy').val(JSON.stringify(existingVals));
            }
            $('#policySelector').append($('<option>', {
                value: value,
                text: text
            }));
        }


    </script>
@stop