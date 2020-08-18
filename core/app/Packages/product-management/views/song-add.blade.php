@extends('layouts.back.master') @section('current_title','Existing Song/Add')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/plugins/datapicker/datepicker3.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/back/css/song.management.css')}}"/>

    <style type="text/css">
        #searching-gif{
            height: 30px;
            margin-left: 15px;
        }
    </style>
@stop
@section('page_header')
    <div class="col-lg-9">
        <h2>Product Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>
            <li class="active">
                <strong>PRODUCT / ADD</strong>
            </li>
        </ol>
    </div>
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12 margins">
            <div class="ibox-content">


                <form class="form-horizontal" id="form" style="margin-top: 15px" onsubmit="return false">
                    <input type="hidden" value="{{$id}}" id="productId">
                    <div style="margin-bottom: 40px;">
                        <div class="form-inline">
                            <label class="control-label" style="margin-right: 20px">Search By</label>
                            <div class="checkbox-inline">
                                <input type="radio" class="form-check-input" id="diabetes" name="type" checked="checked" value="artist">
                                <label class="form-check-label">Artist</label>
                            </div>
                            <div class="checkbox-inline">
                                <input type="radio" class="form-check-input" id="heartProblems" name="type" value="genre">
                                <label class="form-check-label">Genre</label>
                            </div>
                            <div class="checkbox-inline">
                                <input type="radio" class="form-check-input" id="hypertension" name="type" value="product">
                                <label class="form-check-label">Product/Album Name</label>
                            </div>
                            <div class="checkbox-inline">
                                <input type="radio" class="form-check-input" id="hypertension" name="type" value="category">
                                <label class="form-check-label">Category</label>
                            </div>
                            <div class="checkbox-inline">
                                <input type="radio" class="form-check-input" id="hypertension" name="type" value="isrc">
                                <label class="form-check-label">ISRC Code</label>
                            </div>
                            <div class="checkbox-inline">
                                <input type="radio" class="form-check-input" id="by-name" name="type" value="name">
                                <label for="by-name" class="form-check-label">Song Name</label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group form-inline" style="margin-top: 30px; margin-bottom: 50px">
                        <label class="control-label" style="margin-right: 35px; margin-left: 10px">Search </label>
                        <input type="text" id="filterText" class="form-control" name="type" required>
                        <button id="btnFilter" class="btn btn-info" type="button">Search</button>
                        <img id="searching-gif" style="" src="{{url('assets/back/img/loading3.jpg')}}">
                    </div>


                    <button type="button" id="addSelected" onclick="confirmAddSongs()" class="btn btn-info" style="float: right;">Add Selected</button>
                    <table id="example1" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="add-select-all"></th>
                            <th>Id</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Artist</th>
                            <th>Genre</th>
                            <th>Category</th>
                            <th>Music By</th>
                            <th>Publisher</th>
                            <th width="1%">Action</th>
                        </tr>
                        </thead>
                    </table>

                    <div style="margin-top: 30px">
                        <button id="removeSelected" onclick="confirmRemoveSongs()" class="btn btn-info" style="float: right;">Remove Selected</button>
                        <table id="example2" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="remove-select-all"></th>
                                <th>Id</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Artist</th>
                                <th>Genre</th>
                                <th>Category</th>
                                <th>Music By</th>
                                <th>Publisher</th>
                                <th width="1%">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <a class="btn btn-default" href="{{$url}}" style="width: 100px; margin-right: 10px;">Back</a>
                            <button class="btn btn-primary" type="button" onclick="saveSongs()" style="width: 100px;">Next</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
