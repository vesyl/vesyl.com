@extends('Admin/Layouts/adminlayout')

@section('title', trans('message.pending_requests')) {{--TITLE GOES HERE--}}

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
                    <a href="/admin/add-new-supplier" class="btn btn-success"><i class="fa fa-plus "></i>&nbsp;Add New
                        Supplier
                    </a>
                    <table id="pending_supplier" class="display" cellspacing="0" width="100%">
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
            $('#pending_supplier').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "/admin/supplier-ajax-handler",
                    data: {
                        method: 'pendingSupplier'
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
        $(document.body).on('click', '.productapproved', function () {
            var obj = $(this);
            var id = $(this).attr('data-id');

            if (confirm("Do you want to Change the Supplier !") == true) {
                $.ajax({
                    url: '/admin/supplier-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'updateSupplier',
                        SupplierId: id,
                        Status: '1'

                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        window.location.reload();
                        toastr[response['productpending']](response['msg']);
                        window.location.reload();
                    }
                });
            }

        });
        $(document.body).on('click', '.productrejected', function () {
            var obj = $(this);
            var id = $(this).attr('data-id');

            if (confirm("Do you want to Change the Supplier !") == true) {
                $.ajax({
                    url: '/admin/supplier-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'updateSupplier',
                        SupplierId: id,
                        Status: '4'

                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        window.location.reload();
                        toastr[response['productpending']](response['msg']);
                        window.location.reload();
                    }
                });
            }

        });

    </script>
@endsection
