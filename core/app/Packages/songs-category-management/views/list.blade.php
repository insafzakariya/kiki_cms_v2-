@extends('layouts.back.master') @section('current_title','SONG CATEGORY/View')
@section('css')
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
                <strong>Song Category List</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')
    @if(\Sentinel::getUser()->hasAnyAccess(['admin.radio-channels.show', 'admin']))
        <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create">
            <a href="{{ route('songs-category.create') }}"><p class="plus">+</p></a>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">
                <div class="panel-body">
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoty</th>
                            <th>Description</th>
                            <th width="5%">Action</th>
                            <th width="1%">Active/ Deactivate</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr class="main-stage stage-1 parent-{{ $category->categoryId }}">
                                    <th onclick="expand(this)">
                                        {{ $category->categoryId }}
                                        <?php if (count($category->childs) > 0) { echo '<span class="expand-ico"><i class="fa fa-arrow-circle-right"></i></span>'; } ?>
                                    </th>
                                    <th>{{ $category->name }}</th>
                                    <th>{{ $category->description }}</th>
                                    <th width="5%">
                                    <div>
                                        <a href="{{route('songs-category.edit', $category->categoryId)}}"><span><i class="fa fa-edit"></i></span></a>
                                        <a href="#"><span><i class="fa fa-trash"></i></span></a>
                                    </div>
                                    </th>
                                    <th>
                                    <?php
                                        if($category->status == 1){
                                            echo '<center><a href="javascript:void(0)" form="noForm" class="blue song-category-status-toggle " data-id="'.$category->categoryId.'"  data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="fa fa-toggle-on"></i></a></><center>';
                                        }else{
                                            echo '<center><a href="javascript:void(0)" form="noForm" class="blue song-category-status-toggle " data-id="'.$category->categoryId.'"  data-toggle="tooltip" data-placement="top" title="Activate"><i class="fa fa-toggle-off"></i></a></><center>';
                                        }
                                    ?>
                                    </th>
                                </tr>
                                @if(count($category->childs) > 0)
                                    @include('SongsCategory::manageChild', ['childs' => $category->childs, 'stage' => 1, 'parentId' => $category->categoryId])
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        let table;
        $(document).ready(function(){
                $('.song-category-status-toggle').click(function(e){
                    e.preventDefault();
                    id = $(this).data('id');
                    confirmStatus(id);

                });
        });

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
        const expand = (val) => {
            var thisClass = $(val).parents('tr').attr('class');
            var mainClass = thisClass.split(' ');
            if(mainClass[0] !== 'main-stage') {
                var stageClass = thisClass.split(' ');
                var parentID = parseInt(stageClass[2].split('-')[1]);

                $('.parent-' + parentID).toggleClass('selected');
                $('.child-' + parentID).toggleClass('active');
            } else {
                var parentID = mainClass[2].split('-')[1];
                $('.parent-' + parentID).toggleClass('selected');
                $('.child-' + parentID).toggleClass('active');
            }
        }

    </script>
@stop
