@extends('Admin/Layouts/adminlayout')

@section('title', 'Manage Options') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(empty($allOptions))
                <div style="text-align: center">
                    <span class="">Sorry, no product-options found.</span><br>
                    <a href="/admin/add-option">
                        <button class="btn btn-xs btn-success">Add new option</button>
                    </a>
                </div>
            @else
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h4 class="panel-title">All Options</h4>

                        <div class="panel-control">
                            <a href="/admin/add-option"><i class="fa fa-plus"></i>&nbsp;Add new option</a>
                        </div>
                    </div>

                    <!--todo Product attributes are quantifiable or descriptive aspects of a product (such as, color). For example, if you were to create an attribute for color, with the values of blue, green, yellow, and so on, you may want to apply this attribute to shirts, which you sell in various colors (you can adjust a price or weight for any of existing attribute values). You can add attributes to existing product on a product details page. -->
                    <div class="panel-body">
                        <table class="table table-hover" style="width: 100%"
                               id='optionTable'>
                            <thead>
                            <tr>
                                <th width="60%">Option name</th>
                                <th style="text-align: center">Action</th>
                                <th style="text-align: center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allOptions as $optionKey=>$optionValue)
                                <tr id="option-{{$optionValue->option_id}}">
                                    <td><a href="/admin/edit-option/{{$optionValue->option_id}}"  style="text-decoration: none;">{{$optionValue->option_name}}</a></td>
                                    <td style="text-align: center">
                                        <div role="group" class="btn-group ">
                                            <button aria-expanded="false" data-toggle="dropdown"
                                                    class="btn btn-default dropdown-toggle" type="button">
                                                <i class="fa fa-cog"></i>&nbsp;
                                                <span class="caret"></span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu">
                                                <li><a href="/admin/edit-option/{{$optionValue->option_id}}"><i
                                                                class="fa fa-pencil"
                                                                data-id="{{$optionValue->option_id}}"></i>&nbsp;Edit</a>
                                                </li>
                                                <li><a href="javascript:void(0);" class="delete-option"
                                                       data-id="{{$optionValue->option_id}}"><i class="fa fa-trash"></i>&nbsp;Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td style="text-align: center">
                                        @if($optionValue->status=='1')
                                            <button class="btn btn-success option-status"
                                                    data-id="{{$optionValue->option_id}}">Active
                                            </button>
                                        @elseif($optionValue->status=='2')
                                            <button class="btn btn-danger option-status"
                                                    data-id="{{$optionValue->option_id}}">
                                                Inactive
                                            </button>
                                        @elseif($optionValue->status=='3')
                                            <button class="btn btn-warning option-status"
                                                    data-id="{{$optionValue->option_id}}">Pending
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('pagejavascripts')
    <script>
        $(document).ready(function () {

            var table = $('#optionTable');
            var oTable = table.dataTable({
                "lengthMenu": [
                    [10, 20, 100, -1],
                    [10, 20, 100, "All"]
                ], "bAutoWidth": false,
                "bSort": false
//                "searching":false
//                "info":     false
            });

            $(document.body).on("click", ".option-status", function () {
                var obj = $(this);
                var optionId = $(this).attr('data-id');
                var status = 0;
                if (obj.hasClass('btn-success')) {
                    status = 2;
                } else if (obj.hasClass('btn-danger')) {
                    status = 1;
                }
                if (status == 1 || status == 2) {
                    $(document).ajaxStart($.blockUI);
                    $.ajax({
                        url: '/admin/option-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'changeOptionStatus',
                            optionId: optionId,
                            status: status
                        },
                        success: function (response) {
                            $(document).ajaxStop($.unblockUI);
                            response = $.parseJSON(response);
                            toastr[response['status']](response['msg']);
                            if (response['status'] == "success") {
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

            $(document.body).on("click", ".delete-option", function () {
                var obj = $(this);
                var optionId = $(this).attr('data-id');
                var x = confirm("Are you sure you want to proceed?");
                if (x) {
                    $(document).ajaxStart($.blockUI);
                    $.ajax({
                        url: '/admin/option-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'deleteOption',
                            optionId: optionId
                        },
                        success: function (response) {
                            $(document).ajaxStop($.unblockUI);
                            response = $.parseJSON(response);
                            toastr[response['status']](response['msg']);
                            if (response['status'] == 'success') {
                                var oTable = $('#optionTable').dataTable();
                                oTable.fnDeleteRow(document.getElementById('option-' + optionId));
                            }
                        }
                    });
                }
            });
        });

    </script>
    <script src="/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
@endsection
