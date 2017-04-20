@extends('Admin/Layouts/adminlayout')

@section('title', 'Send Merchant Notification') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>
    <style>
        .login .content label {
            color: #000;
        }
        input.error{border:1px solid #FF0000 !important; }
        label.error,div.error{
            font-weight:normal;
            color:#FF0000 !important;
        }
    </style>
@endsection


@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    {{--DISPLAY ALL CATEGORIES, USING SERVER SIDE DATATABLES--}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    <h3 class="form-title" style="color: black" >Send Notification:</h3>
                    <p style="float:right">
                        <button  class="btn blue pull-right" id="sendbtn">
                            Send <i class="m-icon-swapright m-icon-white"></i>
                        </button></p>
                    <span class="newsletter-suc-err"></span></br></br>

                    <div class="form-group" style="width: 400px ">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9"> Message</label>
                        <div class="input-icon">
                            <i class="fa fa-user"></i>
                            <textarea class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="message" name="message" id="messagecontent"></textarea>
                        </div>
                        <div class="input-icon">
                            <i class="fa fa-user"></i>
                            <textarea class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="description" name="description" id="descriptioncontent"></textarea>
                        </div>
                    </div></br>
                    <div style="overflow-x:auto;height:500px;">
                        <table class="dynamicTable table table-striped table-bordered table-condensed" id="subjecttable"  >
                            <thead>
                            <tr>
                                <!--                        <th style="width: 1%;" ><input id="collectioncheckcall" type="checkbox" /></th>-->
                                <th >No</th>
                                <th ><input type="checkbox" id="selecctall"/> User name</th>
                                <th >User Email</th>
                                <th >Registration Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            if (isset($merchantDetails) && !empty($merchantDetails)) {
                            $row = 0;
                            foreach ($merchantDetails as $collectionData) {     // echo '<pre>';print_r($collectionData);die;
                            ?>
                            <tr class="selectable">
                                <td ><?php echo ++$row; ?></td>
                                <td><input class="case" type="checkbox" name="case" id="{{$collectionData->id}}"/>{{$collectionData->username}}</td>
                                <td>{{$collectionData->email}}</td>
                                <td >{{$collectionData->created_at}}</td>
                            </tr>
                            <?php
                            }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejavascripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {
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

            $(document.body).on("click", "#sendbtn", function () {
                var obj = $(this);
                //var userId = $(this).attr('data-id');

                $('.case:checked').each(function () {
                    sel.push($(this).attr('id'));
                });
                var message = $('#messagecontent').val();
                var description = $('#descriptioncontent').val();
                if (($('#messagecontent').val() != "") && (sel != '') && ($('#descriptioncontent').val() != "")){
                    $.ajax({
                        url: '/admin/notification-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'sendMerchantNotification',
                            MerchantID: sel,
                            Message: message,
                            Description : description
                        },
                        success: function (response) {
                            if (response) {
                                // alert(response);
                                //alert("Mail send to " + email);
                                while (sel.length)
                                    sel.pop();
                                $('.case').removeAttr('checked');
                                $('.case').parent().removeClass('checked');
                                $('#selecctall').removeAttr('checked');
                                $('#selecctall').parent().removeClass('checked');
                                $('#messagecontent').val('');
                                $('#descriptioncontent').val('');
                                $('.newsletter-suc-err').show();
                                $('.newsletter-suc-err').html(response);
                                $('.newsletter-suc-err').css('color', 'green');
                                $('.newsletter-suc-err').delay(3000).hide('slow');
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