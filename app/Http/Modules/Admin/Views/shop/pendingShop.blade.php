@extends('Admin/Layouts/adminlayout')

@section('title', trans('Pending Shop')) {{--TITLE GOES HERE--}}

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
                    <table id="pending_shop" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Shop Name</th>
                            <th>Owned By</th>
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
                        method: 'pendingShop'
                    },
                },

            });

            $(document.body).on('change', '.shop-status', function () {
                var obj = $(this);
                var ShopId = $(this).attr('data-id');
                var status = 0;
                status = $(obj).val();
                if (status == 1 || status == 3) {
                    $.ajax({
                        url: '/admin/shop-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'changeShopStatus',
                            ShopId: ShopId,
                            status: status
                        },
                        success: function (response) {
                            response = $.parseJSON(response);
                            toastr[response['status']](response['msg']);
                            if (response['status'] == "success") {
                                setTimeout(function(){
                                    window.location.reload();
                                }, 3000);
                            }
                        }
                    });
                }
            });

        });


    </script>
@endsection