@section('js')

    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/back/js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript">
        let table;
        let table2;
        $(document).ready(function(){

            table=$('#example1').DataTable( {
                "ajax": {
                    "url" : '{{url('admin/playlist/song/list')}}',
                    "data": function ( d ) {
                        d.type = $('input[name="type"]:checked').val();
                        d.text = $('#filterText').val();
                    }
                },
                processing: true,
                serverSide: true,
                bFilter:false,
                "columnDefs": [
                    { "searchable": false, "targets": [-1, -10] },
                    { "orderable": false, "targets": [-1, -10] }
                ],
                buttons: [

                ],
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "autoWidth": false,
                fnDrawCallback:function (oSettings) {
                    console.log("after table create");
                    // console.log("worked");
                    $('#btnFilter').removeAttr("disabled");
                    $("#searching-gif").hide();
                    var elems = Array.prototype.slice.call(document.querySelectorAll('.addSong'));

                    elems.forEach(function(html) {
                        html.onclick = function () {
                            var row = table.row( $(this).parents('tr') ).data();
                            addToSelectedTable(row);
                        }
                    });
                }
            });

            table2 = $('#example2').DataTable({
                bFilter: false,
                "columnDefs": [
                    { "searchable": false, "targets": [-1, -10] },
                    { "orderable": false, "targets": [-1, -10] }
                ],
            });

        });

        $('#add-select-all').on("change", function() {
            if ($(this).is(":checked")) {
                $('#example1').find('input[type=checkbox]').each(function () {
                    $(this).prop("checked", true);
                });
            } else {
                $('#example1').find('input[type=checkbox]').each(function () {
                    $(this).prop("checked", false);
                });
            }
        });
        $('#remove-select-all').on("change", function() {
            if ($(this).is(":checked")) {
                $('#example2').find('input[type=checkbox]').each(function () {
                    $(this).prop("checked", true);
                });
            } else {
                $('#example2').find('input[type=checkbox]').each(function () {
                    $(this).prop("checked", false);
                });
            }
        });

        function addToSelectedListToTable() {
            var checked = $('#example1').find('input[type=checkbox]:checked').length;
            if (checked > 0) {
                if (table.rows().count() > 0) {
                    table.rows().every(function (index, element) {
                        var row = $(this.node());
                        var col0 = row.find('td:first-child input[type="checkbox"]');
                        if (col0.is(':checked')) {
                            var matching = false;

                            if (table2.rows().count() > 0) {
                                table2.rows().every(function (index, element) {
                                    var row2 = $(this.node());


                                    if (row.find('td').eq(1).text() == row2.find('td').eq(1).text()) {
                                        console.log("HERE");
                                        matching = true;
                                    }
                                });
                            }
                            if (!matching) {
                                table2.row.add(['<center><input type=\'checkbox\'></center>',
                                    row.find('td').eq(1).text(),
                                    row.find('td').eq(2).text(),
                                    row.find('td').eq(3).text(),
                                    row.find('td').eq(4).text(),
                                    row.find('td').eq(5).text(),
                                    row.find('td').eq(6).text(),
                                    row.find('td').eq(7).text(),
                                    row.find('td').eq(8).text(),
                                    '<a href="#" onclick="removeItem(this)" class="btn btn-sm btn-info">Remove</a>']).draw();
                            }

                        }
                    });
                }
                $("#add-select-all").prop("checked", false);
            } else {
                toastr.error("Please select a song");
            }
        }

        function addToSelectedTable(rowData) {

            var matching = false;

            if (table2.rows().count() > 0) {
                table2.rows().every(function(index, element) {
                    var row = $(this.node());


                    if (row.find('td').eq(1).text() == rowData[1]) {
                        matching = true;
                    }
                });
            }
            if (!matching) {
                table2.row.add(['<center><input type=\'checkbox\'></center>', rowData[1], rowData[2], rowData[3], rowData[4], rowData[5],
                    rowData[6], rowData[7], rowData[8], '<a href="#" onclick="removeItem(this)" class="btn btn-sm btn-info">Remove</a>']).draw();
            }
        }

        function removeItem(row) {
            var rowData = table2.row( $(row).parents('tr') ).data();
            table2.row( $(row).parents('tr') ).remove().draw();
        }


        function removeSelectedList() {
            var checked = $('#example2').find('input[type=checkbox]:checked').length;
            if (checked > 0) {
                let arr = [];
                if (table2.rows().count() > 0) {
                    table2.rows().every(function (index, element) {
                        var row = $(this.node());
                        var col0 = row.find('td:first-child input[type="checkbox"]');
                        if (col0.is(':checked')) {
                            arr.push($(row));
                        }
                    });
                }

                if (arr.length > 0) {
                    table2.rows(arr).remove().draw();
                }
                $("#remove-select-all").prop("checked", false);
            } else {
                toastr.error("Please select a song");
            }

        }

        $("#searching-gif").hide();

        $("#btnFilter").click(function (e) {
            var txt = $('#filterText').val().trim();
            if (txt !== "" && txt.length >= 3) {
                $("#btnFilter").attr("disabled", true);
                $("#searching-gif").show();
                e.preventDefault();
                $('#example1').DataTable().draw(true);
                // $('#btnFilter').removeAttr("disabled");
                // $("#searching-gif").hide();
            } else {
                $('#btnFilter').removeAttr("disabled");
                $("#searching-gif").hide();
                toastr.error("Please enter a keyword with minimum 3 letters");
            }
        });

        $("#btnFilter").click(function(){
            console.log('warning2');
            // $("#btnFilter").attr("disabled", true);
            // $("#searching-gif").show();
        });

        function saveSongs() {
            var itemArray = [];

            table2.rows().every(function(index, element) {
                var row = $(this.node());

                itemArray.push(row.find('td').eq(1).text());

                console.log(itemArray);
            });

            if (itemArray.length > 0) {
                var productId = $('#productId').val();
                $.ajax({
                    method: "POST",
                    url: '{{url('admin/products/add/step-2')}}',
                    data:{  'songs' : itemArray, 'product_id' : productId  }
                }).done(function( msg ) {
                    window.location = "step-3";
                });
            } else {
                toastr.error("Please Add Songs Before Proceed To Next Step");
            }

        }

        function confirmAddSongs() {
            swal({
                title: "Are you sure?",
                text:"Add selected songs to the list",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Add Selected"

            }).then(function (isConfirm) {
                if (isConfirm.hasOwnProperty("value") && isConfirm.value === true) {
                    addToSelectedListToTable()
                } else if (isConfirm.hasOwnProperty("dismiss") && isConfirm.dismiss === "cancel") {
                    swal("Cancelled", "Cancelled the adding songs", "error");
                }
            });
        }

        function confirmRemoveSongs() {
            swal({
                title: "Are you sure?",
                text:"Remove selected songs from the list",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Remove Selected"

            }).then(function (isConfirm) {
                if (isConfirm.hasOwnProperty("value") && isConfirm.value === true) {
                    removeSelectedList()
                } else if (isConfirm.hasOwnProperty("dismiss") && isConfirm.dismiss === "cancel") {
                    swal("Cancelled", "Cancelled the removing songs", "error");
                }
            });
        }


    </script>
@stop