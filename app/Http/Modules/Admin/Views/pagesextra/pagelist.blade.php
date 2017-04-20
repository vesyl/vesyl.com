

@extends('Admin/Layouts/adminlayout')

@section('title', 'Pages Lists') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection


@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    {{--DISPLAY ALL CATEGORIES, USING SERVER SIDE DATATABLES--}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3>Pages List</h3></br></br>

                    <div class="panel-control">
                        <a href="/admin/addnewpage"><i class="fa fa-plus"></i>&nbsp;Add New Pages</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-hover" style="width: 100%"
                           id='datatable_filter' >
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Page Name</th>
                            <th class="text-center">Page Title</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rowCount = 1; ?>
                        @foreach($returnData['pageDetail']->data as $key => $val)
                            <tr id="filter<?php echo $val->page_id?>">
                                <td class="text-center"><?php echo $rowCount++; ?></td>
                                <td class="text-center">{{$val->page_name}}</td>
                                <td class="text-center">{{$val->page_title}}</td>
                                <td class="text-center"><a
                                            href="/admin/edit-pageslist/{{$val->page_id}}"
                                            class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                </td>
                                <td class="center" style="text-align: center;">
                                    @if($val->page_status == 'A')
                                        <button class="btn btn-success changestatus"
                                                data-id="<?php echo $val->page_id ?>">
                                            Active
                                        </button>
                                    @elseif($val->page_status == 'I')
                                        <button class="btn btn-danger changestatus"
                                                data-id="<?php echo $val->page_id ?>">
                                            Inactive
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejavascripts')
    <script>
        {{--$(document).ready(function () {--}}
            {{--var oTable = $('#datatable_filter').DataTable({--}}
                {{--"processing": true,--}}
                {{--"serverSide": true,--}}
                {{--"ajax": {--}}
                    {{--url: "/admin/page-ajax-handler",--}}
                    {{--data: {--}}
                        {{--method: 'availablePages'--}}
                    {{--},--}}
                {{--},--}}
            {{--});--}}
        {{--});--}}


        $(document).ready(function () {
                var table = $('#datatable_filter');
                var oTable = table.dataTable({
                    "lengthMenu": [
                        [10, 20, 100, -1],
                        [10, 20, 100, "All"]
                    ], "bAutoWidth": false,
                    "bSort": false
//                "searching":false
//                "info":     false
                });
                $(document.body).on("click", ".changestatus", function () {
                    var pageId = $(this).attr('data-id');
                    var pagestatus = $(this).val();
                    var obj = $(this);
                    var giftId = $(this).attr('data-id');
                    var status = 'I';
                    if (obj.hasClass('btn-success')) {
                        status = 'I';
                    } else if (obj.hasClass('btn-danger')) {
                        status = 'A';
                    }
                    if (status == 'I' || status == 'A') {
                        $(document).ajaxStart($.blockUI);
                        $.ajax({
                            url: '/admin/page-ajax-handler',
                            type: 'GET',
                            datatype: 'json',
                            data: {
                                method: 'changePageStatus',
                                pageId: pageId,
                                pagestatus: status
                            },
                            success: function (response) {
                                $(document).ajaxStop($.unblockUI);
                                var res = $.parseJSON(response);
                                toastr[res['status']](res['msg']);
                                if (res['status'] == 'success') {
                                    if (obj.hasClass('btn-success')) {
                                        obj.removeClass('btn-success');
                                        obj.addClass('btn-danger');
                                        obj.text('Inactive');
                                    } else {
                                        obj.removeClass('btn-danger');
                                        obj.addClass('btn-success');
                                        obj.text('Active');
                                    }
                                }
                            }
                        });
                    }
                });

            });

    </script>
    <script src="/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
@endsection
