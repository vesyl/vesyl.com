@extends('Admin/Layouts/adminlayout')

@section('title', 'Add New Gift Certificate') {{--TITLE GOES HERE--}}

@section('headcontent')
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" media="screen" rel="stylesheet"
          type="text/css">
    <link href=" /assets/css/custom/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert display-hide" id="addtagmsgdiv">
                <button class="close" data-close="alert"></button>
                                    <span id="addtagmsg">
                                        <!--ADD TAG RESPONSE GOES HERE-->
                                    </span>
            </div>
            {{--<div class="portlet light bordered">--}}
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        {{--<i class="fa fa-plus font-blue-hoki"></i>--}}
                        {{--<span class="caption-subject font-blue-hoki bold">Add new Coupon</span>--}}
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a href="/admin/manage-giftcertificate" class="btn btn-default btn-circle"><i
                                    class="fa fa-angle-left"></i> Back To List</a>
                    </div>

                </div>

                <div class="portlet-body form">
                    <!-- BEGIN ADD NEW COUPON FORM -->
                    <form class="form-horizontal form-bordered" id="addnewdailyspecial" enctype="multipart/form-data"
                          method="post">
                        <div class="form-body">

                            <div class="form-group">
                                <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Gift Name:</label>

                                <div class="input-group">
                                    <div class=" form-horizontal">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="gift_name"
                                                   name="gift_name" placeholder="Gift code name">
                                        </div>
                                        {!!  $errors->first('gift_name', '<font color="red">:message</font>') !!}

                                        <button class="btn green" id="add_unique_code">Generate Unique Code
                                        </button>

                                    </div>
                                    <div class=" form-horizontal">
                                        <div class="form-group">
                                            <input type="text"
                                                   class="form-control <?php if (old('gift_code') == '') echo 'hidden';?>"
                                                   id="gift_code" readonly
                                                   name="gift_code" placeholder="Gift code"
                                                   value="{{old('gift_code')}}">
                                        </div>
                                        {!!  $errors->first('gift_code', '<font color="red">:message</font>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Gift Amount:</label>

                                <div class="input-group">
                                    <div class=" form-horizontal">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="gift_amount"
                                                   name="gift_amount" placeholder="Gift amount">
                                        </div>
                                        {!!  $errors->first('gift_amount', '<font color="red">:message</font>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <label class="form control-label col-md-2 col-lg-2 col-sm-2 col-xs-12">Upload
                                Certificate:</label>

                            <!-- CHANGE AVATAR TAB -->
                            <div class="form-group">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail"
                                         style="width: 200px; height: 150px;">
                                        <img src="/assets/images/no-image.png" alt=""/>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                         style="max-width: 200px; max-height: 150px;">
                                    </div>
                                    <div>
                                                            <span class="btn btn-circle default btn-file">
                                                                <span class="fileinput-new">
                                                                    Select image </span>
                                                                <span class="fileinput-exists">
                                                                    Change </span>
                                                                <input type="file" name="gift_certificate"
                                                                       accept="image/*">
                                                            </span>
                                        <a href="#" class="btn btn-circle default fileinput-exists"
                                           data-dismiss="fileinput">
                                            Remove </a>
                                    </div>
                                    {!!  $errors->first('gift_certificate', '<font color="red">:message</font>') !!}
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label col-md-2">Shop:</label>--}}

                            {{--<div class="margin-bottom-10">--}}
                            {{--<select class="bs-select form-control input-small" data-style="btn-info"--}}
                            {{--id="suppliershop" name="suppliershop">--}}
                            {{--<option value="">select</option>--}}
                            {{--<option value="0">Abevo</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <!-- SUBMIT BUTTON-->
                        <div class="form-actions">
                            <div align="center">
                                <button type="submit" class="btn blue" type="submit" id="addsubmit">Add Gift Certificate
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- END ADD NEW COUPON FORM -->
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->

@endsection

@section('pagejavascripts')
    <script type="text/javascript" src="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript" src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="/assets/js/pages/components-pickers.js"></script>
    <script src="/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>


    <script>

        $(document).ready(function () {

            $(document.body).on('click', '#add_unique_code', function (e) {
                e.preventDefault();
                var Base64 = {
                    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
                    encode: function (e) {
                        var t = "";
                        var n, r, i, s, o, u, a;
                        var f = 0;
                        e = Base64._utf8_encode(e);
                        while (f < e.length) {
                            n = e.charCodeAt(f++);
                            r = e.charCodeAt(f++);
                            i = e.charCodeAt(f++);
                            s = n >> 2;
                            o = (n & 3) << 4 | r >> 4;
                            u = (r & 15) << 2 | i >> 6;
                            a = i & 63;
                            if (isNaN(r)) {
                                u = a = 64
                            } else if (isNaN(i)) {
                                a = 64
                            }
                            t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                        }
                        return t
                    },
                    decode: function (e) {
                        var t = "";
                        var n, r, i;
                        var s, o, u, a;
                        var f = 0;
                        e = e.replace(/[^A-Za-z0-9+/=]/g, "");
                        while (f < e.length) {
                            s = this._keyStr.indexOf(e.charAt(f++));
                            o = this._keyStr.indexOf(e.charAt(f++));
                            u = this._keyStr.indexOf(e.charAt(f++));
                            a = this._keyStr.indexOf(e.charAt(f++));
                            n = s << 2 | o >> 4;
                            r = (o & 15) << 4 | u >> 2;
                            i = (u & 3) << 6 | a;
                            t = t + String.fromCharCode(n);
                            if (u != 64) {
                                t = t + String.fromCharCode(r)
                            }
                            if (a != 64) {
                                t = t + String.fromCharCode(i)
                            }
                        }
                        t = Base64._utf8_decode(t);
                        return t
                    }, _utf8_encode: function (e) {
                        e = e.replace(/rn/g, "n");
                        var t = "";
                        for (var n = 0; n < e.length; n++) {
                            var r = e.charCodeAt(n);
                            if (r < 128) {
                                t += String.fromCharCode(r)
                            }
                            else if (r > 127 && r < 2048) {
                                t += String.fromCharCode(r >> 6 | 192);
                                t += String.fromCharCode(r & 63 | 128)
                            } else {
                                t += String.fromCharCode(r >> 12 | 224);
                                t += String.fromCharCode(r >> 6 & 63 | 128);
                                t += String.fromCharCode(r & 63 | 128)
                            }
                        }
                        return t
                    }, _utf8_decode: function (e) {
                        var t = "";
                        var n = 0;
                        var r = c1 = c2 = 0;
                        while (n < e.length) {
                            r = e.charCodeAt(n);
                            if (r < 128) {
                                t += String.fromCharCode(r);
                                n++
                            } else if (r > 191 && r < 224) {
                                c2 = e.charCodeAt(n + 1);
                                t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                                n += 2
                            } else {
                                c2 = e.charCodeAt(n + 1);
                                c3 = e.charCodeAt(n + 2);
                                t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                                n += 3
                            }
                        }
                        return t
                    }
                }
                var giftName = $('#gift_name').val();
                $('#gift_code').removeClass('hidden');
                var encrypted = Base64.encode(giftName);
                document.getElementById("gift_code").value = encrypted;
//                var decrypted = Base64.decode(encrypted);
//                console.log(decrypted);

            });

        });

        jQuery(document).ready(function () {
            Metronic.init();
            ComponentsPickers.init();
        });

        function Base64() {

        }

    </script>
    {{--<script src="/assets/js/pages/dashboard.js"></script>--}}
@endsection

