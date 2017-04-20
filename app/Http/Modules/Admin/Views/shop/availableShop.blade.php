@extends('Admin/Layouts/adminlayout')

@section('title', trans('Available Shop')) {{--TITLE GOES HERE--}}

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
                    <a href="/admin/add-new-shop" class="btn btn-circle btn-success">Add new shop</a>
                    <table id="pending_shop" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Shop Name</th>
                            <th>Owned By</th>
                            <th>Action</th>
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
            $('#pending_shop').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "/admin/shop-ajax-handler",
                    data: {
                        method: 'AvailableShop'
                    },
                },

            });

            $(document.body).on('click', '.customer-status', function () {
                var obj = $(this);
                var shop_id = $(this).attr('data-id');
                var supplierId = $(this).attr('data_supplier_id');
                var shopMetaId = $(this).attr('data_shopMeta_id');
                var status = 0;
                if (obj.hasClass('btn-success')) {
                    status = 2;
                } else if (obj.hasClass('btn-danger')) {
                    status = 1;
                }
                if (status == 1 || status == 2) {
                    $.ajax({
                        url: '/admin/shop-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'updateShopStatus',
                            shopMetaId: shopMetaId,
                            supplierId: supplierId,
                            value: status
                        },
                        success: function (response) {
                            response = $.parseJSON(response);
                            if (response == 1) {
                                toastr["success"]("changed successfully");
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

            $(document.body).on('click', '.delete-shop', function () {
                //   alert("cfh");
                var obj = $(this);
                var ShopId = $(this).attr('data-cid');
                //alert(UserId);

                $.ajax({
                    url: '/admin/shop-ajax-handler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'changeShopStatus',
                        ShopId: ShopId,
                        status: 4
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['status']]("Shop Deleted Successfully");
                        if (response['status'] == "success") {
                            obj.parents("tr").remove();
                        }
                    }
                });

            });
        });


    </script>
@endsection
