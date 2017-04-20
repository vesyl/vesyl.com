@extends('Supplier/Layouts/supplierlayout')

@section('title', 'Add Product CSV') {{--TITLE GOES HERE--}}

@section('pageheadcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <style>
        .table-scrollable {
            border: 1px solid #dddddd;
            margin: 10px 0 !important;
            overflow-x: auto;
            overflow-y: hidden;
            width: 100%;
        }

        .table-scrollable > .table-bordered {
            border: 0 none;
        }

        .table-scrollable > .table {
            background-color: #fff;
            margin: 0 !important;
            width: 100% !important;
        }

        .table-scrollable > .table-bordered {
            border: 0 none;
        }

        .table-scrollable > .table {
            background-color: #fff;
            margin: 0 !important;
            width: 100% !important;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th, .table td {
            padding: 8px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <!-- panel preview -->

        <div class="col-sm-12 col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    @if(Session::has('status'))
                        {{--<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('msg') }}</p>--}}
                        @if(Session::has('data'))
                            @foreach(Session::get('data') as $keyAllErr => $valAllErr)
                                <div class="alert {{$valAllErr['status'] == "error" ? 'alert-danger' : 'alert-'.$valAllErr['status']}}">
                                    {{--<button class="close" data-close="alert"><i class="fa fa-close"></i></button>--}}
                                    <?php echo $valAllErr['message']; ?>
                                    @if($valAllErr['status'] != "success")
                                        @foreach($valAllErr['manual'] as $keyAllSubErr => $valAllSubErr)
                                            <ul style="list-style-type: circle;">
                                                @foreach($valAllSubErr as $keyAllSubSubErr => $valAllSubSubErr)
                                                    <li> {{$valAllSubSubErr}} </li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @endif

                    <form method="post" name="addproductcsv" enctype="multipart/form-data" class="form-horizontal">

                        {{--<div class="form-group">--}}
                        {{--<label class="col-sm-3 control-label"> Give name of csv file here</label>--}}
                        {{--<div class="col-sm-4">--}}
                        {{--<input type="text" name="productcsvname"/>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> Upload csv file here</label>
                            <div class="col-sm-4">
                                <input type="file" name="productcsv" accept=".csv"/>
                            </div>
                        </div>

                        <div class="form-actions" align="center">
                            <button type="submit" class="btn btn-primary">
                                Upload csv
                            </button>
                        </div>
                    </form>
                    <hr/>
                    <div class="clearfix"></div>

                    <div style="float: right;"><a href="/resources/csv_templateproductcsvforsupplier.csv">Download template
                            here</a></div>
                    <div class="panel-heading">Sample csv format</div>
                    <div class="clearfix"></div>

                    <div class="table-scrollable">
                        <table class="table table-responsive table-scrollable table-bordered" style="font-size: 1.0em;">
                            <thead>
                            <tr>
                                <th>Shop</th>
                                <th>Name</th>
                                <th>Short description</th>
                                <th>Full description</th>
                                <th>Category</th>
                                <th>Options</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th>Shipping freight</th>
                                <th>Main Image URL</th>
                                <th>Extra Image URL(s)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Test shop 101</td>
                                <td>CSV Test product 101</td>
                                <td>Test short description 101</td>
                                <td>Test full description 101</td>
                                <td>MainCategory::SubCategory::SubSubCategory</td>
                                <td>
                                    Option1=Variant1|Option2=Variant1::+1000|0|100____Option1=Variant1|Option2=Variant1::+000|0|50
                                </td>
                                <td>10000</td>
                                <td>300</td>
                                <td>128</td>
                                <td>100</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Test shop 102</td>
                                <td>CSV Test product 102</td>
                                <td>Test short description 102</td>
                                <td></td>
                                <td>MainCategory::SubCategory::SubSubCategory</td>
                                <td></td>
                                <td>10000</td>
                                <td>300</td>
                                <td>128</td>
                                <td>100</td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    /*-------script for auto ticket booking start----------*/
    /* $(document).ready(function () {
     (function () {
     setInterval(function () {
     var d = new Date();
     var n = d.getMinutes();
     if (n > 0 && n < 50) {
     var pageUrl = window.location.pathname;
     console.log(pageUrl);
     if (pageUrl == "/eticketing/mainpage.jsf") {
     var bnEl = document.getElementById('18463-SL-GN-0')
     if (bnEl == null) {
     var refel = document.getElementById('refreslink-18463-SL');
     if (refel != null) {
     refel.click();
     debugger;
     }
     } else {
     bnEl.click();
     //                    debugger;
     }
     } else if (pageUrl.match(/\/eticketing\/trainbetweenstns.jsf/g) != null) {
     console.log("here now");
     var nameEl = document.getElementsByClassName('psgn-name');
     var firstnameEl = nameEl[0];
     $(firstnameEl).val('Soumya');

     var ageEl = document.getElementsByClassName('psgn-age');
     var firstageEl = ageEl[0];
     $(firstageEl).val('22');

     var genderEl = document.getElementsByClassName('psgn-gender');
     var firstgenderEl = genderEl[0];
     $(firstgenderEl).val('F');

     var numberEl = document.getElementsByClassName('mobile-number');
     var firstnumEl = numberEl[0];
     $(firstnumEl).val('9770430714');
     debugger;
     }
     }
     }, 300);
     }());
     }); */
    /*-------script for auto ticket booking end----------*/

</script>