@extends('layouts.back.master') @section('current_title','Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/front/css/datepicker/bootstrap-datepicker.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}"/>

    <style>
        .select2-selection--multiple:before {
            content: "";
            position: absolute;
            right: 7px;
            top: 42%;
            border-top: 5px solid #888;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
        }

        .steps-form{
            padding-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px !important;
        }

        .no-padding{
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .add-padding{
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        #uploadedDate-error, #releaseDate-error, #endDate-error {
            margin-left: 27%;
        }

        #name-error, #isbc_code-error, #description-error, #p-error {
             width: 73%;
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

                <form method="POST" class="form-horizontal" id="form">
                    {!!Form::token()!!}

                    <input hidden name="song_id" value="{{$data ? $data->songId : ''}}">

                    <div class="form-group"><label class="col-sm-3 col-lg-3 control-label">Song Name*</label>
                        <div class="col-sm-7 col-lg-6"><input type="text" class="form-control" name="name"
                                                              value="{{$data ? $data->name : ''}}" required>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-sm-3 col-lg-3 control-label">ISRC Code*</label>
                        <div class="col-sm-7 col-lg-6"><input type="text" class="form-control" name="isbc_code"
                                                              value="{{$data ? $data->isbc_code : ''}}" required></div>
                    </div>

                    <div class="form-group"><label class="col-sm-3 col-lg-3 control-label">Song Description*</label>
                        <div class="col-sm-7 col-lg-6">
                            <textarea class="form-control" name="description" required>{{$data ? $data->description : ''}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="primaryArtists" class="col-sm-3 col-lg-3 control-label">Primary Artists *</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="primaryArtists" name="primary_artist[]" class="form-control select-simple" multiple="multiple" required>
                               {{-- <option value="">Please select a primary artist</option>--}}
                                @if($data)
                                    @foreach($data->primaryArtists as $artist)
                                        <option value="{{$artist->artistId}}" selected>{{$artist->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="featuredArtists" class="col-sm-3 col-lg-3 control-label">Featured Artists</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="featuredArtists" name="featured_artists[]"
                                    class="form-control select-simple" multiple="multiple" >
                                {{--<option value="">Please select a featured artist</option>--}}
                                {{--@foreach($artists as $artist)
                                    @if($data && isset($data->featuredArtists))
                                        @foreach($data->featuredArtists as $feArtist)
                                            <option value="{{$artist->artistId}}" @if($feArtist->artistId == $artist->artistId) selected @endif>{{$artist->name}}</option>
                                        @endforeach
                                    @endif
                                @endforeach--}}
                                @if($data)
                                    @foreach($data->featuredArtists as $artist)
                                        <option value="{{$artist->artistId}}" selected>{{$artist->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="primaryCategory" class="col-sm-3 col-lg-3 control-label">Primary Category*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="primaryCategory" name="primary_category"
                                                               class="form-control select-simple" required>
                                <option value="">Please select a primary category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->categoryId}}" @if($data && $data->categoryId == $category->categoryId) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Sub Category</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="subCategory" name="sub_category"
                                                               class="form-control select-simple">
                                <option value="" >Please select a SubCategory</option>
                                @foreach($sub_category as $category)
                                    <option value="{{$category->categoryId}}" @if($data && $data->sub_categories == $category->categoryId) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Mood*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="mood" name="moods[]" class="form-control select-simple"
                                    multiple  required>
                                {{--<option value="" selected>Please select a mood</option>--}}

                               {{-- @foreach($moods as $mood)
                                    @if($data AND isset($data->mood))
                                        @foreach($data->mood as $itm)
                                            <option value="{{$mood->id}}" @if($itm->id == $mood->id) selected @endif>{{$mood->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="{{$mood->id}}">{{$mood->name}}</option>
                                    @endif
                                @endforeach--}}
                                @foreach($moods as $mood)
                                    @if(in_array($mood->id, $mood_ids))
                                        <option value="{{$mood->id}}" selected>{{$mood->name}}</option>
                                    @else
                                        <option value="{{$mood->id}}" >{{$mood->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Song Genre*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="songGenre" name="song_genres[]" class="form-control select-simple"
                                                               multiple required>
                                {{--<option value="">Please select a song genre</option>--}}
                               {{-- @foreach($genres as $genre)
                                    @if($data AND isset($data->genres))
                                        @foreach($data->genres as $itm)
                                            <option value="{{$genre->GenreID}}" @if($itm->GenreID == $genre->GenreID) selected @endif>{{$genre->Name}}</option>
                                        @endforeach
                                    @else
                                        <option value="{{$genre->GenreID}}">{{$genre->Name}}</option>
                                    @endif
                                @endforeach--}}
                                @foreach($genres as $genre)
                                    @if(in_array($genre->GenreID, $genreIds))
                                        <option value="{{$genre->GenreID}}" selected>{{$genre->Name}}</option>
                                    @else
                                        <option value="{{$genre->GenreID}}" >{{$genre->Name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Lyricist*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="lyrics" name="lyrics" class="form-control select-simple" required>
                                <option value="">Please select a lyricist</option>
                                @if($data AND $data->writer)
                                    <option value="{{$data->writer->writerId}}" selected >{{$data->writer->name}}</option>
                                {{--@foreach($data->writer as $lyric)
                                    <option value="{{$lyric->writerId}}" @if($data && $lyric->writerId == $data->writerId) selected @endif>{{$lyric->name}}</option>
                                @endforeach--}}
                                    @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="composer" class="col-sm-3 col-lg-3 control-label">Composer/Music Director*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="composer" name="composer" class="form-control select-simple"
                                                               required>
                                <option value="">Please select a composer</option>
                                @if($data AND $data->composer)
                                    <option value="{{$data->composer->id}}" selected>{{$data->composer->name}}</option>
                                @endif

                               {{-- @foreach($song_composers as $composer)
                                    <option value="{{$composer->id}}" @if($data && $data->composerId == $composer->id) selected @endif>{{$composer->name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Song Publisher *</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="song_publisher" name="song_publisher" class="form-control select-simple"
                                    required>
                                <option value="">Please select a composer</option>
                                @if($data AND $data->publisher)
                                    <option value="{{$data->publisher->publisherId}}" selected>{{$data->publisher->name}}</option>
                                @endif
                            </select>
                            {{--<input type="text" class="form-control" name="song_publisher"
                                   value="{{$data ? $data->song_publisher : ''}}" required>--}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Project*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="project" name="projects[]" class="form-control select-simple"
                                                               multiple="multiple" required>
                                {{--<option value="">Please select a project</option>--}}
                                {{--@foreach($projects as $project)
                                    @if($data AND isset($data->projects))
                                        @foreach($data->projects as $itm)
                                            <option value="{{$project->id}}" @if($itm->id == $project->id) selected @endif>{{$project->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @endif
                                @endforeach--}}
                                @foreach($projects as $project)
                                    @if(in_array($project->id, $projectIds))
                                        <option value="{{$project->id}}" selected>{{$project->name}}</option>
                                    @else
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Product*</label>
                        <div class="col-sm-7 col-lg-6">
                            <select id="product" name="products[]" class="form-control select-simple"
                                                               multiple="multiple">
                                {{--<option value="">Please select a product</option>--}}
                               {{-- @foreach($products as $product)
                                    @if($data AND isset($data->products))
                                        @foreach($data->products as $itm)
                                            <option value="{{$product->id}}" @if($itm->id == $product->id) selected @endif>{{$product->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="{{$product->id}}" >{{$product->name}}</option>
                                    @endif
                                @endforeach--}}
                                @foreach($products as $product)
                                    @if(in_array($product->id, $productIds))
                                        <option value="{{$product->id}}" selected>{{$product->name}}</option>
                                    @else
                                        <option value="{{$product->id}}" >{{$product->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group"><label class="col-sm-3 col-lg-3 control-label">(P) Line*</label>
                        <div class="col-sm-7 col-lg-6"><input type="text" class="form-control"
                                                              value="{{$data ? $data->line : ''}}" name="p" required></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Uploaded Date*</label>
                        <div class="add-padding input-group col-sm-7 col-lg-6">
                            <input type="text" id="uploadedDate" class="form-control boot-date"
                                   value="{{$data ? $data->uploaded_date : ''}}" name="uploaded_date" autocomplete="off" >
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>

                    <div  class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Release Date*</label>
                        <div class="add-padding input-group col-sm-7 col-lg-6">

                            <input type="text" id="releaseDate" class="form-control boot-date" name="release_date" value="{{$data ? $data->release_date : ''}}" autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">End Date*</label>
                        <div class="add-padding input-group col-sm-7 col-lg-6">
                            <input type="text" id="endDate" class="form-control boot-date"
                                   value="{{($data AND $data->end_date ) ?  $data->end_date : '2999-12-12'}}" name="end_date" autocomplete="off" >
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-lg-3 control-label">Search Tags</label>
                        <div class="col-sm-7 col-lg-6">
                            <select class="select-simple-tag form-control" name="tags[]" multiple="multiple">
                                @if(isset($data->search_tag) AND is_array($data->search_tag))
                                    <?php foreach ($data->search_tag  as $key => $value):
                                        echo '<option value="'.$value.'" selected="selected">'.$value.'</option>';
                                    endforeach ?>
                                @endif
                            </select>

                            {{--<input type="text" class="form-control"
                                   value="{{$data ? $data->search_tag : ''}}" name="tags" >--}}
                        </div>
                    </div>
                    <input hidden id="product_id" name="product_id">

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button class="btn btn-default" type="button" onclick="cancelRedirect()">Cancel</button>
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
    <script src="{{asset('assets/front/js/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/back/js/jquery-validation-extension.js')}}"></script>
    <script type="text/javascript">
        let product_id = '';
        let product_type = '';
        $(document).ready(function () {


            let url_string = window.location.href;
            let url_new = new URL(url_string);
             product_id = url_new.searchParams.get("product_id");
             product_type = url_new.searchParams.get("type");


            if (product_id)
                $("#product_id").val(product_id);

            $('.select-simple-tag').select2({
                tags: true,
               // multiple: true,
                tokenSeparators: [','],
            }).on('select2:open', function (e) {
                $('.select2-container--open .select2-dropdown--below').css('display','none');
            });


            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            let url = '{{url('admin/artists/search')}}';
            $('#primaryArtists').select2({
                placeholder: "Please select a primary artist",
                tokenSeparators: [','],
                tags: true,
                minimumInputLength: 2,
                multiple: true,
                ajax: {
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return  'term='+params.term; /*JSON.stringify({
                            term: params.term
                        });*/
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item, i) {
                                return {
                                    text: item.name,
                                    id: item.artistId
                                }
                            })
                        };
                    },
                    cache: true
                },
            });

            $('#featuredArtists').select2({
                placeholder: "Please select a featured artist",
                tokenSeparators: [','],
                tags: true,
                minimumInputLength: 2,
                multiple: true,
                ajax: {
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return  'term='+params.term; /*JSON.stringify({
                            term: params.term
                        });*/
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item, i) {
                                return {
                                    text: item.name,
                                    id: item.artistId
                                }
                            })
                        };
                    },
                    cache: true
                },
            });
            $('#primaryCategory').select2({
                placeholder: "Please select a primary category",
            });
            $('#subCategory').select2({
                placeholder: "Please select a sub category",
            });
            $('#mood').select2({
                placeholder: "Please select a mood",
                multiple: true,
            });
            $('#songGenre').select2({
                placeholder: "Please select a song genre",
            });
            let lyricsUrl = '{{url('admin/lyricists/search')}}';
            $('#lyrics').select2({
                placeholder: "Please select a lyricist",
                tokenSeparators: [','],
                tags: true,
                minimumInputLength: 2,
                ajax: {
                    type: "GET",
                    url: lyricsUrl,
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return  'term='+params.term; /*JSON.stringify({
                            term: params.term
                        });*/
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item, i) {
                                return {
                                    text: item.name,
                                    id: item.writerId
                                }
                            })
                        };
                    },
                    cache: true
                },
            });
            let composerUrl = '{{url('admin/song-composers/search')}}';
            $('#composer').select2({
                placeholder: "Please select a composer",
                tokenSeparators: [','],
                tags: true,
                minimumInputLength: 2,
                ajax: {
                    type: "GET",
                    url: composerUrl,
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return  'term='+params.term; /*JSON.stringify({
                            term: params.term
                        });*/
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item, i) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
            });
            let publisherUrl = '{{url('admin/song/search-publisher')}}';
            $('#song_publisher').select2({
                placeholder: "Please select a publisher",
                minimumInputLength: 2,
                ajax: {
                    type: "GET",
                    url: publisherUrl,
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return  'term='+params.term; /*JSON.stringify({
                            term: params.term
                        });*/
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item, i) {
                                return {
                                    text: item.name,
                                    id: item.publisherId
                                }
                            })
                        };
                    },
                    cache: true
                },
            });
            $('#project').select2({
                placeholder: "Please select a project",
            });
            $('#product').select2({
                placeholder: "Please select a product",
            });

            //$('#releaseDate').datepicker();
            $('.boot-date').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true
            });
            //$('#endDate').datepicker();


            $("#form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    isbc_code: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    'primary_artist[]': {
                        required: true
                    },
                    /*featured_artists: {
                        required: true
                    },*/
                    primary_category: {
                        required: true
                    },
                    song_genres: {
                        required: true
                    },
                    moods: {
                        required: true
                    },
                    /*sub_category: {
                        required: true
                    },*/
                    lyrics: {
                        required: true
                    },
                    composer: {
                        required: true
                    },
                    song_publisher: {
                        required: true
                    },
                    'products[]': {
                        required: true
                    },
                    p: {
                        required: true
                    },
                    uploaded_date: {
                        required: true
                    },
                    release_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    },

                },
                messages: {},
                errorPlacement: function (error, element) {
                    if (element.hasClass('select-simple')) {
                        element.next().after(error);
                    } else if (element.hasClass('after-error-placement')) {
                        element.parent().parent().after(error);
                    } else {
                        element.parent().after(error);
                    }
                },
                submitHandler: function (form) {


                        $(".submit-btn-loader").css('display', 'block');
                        $("#submit-banner").prop('disabled', true);
                        $("#cancel-banner").prop('disabled', true);
                        console.log('submit');
                        form.submit();
                }
            });

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

        $('#primaryCategory').on('change', function () {

            $('#subCategory').find('option').remove();

            var url = '{{ url("admin/song/category/{id}/subcategory")}}';
            url = url.replace('{id}', this.value);
            $.ajax({
                method: "GET",
                url: url
            }).done(function (data) {
                /*if (data && data.length) {
                    for (var i = 0; i < data.length; i++) {
                        $('#subCategory').append($('<option>', {
                            value: data[i].categoryId,
                            text: data[i].name
                        }));
                    }
                }*/
                data.unshift({categoryId : '', name :'Please select a sub category'});
                let option = $.map(data, function (obj) {
                    obj.id = obj.categoryId;
                    obj.text = obj.name;

                    return obj;
                });
                $("#subCategory").select2({
                    placeholder: "Please select a sub category",
                    data: option
                });
            });
        });


    </script>
@stop