@extends('Supplier/Layouts/supplierlayout')

@section('title', 'Pending Reviews') {{--TITLE GOES HERE--}}

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection




<style>

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>


@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    {{--DISPLAY ALL CATEGORIES, USING SERVER SIDE DATATABLES--}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">

                <div class="panel-body">
                    <h1>Product Reviews</h1>
                    <table class="table table-hover" style="width: 100%"
                           id='productpending_review'>
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">User Name</th>
                            <th class="text-center">Product Name</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">action</th>
                        </tr>
                        </thead>


                    </table>

                </div>
                <div class="panel-body">
                    <h1>Shop Reviews</h1>
                    <table class="table table-hover" style="width: 100%"
                           id='shoppending_review'>
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">User Name</th>
                            <th class="text-center">Product Name</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">action</th>
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
        $(function () {

            $('#productpending_review').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    url: "/supplier/reviews",
                    data: {
                        method: 'getproductpendingreviews'
                    },
                    columns: [
                        {data: 'id', name: 'review_id'},
                        {data: 'name', name: 'name'},
                        {data: 'review_type', name: 'review_type'},
                        {data: 'review_rating', name: 'review_rating'},
                        {data: 'review_details', name: 'review_details'},
                        {data: 'review_status', name: 'review_status'},
                        {data: 'action', name: 'action'}
                    ]
                }
            });
            });




        $(function () {

            $('#shoppending_review').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    url: "/supplier/reviews",
                    data: {
                        method: 'getshoppendingreviews'
                    },
                    columns: [
                        {data: 'id', name: 'review_id'},
                        {data: 'name', name: 'name'},
                        {data: 'review_type', name: 'review_type'},
                        {data: 'review_rating', name: 'review_rating'},
                        {data: 'review_details', name: 'review_details'},
                        {data: 'review_status', name: 'review_status'},
                        {data: 'action', name: 'action'}
                    ]
                }
            });
        });
        $(document.body).on('click', '.productapproved', function () {
            var obj = $(this);
            var ReviewsId = $(this).attr('data-id');

            if (confirm("Do you want to Change the Reviews !") == true) {
                $.ajax({
                    url: '/supplier/reviews',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getupadetereviews',
                        ReviewsId: ReviewsId,
                        ReviewStatus: 'A'

                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['productpending']](response['msg']);
                        window.location.reload();
                    }
                });
            }

        });
        $(document.body).on('click', '.shopapproved', function () {
            var obj = $(this);
            var ReviewsId = $(this).attr('data-id');

            if (confirm("Do you want to Change the Reviews !") == true) {
                $.ajax({
                    url: '/supplier/reviews',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getupadetereviews',
                        ReviewsId: ReviewsId,
                        ReviewStatus: 'A'

                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['shoppending']](response['msg']);
                        window.location.reload();

                        console.log("came");
                    }
                });
            }

        });
        $(document.body).on('click', '.productrejected', function () {
            var obj = $(this);
            var ReviewsId = $(this).attr('data-id');

            if (confirm("Do you want to Change the Reviews !") == true) {
                $.ajax({
                    url: '/supplier/reviews',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getupadetereviews',
                        ReviewsId: ReviewsId,

                        ReviewStatus: 'R'

                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['productpending']](response['msg']);
                        window.location.reload();
                    }
                });
            }

        });
        $(document.body).on('click', '.shoprejected', function () {
            var obj = $(this);
            var ReviewsId = $(this).attr('data-id');

            if (confirm("Do you want to Change the Reviews !") == true) {
                $.ajax({
                    url: '/supplier/reviews',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'getupadetereviews',
                        ReviewsId: ReviewsId,

                        ReviewStatus: 'R'
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        toastr[response['shoppending']](response['msg']);
                        window.location.reload();

                        console.log("came");
                    }
                });
            }

        });
        $(document.body).on('click','.deleteApproved',function () {

            var obj = $(this);
            var ReviewsId = $(this).attr('data-id');


            if (confirm("Do you want to Delete !") == true) {
                $.ajax({
                    url: '/supplier/reviews',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        method: 'deletemethod',
                        ReviewsId: ReviewsId
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response) {


                            obj.parent().parent().remove();
                        }
                    }
                });
            }

        });



    </script>

@endsection