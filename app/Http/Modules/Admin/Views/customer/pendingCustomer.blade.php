@extends('Admin/Layouts/adminlayout')

@section('title', trans('message.pending_customer')) {{--TITLE GOES HERE--}}

@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}


    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

@endsection

@section('content')

    {{--PAGE CONTENT GOES HERE--}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <a href="/admin/add-new-customer" class="btn btn-success"><i class="fa fa-plus "></i>&nbsp;{{trans('message.add_new_customer')}}
                    </a>
                    <table id="pending_customer" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>UserName</th>
                            <th>Email</th>
                            <th>Register Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('pagejavascripts')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#pending_customer').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "/admin/customer-ajax-handler",
                    data: {
                        method: 'pendingCustomer'
                    },
                },
//                columns:  { data: 'id', name: 'id' },
//            { data: 'name', name: 'name' },
//            { data: 'email', name: 'email' },
//            { data: 'created_at', name: 'created_at' },
//            { data: 'updated_at', name: 'updated_at' }
//            ]


            });
        });


        $(document.body).on('click', '.customer-status', function () {

            var obj = $(this);
            var UserId = $(this).attr('data-id');
            var status = 0;
            if (obj.hasClass('btn-success')) {
                status = 2;
            } else if (obj.hasClass('btn-danger')) {
                status = 1;
            }
            if (status == 1 || status == 2) {
                $.ajax({
                    url: '/admin/customer-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'changeCustomerStatus',
                        UserId: UserId,
                        status: status
                    },
                    success: function (response) {
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

            $(document.body).on('click', '.delete-user', function () {

                var obj = $(this);
                var UserId = $(this).attr('data-cid');

                $.ajax({
                    url: '/admin/customer-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'deleteUserStatus',
                        UserId: UserId,
                    },
                    success: function (response) {
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

            });

        });
        $(document.body).on('click', '.productapproved', function () {
            var obj = $(this);
            var id = $(this).attr('data-id');

            if (confirm("Do you want to Change the Customer !") == true) {
                $.ajax({
                    url: '/admin/customer-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'updateCustomer',
                        CustomerId: id,
                        Status: '1'

                    },
                    success: function (response) {
                        window.location.reload();
                        response = $.parseJSON(response);
                        toastr[response['productpending']](response['msg']);
                        window.location.reload();
                    }
                });
            }

        });
        $(document.body).on('click', '.productrejected', function () {
            var obj = $(this);
            var id = $(this).attr('data-id');

            if (confirm("Do you want to Change the Customer !") == true) {
                $.ajax({
                    url: '/admin/customer-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'updateCustomer',
                        CustomerId: id,
                        Status: '4'

                    },
                    success: function (response) {
                        window.location.reload();
                        response = $.parseJSON(response);
                        toastr[response['productpending']](response['msg']);
                        window.location.reload();
                    }
                });
            }

        });

    </script>
@endsection
