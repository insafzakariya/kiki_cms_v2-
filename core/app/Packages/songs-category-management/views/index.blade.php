@extends('layouts.back.master') @section('current_title','SONG CATEGORY/View')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/Tree-Plugin-jQuery-jsTree/dist/themes/default/style.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/back/Tree-Plugin-jQuery-jsTree/dist/themes/default-dark/style.min.css')}}">
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

        .stage {
            display: none;
        }
        .stage.active {
            display: table-row;
        }
        .expand-ico {
            float: right;
            display: block;
            width: 20px;
            height: 20px;
            font-size: 15px;
            text-align: center;
            -webkit-transition: all .2s ease-out;
            transition: all .2s ease-out;
        }
        .selected .expand-ico {
            -ms-transform: rotate(90deg); /* IE 9 */
            transform: rotate(90deg);
        }
    </style>

@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Song Category Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>Song Category / View</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                    <div class="col-xs-12" style="margin-bottom: 20px;">
                        @if(\Sentinel::getUser()->hasAnyAccess(['songs-category.create', 'admin']))
                            <a href="{{ route('songs-category.create') }}" class="btn btn-primary">New Category</a>
                        @endif
                            @if(\Sentinel::getUser()->hasAnyAccess(['songs-category.edit', 'admin']))
                                <btn onclick="gotToEditUrl()" class="btn btn-success">Edit Category</btn>
                            @endif
                            @if(\Sentinel::getUser()->hasAnyAccess(['admin.songs-category.status.toggle', 'admin']))
                                <btn onclick="statusToggle()" class="btn btn-warning">Toggle Status</btn>
                            @endif
                    </div>
                    <div id="html" class="demo">
                        <ul>
                            @foreach($categories as $category)
                                <li data-jstree='{ "opened" : false }' id="{{$category->categoryId}}">
                                    @if($category->status == 1)
                                        {{ $category->name }}
                                    @else
                                        <del>{{ $category->name }}</del>
                                    @endif

                                    @if(count($category->childs))
                                        @include('SongsCategory::manageChild',['childs' => $category->childs])
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')

    <script type="text/javascript">
        $('#html').jstree({
            'core' : {
                "multiple" : false,
                'check_callback' : true,
                'themes' : {
                    'responsive' : false
                }
            },
            'plugins' : ['state','contextmenu','wholerow' ]
        })

        $('#html').on('ready.jstree', function () {
            $('#html').off("contextmenu.jstree", ".jstree-anchor");
        })
    </script>
    <script type="text/javascript">
        let table;

        function statusToggle() {
            const id = getSelectedCatId();
            if(id != null){
                confirmStatus(id);
            }
        }

        function gotToEditUrl() {
            const id = getSelectedCatId();
            if(id != null){
                let editUrl = '{{route('songs-category.edit', "cat_id")}}';

                window.location.href = editUrl.replace('cat_id', id);
            }
        }

        function getSelectedCatId() {
            try{
                return $('#html').jstree("get_selected")[0];
            }catch (e) {
                return null;
            }

        }

        function confirmStatusAction(id){
            let url = '{!! route('admin.songs-category.status.toggle')!!}';
            $.ajax({
                method: "POST",
                url: url.replace('%7Bsongs_category%7D', id),
            })
                .done(function( msg ) {
                    window.location.reload();
                });

        }
    </script>
@stop
