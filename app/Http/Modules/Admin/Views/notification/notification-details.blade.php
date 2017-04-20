@extends('Admin/Layouts/adminlayout')

@section('title', 'Notification Details') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection


@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    {{--DISPLAY ALL CATEGORIES, USING SERVER SIDE DATATABLES--}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <h3>Notification Table:</h3></br></br>
                    <table class="dynamicTable table table-striped table-bordered table-condensed notificationtable">
                        <thead>
                        <tr>
                            <th class="center">Sr No.</th>
                            <th>Receiver Name</th>
                            <!--<th style="width: 10%;">Review Type</th>-->
                            <th class="center">Sender Name</th>

                            <th style="width: 20%;">Message</th>
                            <th style="width: 10%;">Details</th>
                            <th style="width: 15%;">Send Date</th>
                            <th class="center">Status</th>
                            <th class="center">Delete</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if (isset($notificationDetails) && !empty($notificationDetails)) {
                        $row = 0;
                        foreach ($notificationDetails as $collectionData) { //print_r($collectionData);die;

                        ?>
                        <tr class="selectable" height='10px'>
                            <td class="center"><?php echo ++$row; ?></td>
                            <td>{{$collectionData->reciever}}</td>

                            <td><?php if ($collectionData->sender != '') {
                                    echo $collectionData->sender;
                                } else {
                                    print(Session::get('fs_user')['username']);
                                }?></td>


                            <td><?php echo $collectionData->message; ?></td>
                            <td>
                                <div class="container" style="width: 50px ">
                                    <button data-desc="{{$collectionData->description}}" type="button"
                                            class="btn btn-sm btn-default modaldescription" data-toggle="modal"
                                            data-target="#mymodel"><i class="fa fa-expand"></i></button>
                                </div>
                            </td>
                            <td class="center"><span
                                        class="small">({{$collectionData->send_date}}
                                    )</span></td>
                            <td class="center">
                                <?php if ($collectionData->notification_status == 'U') {
                                    echo "Unseen";
                                }
                                if ($collectionData->notification_status == 'S') {
                                    echo "Seen";
                                }
                                if ($collectionData->notification_status == 'F') {
                                    echo "fail";
                                }
                                ?>
                            </td>
                            <td>
                                <button id="{{$collectionData->notification_id}}"
                                        class="btn btn-danger remove1"><i></i>Delete
                                </button>
                            </td>
                        </tr>
                        <?php

                        }
                        }
                        ?>
                        </tbody>
                    </table>

                    <div class="modal fade" id="mymodel" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Description of Message:</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="message">Message:</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="5" cols="25" id="message" disabled
                                                          style="cursor: default"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div align="right">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejavascripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $(document).ready(function () {
                $('.notificationtable').DataTable();
            });

            $(document.body).on("click", ".modaldescription", function () {
                var desc = $(this).attr('data-desc');
                //alert(mailid);
                $('#message').val(desc);
                // As pointed out in comments,
                // it is superfluous to have to manually call the modal.
                // $('#addBookDialog').modal('show');
            });

            $(document.body).on("click", ".remove1", function () {

                var notificationid = $(this).attr('id');
                var w = $(this);
                if (confirm("Do you want to Delete this Notification!") == true) {

                    $.ajax({
                        url: '/admin/notification-ajax-handler',
                        type: 'GET',
                        datatype: 'HTML',
                        data: {
                            method: 'deletenotification',
                            notificationId: notificationid
                        },
                        beforeSend: function () {

                        },
                        success: function (response) {
                            w.parent().parent().hide();
                        }
                    });
                }

            });

        });

    </script>
    <script src="/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
@endsection
