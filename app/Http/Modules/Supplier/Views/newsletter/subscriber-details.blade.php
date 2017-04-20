@extends('Supplier/Layouts/supplierlayout')

@section('title', 'Manage Giftcertificate') {{--TITLE GOES HERE--}}

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection


@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    {{--DISPLAY ALL CATEGORIES, USING SERVER SIDE DATATABLES--}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3>Notification Table:</h3></br></br>

                    <div class="panel-control">
                        <a href="/supplier/add-newsletter"><i class="fa fa-plus"></i>&nbsp;Add Newsletter</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-hover" style="width: 100%"
                           id='datatable_filter'>
                        <thead>
                        <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Newsletter Email</th>
                            <th class="text-center">Subscription Date Code</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rowCount = 1; ?>
                        @foreach($subscriberDetail as $key => $val)
                            <tr id="filter<?php echo $val->news_id?>">
                                <td class="text-center"><?php echo $rowCount++; ?></td>
                                <td class="text-center">{{$val->sub_email}}</td>
                                <td class="text-center">{{$val->sub_date}}</td>
                                <td class="center" style="text-align: center;">
                                    @if($val->sub_status == 'A')
                                        <button class="btn btn-success changestatus"
                                                data-id="<?php echo $val->news_id ?>">
                                            Active
                                        </button>
                                    @elseif($val->sub_status == 'I')
                                        <button class="btn btn-danger changestatus"
                                                data-id="<?php echo $val->news_id ?>">
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
        {{--PAGE SCRIPTS GO HERE--}}

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
                var newsletterId = $(this).attr('data-id');
                var newsletterStatus = $(this).val();
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
                        url: '/supplier/newsletter-ajax-handler',
                        type: 'GET',
                        datatype: 'json',
                        data: {
                            method: 'changeNewsletterStatus',
                            newsletterId: newsletterId,
                            newsletterStatus: status
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
