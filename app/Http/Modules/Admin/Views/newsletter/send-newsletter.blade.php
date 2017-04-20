@extends('Admin/Layouts/adminlayout')

@section('title', 'Send Newsletter') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
@endsection


@section('content')
    <div class="innerLR">
        <!--         Widget -->
        <div class="panel panel-white">
            <div class="panel-body">
        <div class="widget ">
            <!--             Widget heading -->
            <div class="widget-head">
                <h4 class="heading glyphicons list">Subscriber Details:</h4>

                <p style="float:right">
                    <button class="btn btn-info btn-sm sendnewsletter">Send</button>
                    <a id="submit" class="btn btn-warning" style="margin-left: 20px" name="formbutton"
                       value="edit-profile" type="submit" href="/admin/add-newsletter">Add Newsletter</a></p>
            </div>
            <!--             // Widget heading END -->
            <div class="widget-body">
            </div>
            <h4>Subscriber Table:</h4>
            <span class="newsletter-suc-err"></span></br></br>
            <?php
            if (isset($newsletterDetail)) {
            $row = 0;
            //foreach ($this->newsletterdata as $collectionData) { //print_r($collectionData);die;
            ?>
            <div class="form-group" style="float: center; width: 400px ">
                <select class="form-control" name="selectopt" id="selectnews">
                    <option value="">Select Newsletter</option>
                    <?php foreach ($newsletterDetail as $collectionData) { ?>
                    <option value="{{$collectionData->content}}">{{$collectionData->newsletter_log_subject}}</option>
                    <?php } ?>
                </select>
            </div>
            </br>
            <?php
            }
            //}
            ?>

            <table class="dynamicTable table table-striped table-bordered table-condensed" id="subjecttable">
                <thead>
                <tr>
                    <th  class="center">No</th>
                    <th ><input type="checkbox" id="selecctall"/> Subscriber email</th>
                    <th >Status</th>

                </tr>
                </thead>
                <tbody>

                <?php
                if (isset($subscriberDetail)) {
                $row = 0;
                foreach ($subscriberDetail as $collectionData) { //print_r($collectionData);die;
                if ($collectionData->sub_status == 'A') {
                ?>
                <tr class="selectable">
                    <td class="center"><?php echo ++$row; ?></td>
                    <td><input class="case" type="checkbox" name="case"
                               id="{{$collectionData->sub_email}}"/>{{$collectionData->sub_email}}</td>
                    <td class="center">
                        <?php if ($collectionData->sub_status == 'A') { ?>
                        <button class="btn btn-info btn-sm changestatus" data-id="{{$collectionData->news_id}}">Active
                        </button>
                        <?php } else { ?>
                        <button class="btn btn-danger btn-sm changestatus" data-id="{{$collectionData->news_id}}">
                            Inactive
                        </button>
                        <?php } ?>
                    </td>

                </tr>
                <?php
                }
                }
                }
                ?>
                </tbody>
            </table>

        </div>
        </div>
        </div>
    </div>
    <!--Widget END-->
    </div>
    </div>
    @endsection
            <!-- // Content END -->
@section('pagejavascripts')
    <script type="text/javascript">
        $(document).ready(function () {
//        $(document).ready(function () {
//            $('#subjecttable').DataTable();
//        });

            $(document.body).on("click", ".changestatus", function () {
                var newsletterId = $(this).attr('data-id');
//                var newsletterStatus = $(this).val();
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
                        url: '/admin/newsletter-ajax-handler',
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
            var sel = [];
            $('#selecctall').click(function (event) {
                //on click
                if (this.checked) { // check select status
                    $('.case').each(function () {
                        $(this).parent().addClass('checked');
                    });
                    var i = 0;
                    $('.checked').each(function () {
                        var obj = $(this).children()[0];
                        sel.push($(obj).attr('id'));
                        if (i == 0)
                            sel.pop();
                        i++;
                    });
                    //alert(sel);
                } else {
                    $('.case').each(function () {
                        $(this).parent().removeClass('checked');
                    });
                    while (sel.length)
                        sel.pop();
                }
            });

            $(document.body).on("click", ".sendnewsletter", function () {
                var obj = $(this);
                //var userId = $(this).attr('data-id');
                $('.case:checked').each(function () {
                    sel.push($(this).attr('id'));
                });
                var content = $('#selectnews').val();
                if (($('#selectnews').val() != "") && (sel != '')) {
                    $.ajax({
                        url: '/admin/newsletter-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'sendnewsletter',
                            emailobj: sel,
                            contentofMail: content

                        },
                        success: function (response) {
                            if (response) {
                                alert("Newsletter sent");
                                //alert("Mail send to " + email);
                                while (sel.length)
                                    sel.pop();
                            }
                        }
                    });
                } else {
                    $('.newsletter-suc-err').show();
                    $('.newsletter-suc-err').html('You missed something.');
                    $('.newsletter-suc-err').css('color', 'red');
                    $('.newsletter-suc-err').delay(3000).hide('slow');
//                alert('You missed something');
                }
            });

        });
    </script>
@endsection