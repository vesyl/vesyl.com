<?php
$weightSymbol = getSetting('weight_symbol'); $weightSymbol = $weightSymbol ? $weightSymbol : 'lbs';
$priceSymbol = getSetting('price_symbol'); $priceSymbol = $priceSymbol ? $priceSymbol : '$';

function treeView($array, $id = 0, $allCategories, $productData)
{
foreach ($array as $keyArray => $valueArray) {
if ($array[$keyArray]->parent_category_id == $id) {
$flag = array_search($array[$keyArray]->category_id, array_column(json_decode(json_encode($allCategories), true), 'parent_category_id'));
if  ($flag) { ?>
<optgroup
        label="<?php echo $array[$keyArray]->display_name . $array[$keyArray]->category_name; ?>">
    <?php treeView($array, $array[$keyArray]->category_id, $allCategories, $productData); ?>
</optgroup>
<?php
} else { ?>
<option value="{{$array[$keyArray]->category_id }}" @if($array[$keyArray]->category_id == $productData['category_id']){{"selected"}}@endif>
    <?php echo $array[$keyArray]->display_name . $array[$keyArray]->category_name; ?>
</option>
<?php }
}
}
}
?>

@extends('Supplier/Layouts/supplierlayout')

@section('title', 'Edit Product') {{--TITLE GOES HERE--}}

@section('pageheadcontent')
    @if(isset($productData) && !empty($productData))

        {{--OPTIONAL--}}
        {{--PAGE STYLES OR SCRIPTS LINKS--}}
        <link href="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css"/>
        {{--<link href="/assets/css/custom/components.css" rel="stylesheet" type="text/css"/>--}}
        <link href="/assets/css/custom/components-rounded.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/css/jquery.datatables.min.css"/>

        <style>
            /*table tr th {*/
            /*text-align: center;*/
            /*}*/

            .separator {
                font-size: 25px;
            }

            .select2-container--default .select2-selection--single .select2-selection__clear {
                padding-right: 2%;
            }

            .panel-collapse .panel-body {
                height: 300px;
                overflow-y: auto;
            }

            hr {
                margin: 7px;
                border-color: #ddd;
            }

            .hrfeaturegroup {
                margin: 7px;
                border-color: #aaa;
            }

            .masonry { /* Masonry container */
                -moz-column-count: 4;
                -webkit-column-count: 4;
                column-count: 4;
                -moz-column-gap: 1em;
                -webkit-column-gap: 1em;
                column-gap: 1em;
                border: 1px solid #899dc1;
                background-color: #eee;
                margin: 0 0 1em;
                padding: 2%;
            }

            .item { /* Masonry bricks or child elements */
                /*border: 2px solid #000;*/
                background-color: #899cd1;
                margin: 0 0 1em;
                display: inline-block;
                width: 100%;
                padding: 0%;
                box-shadow: 3px 4px 8px 1px rgba(0, 0, 0, 0.4) !important;
                float: inherit !important;
            }

            .item:hover {
                padding: 1%;
                box-shadow: 0px 0px 5px rgba(85, 160, 255, 1) !important;
            }

            .panel-collapse {
                border: 1px solid #12afcb;
            }
        </style>
    @endif
@endsection

@section('content')
    {{--PAGE CONTENT GOES HERE--}}

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-white">
                <div class="panel-body">


                    @if(isset($productData) && !empty($productData))

                        <div class="portlet-title tabbable-line">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1_1" data-toggle="tab">General</a></li>
                                <li><a href="#tab_1_2" data-toggle="tab">Images</a></li>
                                {{--<li><a href="#tab_1_3" data-toggle="tab">SEO</a></li>--}}
                                <li><a href="#tab_1_4" data-toggle="tab">Options</a></li>
                                <li><a href="#tab_1_5" data-toggle="tab">Shipping properties</a></li>
                                <li><a href="#tab_1_6" data-toggle="tab">Quantity discounts</a></li>
                                <li id="featureTab" class="hidden"><a href="#tab_1_7" data-toggle="tab">Features</a>
                                </li>
                                <li><a href="#tab_1_8" data-toggle="tab">Product tabs</a></li>
                                <li><a href="#tab_1_9" data-toggle="tab">Tags</a></li>
                            </ul>
                        </div>
                        {{--<form class="form-horizontal" method="post" enctype="multipart/form-data" autocomplete="on"--}}
                        {{--id="addProductForm">--}}
                        <div class="tab-content">
                            {{--GENERAL DETAILS TAB--}}
                            <div class="tab-pane active" id="tab_1_1">
                                <form class="form-horizontal" method="post" autocomplete="on"
                                        id="editGeneralDetailsForm">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default panel-info">
                                            <div class="panel-heading" role="tab" id="headingInfo" data-toggle="collapse" data-parent="#accordion" href="#collapseInfo" aria-expanded="true" aria-controls="collapseInfo">
                                                <h4 class="panel-title">
                                                    <a> Information </a>
                                                </h4>
                                            </div>
                                            <div id="collapseInfo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingInfo" aria-expanded="true">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Name</label>

                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="product_name" name="product_data[product_name]" value="@if(isset(old('product_data')['product_name'])){{old('product_data')['product_name']}}@else{{$productData['product_name']}}@endif">
                                                            <span class="error">{!! $errors->first('product_name') !!}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Supplier</label>

                                                        <div class="col-sm-4">
                                                            <select name="product_data[shop_id]" class="form-control m-b-sm">
                                                                <option value="0">None</option>
                                                                @if(isset($allShop))
                                                                    @foreach($allShop as $key=>$value)
                                                                        <option value="{{$value->id}}"
                                                                                @if(isset(old('product_data')['shop_id'])&&$value->id==old('product_data')['shop_id']) selected @endif>{{$value->shop_name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="error">{!! $errors->first('shop_id') !!}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Choose category</label>

                                                        <div class="col-md-4">
                                                            <select id="select_category" class="bs-select form-control" name="product_data[category_id]">
                                                                <option value="">Select category</option>
                                                                @if(isset($allCategories))
                                                                    <?php treeView($allCategories, 0, $allCategories, $productData); ?>
                                                                @endif
                                                            </select>

                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label"> Price ({{$priceSymbol}}
                                                            )</label>

                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control float-type" id="price" name="product_data[price]" value="@if(isset(old('product_data')['price'])){{old('product_data')['price']}}@else{{$productData['price_total']}}@endif">
                                                            <span class="error">{!! $errors->first('price') !!}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {{--Todo-use editor here--}}
                                                        <label class="col-sm-3 control-label">Full description</label>

                                                        <div class="col-sm-4">
                                                            <textarea name="product_data[full_description]" class="form-control">@if(isset(old('product_data')['price'])){{old('product_data')['full_description']}}@else{{$productData['full_description']}}@endif</textarea>
                                                            <span class="error">{!! $errors->first('full_description') !!}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Status</label>

                                                        <div class="col-sm-4">
                                                            <label for="status_active" class="col-sm-6">
                                                                <input type="radio" class="form-control" id="status_active" name="product_data[status]" @if($productData['product_status'] == 1){{"checked"}}@endif value="1">
                                                                Active </label>
                                                            <label for="status_inactive" class="col-sm-6">
                                                                <input type="radio" class="form-control" id="status_inactive" name="product_data[status]" @if($productData['product_status'] == 2){{"checked"}}@endif value="2">
                                                                Inactive </label>
                                                        </div>
                                                    </div>

                                                <!--
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Only for wholesale</label>

                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="product_data[product_type]"
                                                                    class="form-control m-b-sm"
                                                                    id="product_type"
                                                            @if(isset(old('product_data')['product_type'])){{"checked"}}@elseif($productData['product_type'] == 1){{"checked"}}@endif />
                                                        </div>
                                                    </div>
                                                    -->
                                                    <!--todo required if supplier can add product to any type of campaign/wholesale-->


                                                    {{--<div class="form-group">--}}
                                                    {{--<label class="col-sm-3 control-label">Min quantity</label>--}}
                                                    {{--<div class="col-sm-4">--}}
                                                    {{--<input type="text" class="form-control" id="quantity_min"--}}
                                                    {{--name="product_data[quantity_min]"--}}
                                                    {{--value="{{old('product_data')['quantity_min']}}">--}}
                                                    {{--<span class="error">{!! $errors->first('quantity_min') !!}</span>--}}
                                                    {{--</div>--}}
                                                    {{--</div>--}}


                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">For gender</label>

                                                        <div class="col-sm-4">
                                                            <select name="product_data[for_gender]" class="form-control m-b-sm" id="for_gender">
                                                                <?php $genders = ['0' => 'For all', '1' => 'Male', '2' => 'Female', '3' => 'Unisex'];?>
                                                                @foreach($genders as $key =>$gender)
                                                                    <option value="{{$key}}" @if(isset(old('product_data')['for_gender'])&&$key==old('product_data')['for_gender']) selected @endif>{{$gender}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error">{!! $errors->first('for_gender') !!}</span>
                                                        </div>
                                                    </div>

                                                    {{--<div class="form-group">--}}
                                                    {{--<label class="col-sm-3 control-label">--}}
                                                    {{--Weight ({{$weightSymbol}})</label>--}}
                                                    {{--<div class="col-sm-4">--}}
                                                    {{--<input type="text" class="form-control" id="weight"--}}
                                                    {{--name="product_data[weight]"--}}
                                                    {{--value="{{old('product_data')['weight']}}">--}}
                                                    {{--<span class="error">{!! $errors->first('weight') !!}</span>--}}
                                                    {{--</div>--}}
                                                    {{--</div>--}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading collapsed" role="tab" id="headingPrice" data-toggle="collapse" data-parent="#accordion" href="#collapsePrice" aria-expanded="false" aria-controls="collapsePrice">
                                                <h4 class="panel-title">
                                                    <a class="collapsed"> Pricing / inventory </a>
                                                </h4>
                                            </div>
                                            <div id="collapsePrice" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPrice">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label"> List price
                                                            ({{$priceSymbol}})
                                                            <i class="fa fa-question-circle" data-toggle="tooltip" title="Manufacturer suggested retail price."></i>
                                                        </label>

                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control float-type" id="list_price" name="product_data[list_price]" value="@if(isset(old('product_data')['list_price'])){{old('product_data')['list_price']}}@else{{$productData['list_price']}}@endif">
                                                            <span class="error">{!! $errors->first('list_price') !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">In stock</label>

                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control integer-type" id="in_stock" name="product_data[in_stock]" value="@if(isset(old('product_data')['in_stock'])){{old('product_data')['in_stock']}}@else{{$productData['in_stock']}}@endif">
                                                            <span class="error">{!! $errors->first('in_stock') !!}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label"> Minimum order
                                                            quantity</label>

                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control integer-type" id="minimum_order_quantity" name="product_data[minimum_order_quantity]" value="@if(isset(old('product_data')['minimum_order_quantity'])){{old('product_data')['minimum_order_quantity']}}@else{{$productData['min_qty']}}@endif">
                                                            <span class="error">{!! $errors->first('minimum_order_quantity') !!}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Maximum order
                                                            quantity</label>

                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control integer-type" id="maximum_order_quantity" name="product_data[maximum_order_quantity]" value="@if(isset(old('product_data')['maximum_order_quantity'])){{old('product_data')['maximum_order_quantity']}}@else{{$productData['max_qty']}}@endif">
                                                            <span class="error">{!! $errors->first('maximum_order_quantity') !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Taxes</label>

                                                        <div class="col-sm-4">
                                                            <input type="checkbox" class="form-control" id="taxes" name="product_data[taxes]" @if(isset(old('product_data')['taxes'])) checked @endif>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading collapsed" role="tab" id="headingExtra" data-toggle="collapse" data-parent="#accordion" href="#collapseExtra" aria-expanded="false" aria-controls="collapseExtra">
                                                <h4 class="panel-title">
                                                    <a class="collapsed"> Extra </a>
                                                </h4>
                                            </div>
                                            <div id="collapseExtra" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingExtra">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        {{--Todo-Use editor here--}}
                                                        <label class="col-sm-3 control-label"> Short description</label>

                                                        <div class="col-sm-4">
                                                            <textarea name="product_data[short_description]" class="form-control">@if(isset(old('product_data')['short_description'])){{old('product_data')['short_description']}}@else{{$productData['short_description']}}@endif</textarea>
                                                            <span class="error">{!! $errors->first('short_description') !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label"> Search words</label>

                                                        <div class="col-sm-4">
                                                            <textarea name="product_data[search_words]" class="form-control">@if(isset(old('product_data')['search_words'])){{old('product_data')['search_words']}}@else{{$productData['search_words']}}@endif</textarea>
                                                            <span class="error">{!! $errors->first('search_words') !!}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        {{--Todo-Use editor here--}}
                                                        <label class="col-sm-3 control-label">Promo text</label>

                                                        <div class="col-sm-4">
                                                            <textarea name="product_data[promo_text]" class="form-control">@if(isset(old('product_data')['promo_text'])){{old('product_data')['promo_text']}}@else{{$productData['promo_text']}}@endif</textarea>
                                                            <span class="error">{!! $errors->first('promo_text') !!}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading collapsed" role="tab" id="headingFeatures" data-toggle="collapse" data-parent="#accordion" href="#collapseFeatures" aria-expanded="false" aria-controls="collapseExtra">
                                                <h4 class="panel-title">
                                                    <a class="collapsed"> Features </a>
                                                </h4>
                                            </div>
                                            <div id="collapseFeatures" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFeatures">
                                                <div class="panel-body" id="featuresData">
                                                    @if(isset($featuresData))

                                                        @foreach($featuresData['featureDetails']['data'] as $keyFD=>$valueFD)

                                                            <?php $toAppendStatus = '<div class="col-md-offset-1 col-md-4"><label for="product_feature_status_' . $valueFD['feature_id'] . '_active" class="col-sm-6">';
                                                            $toAppendStatus .= '<input type="radio" name="product_data[features][' . $valueFD['feature_id'] . '][status]" id="product_feature_status_' . $valueFD['feature_id'] . '_active" value="1" ' . ($valueFD['display_status'] == "1" ? 'checked' : '') . '> Active </label>';
                                                            $toAppendStatus .= '<label for="product_feature_status_' . $valueFD['feature_id'] . '_disabled" class="col-sm-6">';
                                                            $toAppendStatus .= '<input type="radio" name="product_data[features][' . $valueFD['feature_id'] . '][status]" id="product_feature_status_' . $valueFD['feature_id'] . '_disabled" value="0" ' . ($valueFD['display_status'] == "0" ? 'checked' : '') . '> Disabled </label></div>'; ?>

                                                            <hr>
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">{{$valueFD['feature_name']}}</label>
                                                                @if ($valueFD['feature_type'] == 0)
                                                                    <div class="col-md-3">
                                                                        <input class="form-control" type="checkbox" name="product_data[features][{{$valueFD['feature_id']}}][single]" @if($valueFD['feature_id'] == $valueFD['feature_id_relations']) checked @endif/>
                                                                    </div>
                                                                    <?php echo $toAppendStatus; ?>
                                                                    <div class="clearfix"></div>
                                                                @elseif($valueFD['feature_type'] == 1)
                                                                    @if ($valueFD['variant_ids'] != null)
                                                                        <?php $variantIds = explode(",", $valueFD['variant_ids']); ?>
                                                                        <?php $variantNames = explode(",", $valueFD['variant_names']); ?>
                                                                        <?php $variantDescs = explode(",", $valueFD['variant_descriptions']); ?>
                                                                        @foreach($variantIds as $keyVIds=> $valueVIds)
                                                                            @if($keyVIds != 0)
                                                                                <div class="col-md-3"></div>
                                                                            @endif
                                                                            <div class="col-md-3">
                                                                                <input class="form-control" type="checkbox" name="product_data[features][{{$valueFD['feature_id']}}][multiple][{{$valueVIds}}]" @if(in_array($valueVIds, explode(",",$valueFD['var_id_relations']))) checked @endif/> {{$variantNames[$keyVIds]}}
                                                                            </div>
                                                                            <!--value="' + a['feature_id'] + '.' + aVIds + '"-->
                                                                            @if($keyVIds== 0)
                                                                                <?php echo $toAppendStatus; ?>
                                                                            @endif

                                                                            <div class="clearfix"></div>
                                                                        @endforeach
                                                                    @endif
                                                                @elseif ($valueFD['feature_type'] == 2 || $valueFD['feature_type'] == 3)
                                                                    @if($valueFD['variant_ids'] != null)
                                                                        <?php $variantIds = explode(",", $valueFD['variant_ids']); ?>
                                                                        <?php $variantNames = explode(",", $valueFD['variant_names']); ?>
                                                                        <?php $variantDescs = explode(",", $valueFD['variant_descriptions']); ?>
                                                                        <div class="col-md-3">
                                                                            <select class="form-control"
                                                                                    name="product_data[features][{{$valueFD['feature_id']}}][select]">
                                                                                @foreach($variantIds as $keyVIds=> $valueVIds)
                                                                                    <option value="{{$valueVIds}}" @if($valueVIds == $valueFD['var_id_relations']) selected @endif>{{$variantNames[$keyVIds]}}</option>
                                                                                @endforeach
                                                                            </select></div>
                                                                        <?php echo $toAppendStatus; ?>
                                                                        <div class="clearfix"></div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        @endforeach

                                                        @foreach($featuresData['featureGroupDetails']['data'] as $keyFG => $valueFG)
                                                            <hr class="hrfeaturegroup">
                                                            <strong>{{$valueFG['feature_name']}}</strong><br>
                                                            @foreach($valueFG['featureDetails'] as $keyFD => $valueFD)

                                                                <?php $toAppendStatus = '<div class="col-md-offset-1 col-md-4"><label for="product_feature_status_' . $valueFD['feature_id'] . '_active" class="col-sm-6">';
                                                                $toAppendStatus .= '<input type="radio" name="product_data[features][' . $valueFD['feature_id'] . '][status]" id="product_feature_status_' . $valueFD['feature_id'] . '_active" value="1" ' . ($valueFD['display_status'] == "1" ? 'checked' : '') . '> Active </label>';
                                                                $toAppendStatus .= '<label for="product_feature_status_' . $valueFD['feature_id'] . '_disabled" class="col-sm-6">';
                                                                $toAppendStatus .= '<input type="radio" name="product_data[features][' . $valueFD['feature_id'] . '][status]" id="product_feature_status_' . $valueFD['feature_id'] . '_disabled" value="0" ' . ($valueFD['display_status'] == "0" ? 'checked' : '') . '> Disabled </label></div>'; ?>

                                                                <hr>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">{{$valueFD['feature_name']}} </label>
                                                                    @if ($valueFD['feature_type'] == 0)
                                                                        <div class="col-md-3">
                                                                            <input class="form-control" type="checkbox"
                                                                                    name="product_data[features][{{$valueFD['feature_id']}}][single]"/>
                                                                        </div>
                                                                        <?php echo $toAppendStatus; ?>
                                                                        <div class="clearfix"></div>
                                                                    @elseif ($valueFD['feature_type'] == 1)
                                                                        @if ($valueFD['variant_ids'] != null)
                                                                            <?php $variantIds = explode(",", $valueFD['variant_ids']);
                                                                            $variantNames = explode(",", $valueFD['variant_names']);
                                                                            $variantDescs = explode(",", $valueFD['variant_descriptions']); ?>
                                                                            @foreach($variantIds as $keyVIds => $valueVIds)
                                                                                @if($keyVIds != 0)
                                                                                    <div class="col-md-3"></div>
                                                                                @endif
                                                                                <div class="col-md-3">
                                                                                    <input class="form-control" type="checkbox" name="product_data[features][{{$valueF['feature_id']}}][multiple][{{$valueVIds}}]"/>{{$variantNames[$keyVIds]}}
                                                                                </div>
                                                                                @if($keyVIds == 0)
                                                                                    <?php echo $toAppendStatus; ?>
                                                                                @endif
                                                                                <div class="clearfix"></div>
                                                                            @endforeach
                                                                        @endif
                                                                    @elseif ($valueFD['feature_type'] == 2 || $valueFD['feature_type'] == 3)
                                                                        @if ($valueFD['variant_ids'] != null)
                                                                            <?php $variantIds = explode(",", $valueFD['variant_ids']);
                                                                            $variantNames = explode(",", $valueFD['variant_names']);
                                                                            $variantDescs = explode(",", $valueFD['variant_descriptions']); ?>
                                                                            <div class="col-md-3">
                                                                                <select class="form-control"
                                                                                        name="product_data[features][{{$valueFD['feature_id']}}][select]">
                                                                                    @foreach($variantIds as $keyVIds =>$valueVIds)
                                                                                        <option value="{{$valueVIds}}">{{$variantNames[$keyVIds]}}</option>
                                                                                    @endforeach
                                                                                </select></div>
                                                                            <?php echo $toAppendStatus; ?>
                                                                            <div class="clearfix"></div>

                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="clearfix"></div>

                                                            @endforeach
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions" align="center">
                                        <button type="submit" class="btn btn-primary"
                                                name="product_data[updateFormName]" value="general">
                                            Update general info
                                        </button>
                                    </div>
                                </form>
                            </div>
                            {{--//GENERAL DETAILS TAB--}}
                            {{--IMAGES TAB--}}
                            <div class="tab-pane" id="tab_1_2">
                                <!--<form class="form-horizontal" method="post" enctype="multipart/form-data" autocomplete="on" id="editImagesForm">-->
                                <div class="panel-group" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default panel-info">
                                        <div class="alert alert-success margin-bottom-10">
                                            <i class="fa fa-warning fa-lg"></i> You can
                                            upload .jpg, .png, .bmp and .gif file types
                                            only.
                                        </div>
                                        <div class="panel-heading" role="tab" id="headingMainImage" data-toggle="collapse" href="#collapseMainImage" aria-controls="collapseMainImage">
                                            <h4 class="panel-title">
                                                <a> Main Image </a>
                                            </h4>
                                        </div>
                                        <div id="collapseMainImage" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingMainImage" aria-expanded="true">
                                            <div class="panel-body">
                                                <div class="form-horizontal form-bordered">
                                                    <div class="form-body">

                                                        <!--CODE FOR IMAGES-->

                                                        @if(isset($dataDefaultImages['data']) && !empty($dataDefaultImages['data']))
                                                            @foreach ($dataDefaultImages['data'] as $keyDefImg => $valueDefImg)
                                                                @if ($valueDefImg['image_type'] == 0)

                                                                    <div class="row" align="center">
                                                                        <form enctype="multipart/form-data" class="form-horizontal form-row-seperated" id="product-main-image-form" method="post">

                                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                <div class="fileinput-new thumbnail">
                                                                                    <img id="main-image" image-id="<?php echo $valueDefImg['pi_id']; ?>" height="300px" width="300px" src="<?php echo $valueDefImg['image_url']; ?>" alt="Main Image"/>
                                                                                </div>
                                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 300px; max-height: 300px;">
                                                                                </div>
                                                                                <div>
                                                                                    <span class="btn-file" style="position: absolute; right: 375px; top: 337px; display: none;">
                                                                                        <span class="fileinput-new">
                                                                                            <i class="fa fa-camera btn btn-circle default "></i></span>
                                                                                        <span class="fileinput-exists fa fa-camera btn btn-circle default "></span>
                                                                                        <input id="main-image-file" type="file" name="product_data[mainimage]" accept="image/*">
                                                                                    </span>
                                                                                    <span id="main-image-action" style="position: absolute; right: 374px; top: 338px;">
                                                                                        <a class="btn btn-circle btn-sm green fileinput-exists" id="submit-main-image">Save</a>
                                                                                        <a class="btn btn-circle btn-sm red fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            @endforeach

                                                        @endif


                                                    </div>
                                                </div>
                                            <!--<div class="form-group">
                                                        {{--<h4 class="note note-info note-bordered"--}}
                                            {{--style="text-align: center">--}}
                                            {{--Main Image</h4>--}}
                                                    <div class="text-center col-md-6">

                                                        <div class="fileinput fileinput-new"
                                                                data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail"
                                                                    style="width: 200px; height: 150px;">
                                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                                            alt=""/>
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                                        style="max-width: 200px; max-height: 150px;">
                                                                </div>
                                                                <div>
                                                                    <span class="btn default btn-info btn-file">
                                                                        <span class="fileinput-new"> Select
                                                                            image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        <input id="main-image" type="file"
                                                                                name="product_data[mainimage]"> </span>
                                                                    <a href="#"
                                                                            class="btn default btn-danger fileinput-exists"
                                                                            data-dismiss="fileinput"> Remove </a>
                                                                </div>
                                                                <span class="error">{!! $errors->first('mainimage') !!}</span>
                                                            </div>


                                                            {{--<div class="clearfix margin-top-10">--}}
                                            {{--<span class="label label-danger">NOTE! </span> <span>Attached--}}
                                            {{--image thumbnail is supported in Latest Firefox, Chrome,--}}
                                            {{--Opera, Safari and Internet Explorer 10 only </span>--}}
                                            {{--</div>--}}

                                                    </div>
                                                    <div class="col-md-6">
                                                        <br>

                                                        <p class="margin-top-10">
                                                            Counterfeit products are prohibited on FlashSale.
                                                        </p>

                                                        <p class="margin-top-10">Products with multiple high quality
                                                            images get the most sales.</p>

                                                        <p class="margin-top-10">Add images that are at least
                                                            800x800 pixels.</p>

                                                        <p class="margin-top-10">Do not steal images from other
                                                            merchants, or your product will be deleted.</p>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading collapsed" role="tab" id="headingOtherImages" data-toggle="collapse" data-parent="#accordion" href="#collapseOtherImages" aria-expanded="false" aria-controls="collapseOtherImages">
                                            <h4 class="panel-title">
                                                <a class="collapsed"> Other Images </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOtherImages" class="panel-collapse collapse"
                                                role="tabpanel"
                                                aria-labelledby="headingOtherImages">
                                            <div class="panel-body" style="height: 800px;">
                                                <div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
                                                    <a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn yellow">
                                                        <i class="fa fa-plus"></i> Select Files
                                                    </a>
                                                    <a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn green">
                                                        <i class="fa fa-share"></i> Upload Files
                                                    </a>
                                                </div>
                                                <div class="row">
                                                    <div id="tab_images_uploader_filelist" class="col-md-8 col-sm-12">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding: 1%" align="center">

                                                    <div class="masonry @if(count($dataDefaultImages['data']) < 2) hidden @endif">

                                                        @foreach ($dataDefaultImages['data'] as $keyDefImg => $valueDefImg)
                                                            @if ($valueDefImg['image_type'] == 1)

                                                                <div class="item other-images col-md-12">
                                                                    <div class="btn-group pull-right image-action" style="position: absolute; right: 10px; bottom: 10px;">
                                                                    <!--<a class="btn btn-sm blue btn-circle dropdown-toggle" href="#" data-toggle="dropdown">
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu pull-right">
                                                                            <li>
                                                                                <a class="make-main-image" image-id="{{$valueDefImg['pi_id']}}">
                                                                            Make main image
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="delete-image" image-id="{{$valueDefImg['pi_id']}}">Delete </a>
                                                                            </li>
                                                                        </ul>-->
                                                                        <button class="btn btn-sm red-thunderbird btn-circle delete-image" image-id="{{$valueDefImg['pi_id']}}">
                                                                            <i class="fa fa-trash"></i></button>
                                                                    </div>
                                                                    <img class="img-responsive" src="{{$valueDefImg['image_url']}}" alt="">
                                                                </div>

                                                            @endif
                                                        @endforeach

                                                        <div id="newimageappendbefore"></div>

                                                    </div>
                                                </div>
                                            <!--<div class="form-group last">
                                                        {{--<h4 class="note note-info note-bordered"--}}
                                            {{--style="text-align: center">--}}
                                            {{--Other Images</h4>--}}
                                                    <div class="col-md-12">
                                                        <div class="fileinput fileinput-new"
                                                                data-provides="fileinput"
                                                                id="otherimagesdiv">
                                                            <div class="fileinput-new thumbnail"
                                                                    style="width: 200px; height: 150px;">
                                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                                                                            alt=""/>
                                                                </div>
                                                                <!--<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">->
                                                            </div>
                                                            <div>
                                                                <span class="btn default btn-file btn-info">
                                                                    <span class="fileinput-new" id="otherimageselect">
                                                                        Select images </span>
                                                                    <span class="fileinput-exists hidden"
                                                                            id="otherimagechange"> Change </span>
                                                                    <input type="file"
                                                                            name="product_data[otherimages][]"
                                                                            multiple=""
                                                                            accept="image/*" id="otherimages"> </span>
                                                                <a class="btn default fileinput-exists btn-danger hidden"
                                                                        data-dismiss="fileinput"
                                                                        id="otherimagesremove"> Remove All</a>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="form-actions" align="center">
                                    <button type="submit" class="btn btn-primary"
                                            name="product_data[updateFormName]" value="images">
                                        Update images
                                    </button>
                                </div>-->

                                <!--</form>-->
                            </div>

                            <div class="tab-pane" id="tab_1_3">

                            </div>

                            <div class="tab-pane" id="tab_1_4">
                                <form class="form-horizontal" method="post" enctype="multipart/form-data"
                                        autocomplete="on" id="editOptionsForm">

                                    <div class="form-group">
                                        {{--<label class="col-sm-3 control-label">Option</label>--}}
                                        <div class="col-sm-6">
                                            <select class="form-control bs-select"
                                                    id="select_option">
                                                @foreach($allOptions as $optionKey=>$optionValue)
                                                    <option value="{{$optionValue->option_id}}">
                                                        {{$optionValue->option_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="button" class="btn btn-info" id="add-another-option">Add
                                                another option
                                            </button>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="button" class="btn btn-default" id="option-combinations">
                                                Option combinations
                                            </button>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="optionTable">
                                        <thead>
                                        <tr>
                                            <th width="50%">Option</th>
                                            <th width="15%">Action</th>
                                            <th width="35%">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="optionTableBody">
                                        {{--APPEND OPTION AND VARIANT DATA HERE--}}

                                        @if($dataOptVarWithRelations['code'] == 200)
                                            @foreach($dataOptVarWithRelations['data'] as $keyOVwR => $valueOVwR)
                                                <?php $optionId = $valueOVwR['option_id'];
                                                $optionName = $valueOVwR['option_name'];
                                                $optionVariants = json_decode($valueOVwR['variant_data'], true); ?>
                                                @if ($valueOVwR['product_id'] != null)
                                                    <tr id="option_id_{{$optionId}}" option-id="{{$optionId}}">
                                                        <td>
                                                            <a total-variant="{{count($optionVariants)}}"
                                                                    style="text-decoration: none" href="javascript:void(0);"
                                                                    class="edit-option option-name"
                                                                    data-id="{{$optionId}}">{{$optionName}}</a>
                                                            <input id="option_name_{{$optionId}}"
                                                                    field-name="relation_id" type="hidden"
                                                                    name="product_data[options][{{$optionId}}][relation_id]"
                                                                    value="{{$valueOVwR['relation_id']}}">
                                                            <input id="option_name_{{$optionId}}"
                                                                    field-name="option_name" type="hidden"
                                                                    name="product_data[options][{{$optionId}}][option_name]"
                                                                    value="{{$optionName}}">
                                                            <input id="option_id_{{$optionId}}" field-name="option_name"
                                                                    type="hidden"
                                                                    name="product_data[options][{{$optionId}}][option_id]"
                                                                    value="{{$optionId}}">
                                                            <input id="shop_id_{{$optionId}}" field-name="shop_id"
                                                                    type="hidden"
                                                                    name="product_data[options][{{$optionId}}][shop_id]"
                                                                    value="{{$valueOVwR['shop_id']}}">
                                                            <input id="option_type_{{$optionId}}"
                                                                    field-name="option_type" type="hidden"
                                                                    name="product_data[options][{{$optionId}}][option_type]"
                                                                    value="{{$valueOVwR['option_type']}}">
                                                            <input id="description_{{$optionId}}"
                                                                    field-name="description" type="hidden"
                                                                    name="product_data[options][{{$optionId}}][description]"
                                                                    value="{{$valueOVwR['description']}}">
                                                            <input id="comment_{{$optionId}}" field-name="comment"
                                                                    type="hidden"
                                                                    name="product_data[options][{{$optionId}}][comment]"
                                                                    value="{{$valueOVwR['comment']}}">
                                                            <input id="status_{{$optionId}}" field-name="status"
                                                                    type="hidden"
                                                                    name="product_data[options][{{$optionId}}][status]"
                                                                    value="{{$valueOVwR['status']}}">
                                                            <input id="required_{{$optionId}}" field-name="required"
                                                                    type="hidden"
                                                                    name="product_data[options][{{$optionId}}][required]"
                                                                    value="{{$valueOVwR['required']}}">
                                                        </td>
                                                        <td>
                                                            <div role="group" class="btn-group ">
                                                                <button aria-expanded="false" data-toggle="dropdown"
                                                                        class="btn btn-default dropdown-toggle"
                                                                        type="button">
                                                                    <i class="fa fa-cog"></i>&nbsp;
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul role="menu" class="dropdown-menu">
                                                                    <li>
                                                                        <a total-variant="{{count($optionVariants)}}"
                                                                                href="javascript:void(0);"
                                                                                class="edit-option" data-id="{{$optionId}}">
                                                                            <i class="fa fa-pencil"
                                                                                    data-id="{{$optionId}}"></i>&nbsp;Edit</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0);"
                                                                                class="delete-option"
                                                                                data-id="{{$optionId}}">
                                                                            <i class="fa fa-trash"></i>&nbsp;Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label for="product_option_status_{{$optionId}}_active"
                                                                    class="col-sm-6">
                                                                <input field-name="option_status" type="radio"
                                                                        name="product_data[options][{{$optionId}}][status]"
                                                                        id="product_option_status_{{$optionId}}_active"
                                                                        value="1" checked> Active </label>
                                                            <label for="product_option_status_{{$optionId}}_disabled"
                                                                    class="col-sm-6">
                                                                <input field-name="option_status" type="radio"
                                                                        name="product_data[options][{{$optionId}}][status]"
                                                                        id="product_option_status_{{$optionId}}_disabled"
                                                                        value="2"> Disabled </label>
                                                        </td>

                                                        @if ($optionVariants != '')
                                                            @foreach($optionVariants as $keyOV =>$valueOV)
                                                                <input type="hidden"
                                                                        class="all-data-of-a-variant hidden"
                                                                        variant_id="{{$valueOV['VID']}}"
                                                                        variant_name="{{$valueOV['VN']}}"
                                                                        price_modifier="{{$valueOV['PM']}}"
                                                                        price_modifier_type="{{$valueOV['PMT']}}"
                                                                        weight_modifier="{{$valueOV['WM']}}"
                                                                        weight_modifier_type="{{$valueOV['WMT']}}"
                                                                        status="{{$valueOV['STTS']}}">
                                                            @endforeach
                                                        @endif

                                                    </tr>

                                                @endif
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>

                                    <!--<div class="form-group">
                                    <div class="col-md-12" id="toAppendOptionDiv">
                                    <div class="col-md-6">
                                    <div class="portlet box blue-hoki">
                                    <div class="portlet-title">
                                    <div class="caption">
                                    <i class="fa fa-gift"></i>Portlet
                                    </div>
                                    <div class="tools">
                                    <a href="javascript:;" class="collapse"> </a>
                                    <a href="javascript:;" class="remove"> </a>
                                    </div>
                                    </div>
                                    <div class="portlet-body">
                                    <div class="scroller" style="height:300px">
                                    <p> Duis mollis, est non commodo luctus, nisi erat porttitor
                                    ligula, eget lacinia odio sem nec elit. Cras mattis
                                    consectetur purus sit amet fermentum. est non commodo
                                    luctus, nisi erat porttitor ligula, eget lacinia odio sem
                                    nec elit. Cras mattis consectetur purus sit amet fermentum.
                                    Duis mollis, est non commodo luctus, nisi erat porttitor
                                    ligula, eget lacinia odio sem nec elit. Cras mattis
                                    consectetur purus sit amet fermentum. est non commodo
                                    luctus, nisi erat porttitor ligula, eget lacinia odio sem
                                    nec elit. Cras mattis consectetur purus sit amet
                                    fermentum. </p>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>-->
                                    <!-- OPTION COMBINATION MODAL START-->
                                    <div class="modal fade bs-modal-lg" id="option_combinations_modal" role="dialog">
                                        <div class="modal-dialog  modal-lg">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title"><b>Option Combinations</b></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-horizontal">
                                                        <div class="panel-body">
                                                            <div class="row" style="  height: 450px; overflow-y: auto;">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered"
                                                                            id="option-combination-table">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Quantity</th>
                                                                            {{--<th>Bar code</th>--}}
                                                                            <th>Exclude combination</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="variantCombinationTBody">

                                                                        @if($dataOptVarCombs['code'] == 200)
                                                                            @foreach($dataOptVarCombs['data'] as $keyOVC => $valueOVC)
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php echo $valueOVC['nameString']; ?>
                                                                                        <input name="product_data[opt_combination][existing][{{$valueOVC['variant_ids']}}][newflag]" type="hidden" value="{{$valueOVC['varFlagString']}}"/>
                                                                                        <input name="product_data[opt_combination][existing][{{$valueOVC['variant_ids']}}][combination_id]" type="hidden" value="{{$valueOVC['combination_id']}}"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input name="product_data[opt_combination][existing][{{$valueOVC['variant_ids']}}][quantity]" type="number" value="{{$valueOVC['quantity']}}" min=1 dataCid="{{$valueOVC['variant_ids']}}"/>
                                                                                    </td>
                                                                                {{--<td>--}}
                                                                                {{--<input name="product_data[opt_combination][existing][{{$valueOVC['combinationId']}}][barcode]"/>--}}
                                                                                {{--</td>--}}
                                                                                <!--todo med:med add code for image for combination here-->
                                                                                    <td>
                                                                                        <input name="product_data[opt_combination][existing][{{$valueOVC['combination_id']}}][excludeflag]" type="checkbox" @if($valueOVC['exception_flag'] == 1) checked @endif/>
                                                                                    </td>
                                                                                </tr>

                                                                            @endforeach
                                                                        @endif

                                                                        <!-- OPTION COMBINATIONS WILL BE ADDED/UPDATED/REMOVED HERE-->
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="text-align: center">
                                                    {{--<button data-dismiss="modal" class="btn default" type="button">Close</button>--}}
                                                    {{--<button data-dismiss="modal" class="btn blue" type="button"--}}
                                                    {{--id="save-option-combinations">--}}
                                                    {{--Save and close--}}
                                                    {{--</button>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- OPTION COMBINATION MODAL END-->

                                    <div class="form-actions" align="center">
                                        <button type="submit" class="btn btn-primary"
                                                name="product_data[updateFormName]" value="options">
                                            Update options
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="tab_1_5">
                                <form class="form-horizontal" method="post" autocomplete="on" id="editShippingForm">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Weight ({{$weightSymbol}})</label>

                                        <div class="col-sm-4">
                                            <input type="text" class="form-control float-type" id="weight"
                                                    name="product_data[shipping_properties][weight]" value="@if(isset(old('product_data')['shipping_properties']['weight'])){{old('product_data')['shipping_properties']['weight']}}@else{{$productData['weight']}}@endif">
                                            <span class="error">{!! $errors->first('weight') !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Free shipping</label>

                                        <div class="col-sm-4">
                                            <input type="checkbox"
                                                    name="product_data[shipping_properties][free_shipping]"
                                                    class="form-control m-b-sm"
                                                    id="free_shipping"
                                                    @if(isset(old('product_data')['shipping_properties']['free_shipping'])) checked @endif />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Shipping freight ({{$priceSymbol}}
                                            )</label>

                                        <div class="col-sm-4">
                                            <input type="text" class="form-control float-type" id="shipping_freight"
                                                    name="product_data[shipping_properties][shipping_freight]"
                                                    value="@if(isset(old('product_data')['shipping_properties']['shipping_freight'])){{old('product_data')['shipping_properties']['shipping_freight']}}@else{{$productData['shipping_freight']}}@endif">
                                            <span class="error">{!! $errors->first('shipping_freight') !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Items in a box
                                            <i class="fa fa-question-circle" data-toggle="tooltip"
                                                    title="Use this field to define to minimum and maximum number of product item to be shipped in a separate box. Enter non-zero value and specify the box dimensions below"></i></label>

                                        <div class="col-sm-4">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control items_in_a_box integer-type" id="min_items" name="product_data[shipping_properties][min_items]" value="@if(isset(old('product_data')['shipping_properties']['min_items'])){{old('product_data')['shipping_properties']['min_items']}}@else{{$productData['min_qty']}}@endif" placeholder="min">
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control items_in_a_box integer-type" id="max_items" name="product_data[shipping_properties][max_items]" value="@if(isset(old('product_data')['shipping_properties']['max_items'])){{old('product_data')['shipping_properties']['max_items']}}@else{{$productData['max_qty']}}@endif" placeholder="max">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Box length</label>

                                        <div class="col-sm-4">
                                            <input type="text" class="form-control box_dimension float-type" id="box_length" name="product_data[shipping_properties][box_length]" @if(isset(old('product_data')['shipping_properties']['box_length'])) value="{{old('product_data')['shipping_properties']['box_length']}}" @endif disabled>
                                            <span class="error">{!! $errors->first('box_length') !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Box width</label>

                                        <div class="col-sm-4">
                                            <input type="text" class="form-control box_dimension float-type" id="box_width" name="product_data[shipping_properties][box_width]" @if(isset(old('product_data')['shipping_properties']['box_width'])) value="{{old('product_data')['shipping_properties']['box_width']}}" @endif disabled>
                                            <span class="error">{!! $errors->first('box_width') !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Box height</label>

                                        <div class="col-sm-4">
                                            <input type="text" class="form-control box_dimension float-type" id="box_height" name="product_data[shipping_properties][box_height]" @if(isset(old('product_data')['shipping_properties']['box_height'])) value="{{old('product_data')['shipping_properties']['box_height']}}" @endif disabled>
                                            <span class="error">{!! $errors->first('box_height') !!}</span>
                                        </div>
                                    </div>

                                    <div class="form-actions" align="center">
                                        <button type="submit" class="btn btn-primary"
                                                name="product_data[updateFormName]" value="shipping">
                                            Update shipping info
                                        </button>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane" id="tab_1_6">
                                <form class="form-horizontal" method="post" autocomplete="on" id="editDiscountForm">

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 10%">Quantity</th>
                                                <th style="width: 25%">Value</th>
                                                <th style="width: 25%">Type
                                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                                            title="Fixed amount/percentage to be taken off the price."></i>
                                                </th>
                                                {{--<th>User group</th>--}}
                                                <th style="width: 15%">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="quantityDiscountTBody">
                                            @if(isset(old('product_data')['quantity_discount'])
                                            && (current(old('product_data')['quantity_discount'])['quantity']!=''
                                            || current(old('product_data')['quantity_discount'])['value']!=''))
                                                @foreach(old('product_data')['quantity_discount'] as $quantityDiscountKey=>$quantityDiscountValue)
                                                    @if($quantityDiscountValue['quantity']!=''||$quantityDiscountValue['value']!='')
                                                        <tr>
                                                            <td><input type="text" class="form-control integer-type"
                                                                        name="product_data[quantity_discount][{{$quantityDiscountKey}}][quantity]"
                                                                        value="{{$quantityDiscountValue['quantity']}}">
                                                                <span class="error">{!! $errors->first('quantity_discount.'.$quantityDiscountKey.'.quantity') !!}</span>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="col-sm-6">
                                                                        <input type="text"
                                                                                class="form-control float-type"
                                                                                name="product_data[quantity_discount][{{$quantityDiscountKey}}][value]"
                                                                                value="{{$quantityDiscountValue['value']}}">
                                                                        <span class="error">{!! $errors->first('quantity_discount.'.$quantityDiscountKey.'.value') !!}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="col-sm-4">
                                                                        <select name="product_data[quantity_discount][{{$quantityDiscountKey}}][type]"
                                                                                class="form-control">
                                                                            <?php $priceModifierType = array('1' => $priceSymbol, '2' => '%'); ?>
                                                                            @foreach($priceModifierType as $key=>$value)
                                                                                <option value="{{$key}}"
                                                                                        @if(isset(old('product_data')['quantity_discount'])&&$key==old('product_data')['quantity_discount'][$quantityDiscountKey]['type']) selected @endif>{{$value}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                            {{--<td>--}}
                                                            {{--<div class="col-sm-12">--}}
                                                            {{--<select name="product_data[quantity_discount][{{$quantityDiscountKey}}][user_group]"--}}
                                                            {{--class="form-control">--}}
                                                            {{--<?php $status = array('1' => 'Active', '2' => 'Inactive'); ?>--}}
                                                            {{--@foreach($status as $key=>$value)--}}
                                                            {{--<option value="{{$key}}"--}}
                                                            {{--@if(isset(old('product_data')['quantity_discount'])&&$key==old('product_data')['quantity_discount'][$quantityDiscountKey]['user_group']) selected @endif>{{$value}}</option>--}}
                                                            {{--@endforeach--}}
                                                            {{--</select>--}}
                                                            {{--</div>--}}

                                                            {{--</td>--}}
                                                            <td>
                                                                <a href="javascript:void(0);" class="col-sm-1 add-more"><i class="fa fa-plus"></i></a>
                                                                <a href="javascript:void(0);" class="col-sm-1 remove"><i class="fa fa-remove"></i></a>
                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control integer-type" name="product_data[quantity_discount][0][quantity]">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control float-type" name="product_data[quantity_discount][0][value]">
                                                    </td>
                                                    <td>
                                                        <select name="product_data[quantity_discount][0][type]" class="form-control">
                                                            <?php $priceModifierType = array('1' => 'Absolute (' . $priceSymbol . ')', '2' => 'Percent (%)'); ?>
                                                            @foreach($priceModifierType as $key=>$value)
                                                                <option value="{{$key}}">{{$value}}</option>
                                                            @endforeach
                                                        </select>

                                                    </td>
                                                    {{--<td>--}}
                                                    {{--<select name="product_data[quantity_discount][0][user_group]"--}}
                                                    {{--class="form-control">--}}
                                                    {{--<option value="1">Active</option>--}}
                                                    {{--<option value="2">Inactive</option>--}}
                                                    {{--</select>--}}
                                                    {{--</td>--}}
                                                    <td>
                                                        <a href="javascript:void(0);" class="col-sm-1 add-more"><i class="fa fa-plus"></i></a>
                                                        <a href="javascript:void(0);" class="col-sm-1 remove"><i class="fa fa-remove"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-actions" align="center">
                                        <button type="submit" class="btn btn-primary" name="product_data[updateFormName]" value="discounts">
                                            Update discounts
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="tab_1_7">

                            </div>

                            <div class="tab-pane" id="tab_1_8">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th width="50%">Tabs</th>
                                        <th width="50%">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Description</td>
                                        <td>
                                            <label for="product_tabs_description_active" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][description]" id="product_tabs_description_active" value="1" checked>
                                                Active
                                            </label>
                                            <label for="product_tabs_description_disabled" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][description]" id="product_tabs_description_disabled" value="2">
                                                Disabled
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Features</td>
                                        <td>
                                            <label for="product_tabs_features_active" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][features]" id="product_tabs_features_active" value="1" checked>
                                                Active
                                            </label>
                                            <label for="product_tabs_features_disabled" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][features]" id="product_tabs_features_disabled" value="2">
                                                Disabled
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tags</td>
                                        <td>
                                            <label for="product_tabs_tags_active" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][tags]" id="product_tabs_tags_active" value="1" checked>
                                                Active </label>
                                            <label for="product_tabs_tags_disabled" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][tags]" id="product_tabs_tags_disabled" value="2">
                                                Disabled </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Reviews</td>
                                        <td>
                                            <label for="product_tabs_reviews_active" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][reviews]" id="product_tabs_reviews_active" value="1" checked>
                                                Active
                                            </label> <label for="product_tabs_reviews_disabled" class="col-sm-3">
                                                <input type="radio" name="product_data[product_tabs][reviews]" id="product_tabs_reviews_disabled" value="2">
                                                Disabled </label>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="form-actions" align="center">
                                    <button type="submit" class="btn btn-primary" name="product_data[updateFormName]" value="tabs">
                                        Update tabs
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_1_9">

                            </div>
                            {{--//VARIANT DETAILS TAB--}}
                        </div>

                    @elseif(isset($productData) && empty($productData))
                        <div style="height: 100%;">
                            <h1>{{$message}}<br><br><br></h1>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(isset($productData) && !empty($productData))

        <div class="modal fade bs-modal-lg" id="edit_option_modal" role="dialog">
            <div class="modal-dialog  modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><b>Editing option: <span id="modal_title"></span></b></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <div class="panel panel-white">

                                    <div class="panel-body">
                                        <div class="portlet-title tabbable-line">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#option_tab" data-toggle="tab">General</a>
                                                </li>
                                                <li><a href="#variant_tab" data-toggle="tab">Variants</a></li>
                                            </ul>
                                        </div>
                                        <div class="tab-content" style="  height: 300px; overflow-y: auto;">
                                            {{--GENERAL DETAILS TAB--}}
                                            <div class="tab-pane active" id="option_tab">
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" id="option_id_for_edit"
                                                            for="option_id">
                                                    <label class="col-sm-2 control-label">Name</label>

                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control"
                                                                id="option_name_for_edit"
                                                                for="option_name">
                                                    </div>
                                                </div>

                                                {{--<div class="form-group">--}}
                                                {{--<label class="col-sm-2 control-label">Supplier</label>--}}

                                                {{--<div class="col-sm-4">--}}
                                                {{--<select name="option_data[shop_id]" class="form-control m-b-sm"--}}
                                                {{--for="shop_id">--}}
                                                {{--<option value="0">None</option>--}}
                                                {{--@if(isset($allShop))--}}
                                                {{--@foreach($allShop as $key=>$value)--}}
                                                {{--<option value="{{$value->id}}"--}}
                                                {{--@if(isset(old('option_data')['shop_id'])&&$value->id==old('option_data')['shop_id']) selected @endif>{{$value->shop_name}}</option>--}}
                                                {{--@endforeach--}}
                                                {{--@endif--}}

                                                {{--</select>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Type</label>

                                                    <div class="col-sm-4">
                                                        <select name="option_data[option_type]"
                                                                class="form-control m-b-sm"
                                                                id="option_type_for_edit" for="option_type">
                                                            <?php $optionType = array('1' => 'Select box', '2' => 'Radio group', '3' => 'Check box'); ?>
                                                            @foreach($optionType as $key=>$value)
                                                                <option value="{{$key}}">{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Description</label>

                                                    <div class="col-sm-4">
                                                    <textarea name="option_data[description]"
                                                            class="form-control"
                                                            id="option_desc_for_edit" for="description"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Comment</label>

                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control"
                                                                name="option_data[comment]"
                                                                id="option_comment_for_edit" for="comment">
                                                        <small>Enter your comment to appear below the option</small>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Required</label>

                                                    <div class="col-sm-4">
                                                        <input type="checkbox" class="form-control"
                                                                name="option_data[required]"
                                                                @if(isset(old('option_data')['required'])) checked @endif
                                                        id="option_required_for_edit" for="required">
                                                    </div>
                                                </div>
                                            </div>
                                            {{--//GENERAL DETAILS TAB--}}
                                            {{--VARIANT DETAILS TAB--}}
                                            <div class="tab-pane" id="variant_tab">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Price modifier / Type</th>
                                                            <th>Weight modifier / Type</th>
                                                            <th>Status</th>
                                                            <th>Extra</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="variantTBody"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            {{--//VARIANT DETAILS TAB--}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button data-dismiss="modal" class="btn default" type="button">Close</button>
                        <button class="btn blue" type="button" id="save-option-details">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection

@section('pagejavascripts')
    @if(isset($productData) && !empty($productData))

        {{--<script src="/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>--}}
        <script src="/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
        <script src="/assets/plugins/select2/js/select2.js"></script>
        <script src="/assets/global/scripts/app.min.js"></script>
        <script src="/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
        <script src="/assets/global/plugins/underscore-js/underscore.js"></script>
        <script src="/assets/global/plugins/plupload/js/plupload.full.min.js"></script>
        <script type="text/javascript" src="/assets/global/plugins/bootbox/bootbox.min.js"></script>

        <script>
            $(document).ready(function () {
                App.initPortlet();
                App.initSlimScroll(".scroller");
            });

            var status = "info";
            @if(session('message')!='')
                    @if(session('code') == '200')
                    status = "success";
            @elseif(session('code') == '400')
                    status = "error";
            @endif
                    toastr[status]("{{session('message')}}");
                    @endif

            var optionAddedRemovedFlag = false;

            $(document).ready(function () {
                //SUBMIT CHANGED VALUE ONLY BLOCK START
//                $(document.body).on('change', 'input:not([type="checkbox"],[type="hidden"]), select', function () {
//                    $(this).addClass("changed");
//                });
//
//                $('form').submit(function () {
//                    $('form').find('input:not([type="checkbox"],[type="hidden"],.changed)').prop("disabled", "disabled");
//                    $('form').find('select:not(.changed)').prop("disabled", "disabled");
//                    return true;
//                });
                //SUBMIT CHANGED VALUE ONLY BLOCK END

//            TODO UNCOMMENT THIS TO GET STYLED DROPDOWN
                /* $("#select_category").select2({
                 allowClear: !0,
                 placeholder: "Select a category",
                 width: "70%"
                 }); */

                $("#select_option").select2({
                    allowClear: !0,
                    placeholder: "Select an option",
                    width: "100%"
                });

                $(document.body).on('click', '#add-another-option', function (e) {
                    var optionId = $('#select_option').val();
//                alert(optionId);
                    var newOptionFlag = true;
                    if ($("#optionTableBody tr").length > 0) {
                        $.each($("#optionTableBody tr"), function (i, v) {
                            if (optionId == $(this).attr('option-id'))
                                newOptionFlag = false;
                        });
                    }
                    //todo add validation to add not more than 3 options

                    if (optionId != '') {
                        if (newOptionFlag) {
                            $.ajax({
                                url: '/supplier/product-ajax-handler',
                                type: 'POST',
                                datatype: 'json',
                                data: {
                                    method: 'getOptionVariantsWhere',
                                    optionId: optionId
                                },
                                success: function (response) {
                                    response = $.parseJSON(response);
                                    //toastr[response['status']](response['msg']);
                                    console.log(response);
                                    if (response['code'] == 200) {
                                        var toAppend = '';
                                        $("#optionTable").removeClass('hidden');
                                        var optionId = response['data']['optionDetails']['option_id'];
                                        var optionName = response['data']['optionDetails']['option_name'];
                                        var optionVariants = response['data']['optionVariants'];
                                        if (optionName != '') {
                                            toAppend += '<tr id="option_id_' + optionId + '" option-id="' + optionId + '"> ';

                                            toAppend += '<td>';
                                            toAppend += '<a total-variant="' + optionVariants.length + '" style="text-decoration: none" href="javascript:void(0);" class="edit-option option-name" data-id="' + optionId + '">' + optionName + '</a>';
                                            toAppend += '<input id="option_name_' + optionId + '" field-name="option_name" type="hidden" name="product_data[options][' + optionId + '][option_name]" value="' + optionName + '">';
                                            toAppend += '<input id="option_id_' + optionId + '" field-name="option_name" type="hidden" name="product_data[options][' + optionId + '][option_id]" value="' + optionId + '">';
                                            toAppend += '<input id="shop_id_' + optionId + '" field-name="shop_id" type="hidden" name="product_data[options][' + optionId + '][shop_id]" value="' + response['data']['optionDetails']['shop_id'] + '">';
                                            toAppend += '<input id="option_type_' + optionId + '" field-name="option_type" type="hidden" name="product_data[options][' + optionId + '][option_type]" value="' + response['data']['optionDetails']['option_type'] + '">';
                                            toAppend += '<input id="description_' + optionId + '" field-name="description" type="hidden" name="product_data[options][' + optionId + '][description]" value="' + response['data']['optionDetails']['description'] + '">';
                                            toAppend += '<input id="comment_' + optionId + '" field-name="comment" type="hidden" name="product_data[options][' + optionId + '][comment]" value="' + response['data']['optionDetails']['comment'] + '">';
                                            toAppend += '<input id="status_' + optionId + '" field-name="status" type="hidden" name="product_data[options][' + optionId + '][status]" value="' + response['data']['optionDetails']['status'] + '">';
                                            toAppend += '<input id="required_' + optionId + '" field-name="required" type="hidden" name="product_data[options][' + optionId + '][required]" value="' + response['data']['optionDetails']['required'] + '">';
                                            toAppend += '</td>';

                                            toAppend += '<td>';
                                            toAppend += '<div role="group" class="btn-group ">';
                                            toAppend += '<button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">';
                                            toAppend += '<i class="fa fa-cog"></i>&nbsp; <span class="caret"></span>';
                                            toAppend += '</button>';
                                            toAppend += '<ul role="menu" class="dropdown-menu">';
                                            toAppend += '<li><a total-variant="' + optionVariants.length + '" href="javascript:void(0);" class="edit-option" data-id="' + optionId + '">';
                                            toAppend += '<i class="fa fa-pencil" data-id="' + optionId + '"></i>&nbsp;Edit</a> </li>';
                                            toAppend += '<li><a href="javascript:void(0);" class="delete-option" data-id="' + optionId + '">';
                                            toAppend += '<i class="fa fa-trash"></i>&nbsp;Delete</a> </li>';
                                            toAppend += '</ul>';
                                            toAppend += '</div>';
                                            toAppend += '</td>';

                                            toAppend += '<td>';
                                            toAppend += '<label for="product_option_status_' + optionId + '_active" class="col-sm-6">';
                                            toAppend += '<input field-name="option_status"  type="radio" name="product_data[options][' + optionId + '][status]" id="product_option_status_' + optionId + '_active" value="1" checked> Active </label>';
                                            toAppend += '<label for="product_option_status_' + optionId + '_disabled" class="col-sm-6">';
                                            toAppend += '<input field-name="option_status" type="radio" name="product_data[options][' + optionId + '][status]" id="product_option_status_' + optionId + '_disabled" value="2"> Disabled </label>';
                                            toAppend += '</td>';


                                            if (optionVariants != '') {
                                                $.each(optionVariants, function (i, a) {
                                                    console.log(a['variant_id']);
                                                    toAppend += '<input type="hidden" class="all-data-of-a-variant hidden"  variant_id="' + a['variant_id'] + '" variant_name="' + a['variant_name'] + '" price_modifier="' + a['price_modifier'] + '" price_modifier_type="' + a['price_modifier_type'] + '" weight_modifier="' + a['weight_modifier'] + '" weight_modifier_type="' + a['weight_modifier_type'] + '" status="' + a['status'] + '">';
//                                                toAppend += '<input id="variant_name_' + optionId + '_' + a['variant_id'] + '" class="variant-data-' + optionId + ' variant-id-' + a['variant_id'] + '" field-name="variant_name" type="hidden" varaint-id="' + a['variant_id'] + '" name="product_data[options][' + optionId + '][option_variants][variant_name][]" value="' + a['variant_name'] + '">';
//                                                toAppend += '<input id="price_modifier_' + optionId + '_' + a['variant_id'] + '" class="variant-data-' + optionId + ' variant-id-' + a['variant_id'] + '" field-name="price_modifier" type="hidden" varaint-id="' + a['variant_id'] + '" name="product_data[options][' + optionId + '][option_variants][price_modifier][]" value="' + a['price_modifier'] + '">';
//                                                toAppend += '<input id="price_modifier_type_' + optionId + '_' + a['variant_id'] + '" class="variant-data-' + optionId + ' variant-id-' + a['variant_id'] + '" field-name="price_modifier_type" type="hidden" varaint-id="' + a['variant_id'] + '" name="product_data[options][' + optionId + '][option_variants][price_modifier_type][]" value="' + a['price_modifier_type'] + '">';
//                                                toAppend += '<input id="weight_modifier_' + optionId + '_' + a['variant_id'] + '" class="variant-data-' + optionId + ' variant-id-' + a['variant_id'] + '" field-name="weight_modifier" type="hidden" varaint-id="' + a['variant_id'] + '" name="product_data[options][' + optionId + '][option_variants][weight_modifier][]" value="' + a['weight_modifier'] + '">';
//                                                toAppend += '<input id="weight_modifier_type_' + optionId + '_' + a['variant_id'] + '" class="variant-data-' + optionId + ' variant-id-' + a['variant_id'] + '" field-name="weight_modifier_type" type="hidden" varaint-id="' + a['variant_id'] + '" name="product_data[options][' + optionId + '][option_variants][weight_modifier_type][]" value="' + a['weight_modifier_type'] + '">';
                                                });
                                            }
                                            toAppend += '</tr>';
                                            $("#optionTableBody").append(toAppend);
                                        }
//                                        $.each(response['data'], function (i, a) {
//                                        });
//                                $('#toAppendOptionDiv').html(toAppend);
                                        optionAddedRemovedFlag = true;
                                        App.initAjax();
                                    } else {
                                        toastr['error'](response['message']);
                                    }
                                },
                                error: function (response) {
                                    toastr['error']("Something went wrong. Please try again");
                                    console.log(response);
                                }
                            });
                        } else {
                            toastr['error']("Sorry, you can't add same option twice.");
                        }
                    } else {
                        toastr['error']("Please select an option to add.");
                    }
                });

                var variantCounter = 1;
                $(document.body).on('click', '.add-more', function () {
                    var toAppendNewTableRow = '<tr>';
                    toAppendNewTableRow += '<td><input type="text" class="form-control integer-type" name="product_data[quantity_discount][' + variantCounter + '][quantity]"></td>';

                    toAppendNewTableRow += '<td><input type="text" class="form-control float-type" name="product_data[quantity_discount][' + variantCounter + '][value]"></td>';
                    toAppendNewTableRow += '<td>';
                    toAppendNewTableRow += '<select name="product_data[quantity_discount][' + variantCounter + '][type]" class="form-control">';
                    toAppendNewTableRow += '<option value="1">Absolute ({{$priceSymbol}})</option>';
                    toAppendNewTableRow += '<option value="2">Percent (%)</option>';
                    toAppendNewTableRow += '</select>';
                    toAppendNewTableRow += '</td>';

                    toAppendNewTableRow += '<td>';
                    toAppendNewTableRow += '<a class="col-sm-1 add-more"><i class="fa fa-plus"></i></a>';
                    toAppendNewTableRow += '<a class="col-sm-1 remove"><i class="fa fa-remove"></i></a>';
                    toAppendNewTableRow += '</td>';

                    toAppendNewTableRow += ' </tr>';

                    $("#quantityDiscountTBody").append(toAppendNewTableRow);
                    variantCounter++;
                });

                $(document.body).on('click', '.remove', function () {
                    var count = $('#quantityDiscountTBody').children('tr').length;
                    if (count > 1) $(this).closest('tr').remove();
                });

                if ($(".tab-content").find('.error').text()) {
                    $.each($(".tab-content").find('.error'), function (index, value) {
                        if ($(this).text()) {
                            $(".tab-content").children('.tab-pane').removeClass('active');
                            $(this).closest('.tab-pane').addClass('active');
                            $(".nav-tabs").children('li').removeClass('active');
                            var id = $(this).closest('.tab-pane').attr('id');
                            $.each($(".nav-tabs").children('li'), function (i, a) {
                                if (('#' + id) == $(this).children('a').attr('href'))
                                    $(this).addClass('active');
                            });
                            return false;
                        }
                    });
                }

                $(".items_in_a_box").on('keyup', function () {
                    $(".box_dimension").prop('disabled', (($('#min_items').val() == 0) && ($('#max_items').val() == 0)));
                });

                $(document.body).on('click', '.delete-option', function () {
                    $(this).closest('tr').remove();
                    if ($("#optionTableBody tr").length == 0) $("#optionTable").addClass('hidden');
                    optionAddedRemovedFlag = true;
                });

                var variantCounterForEdit = 1;
                $(document.body).on('click', '.edit-option', function () {
                    var obj = $(this);
                    var optionName = obj.closest('tr').find('[field-name=option_name]').val();
                    var optionId = obj.attr('data-id');
                    var totalVariant = variantCounterForEdit = obj.attr('total-variant');
//                console.log(totalVariant);
                    $.each($("#option_id_" + optionId), function (i, v) {
                        $("#variantTBody").empty();
                        $("#save-option-details").attr('option_id', optionId);
                        $("#modal_title").html(optionName);
                        $("#option_name_for_edit").val(optionName);
                        $("#option_id_for_edit").val(optionId);
                        $("#option_desc_for_edit").val(obj.closest('tr').find('[field-name=description]').val());
                        $("#option_comment_for_edit").val(obj.closest('tr').find('[field-name=comment]').val());
                        if ($(this).find(".all-data-of-a-variant").length > 0) {
                            $.each($(this).find(".all-data-of-a-variant"), function (i, v) {
//                            console.log(v);
                                var newVarClass = '';
                                if ($(v).hasClass('new-variant')) {
                                    newVarClass = 'new-variant';
                                }
                                var toAppendVariantDetail = '';
                                toAppendVariantDetail += '<tr class="variant-data ' + newVarClass + '"  variant-id="' + $(this).attr('variant_id') + '">';
                                toAppendVariantDetail += '<td><input field-name="variant_name" type="text" class="form-control variant_name" value="' + $(this).attr('variant_name') + '"></td>';
                                toAppendVariantDetail += '<td>';
                                toAppendVariantDetail += '<div class="form-group">';
                                toAppendVariantDetail += '<div class="col-sm-5">';
                                toAppendVariantDetail += '<input field-name="price_modifier"  type="text" class="form-control float-type price_modifier " value="' + $(this).attr('price_modifier') + '">';
                                toAppendVariantDetail += '</div>';
                                toAppendVariantDetail += '<span class="col-sm-1 separator">/</span>';
                                toAppendVariantDetail += '<div class="col-sm-5">';
                                toAppendVariantDetail += '<select field-name="price_modifier_type" class="form-control price_modifier_type ">';
                                toAppendVariantDetail += '<option value="1">{{$priceSymbol}}</option>';
                                toAppendVariantDetail += '<option value="2">%</option>';
                                toAppendVariantDetail += '</select>';
                                toAppendVariantDetail += '</div>';
                                toAppendVariantDetail += '</div>';
                                toAppendVariantDetail += '</td>';

                                toAppendVariantDetail += '<td>';
                                toAppendVariantDetail += '<div class="form-group">';
                                toAppendVariantDetail += '<div class="col-sm-5">';
                                toAppendVariantDetail += '<input field-name="weight_modifier" type="text" class="form-control weight_modifier float-type " value="' + $(this).attr('weight_modifier') + '">';
                                toAppendVariantDetail += '</div>';
                                toAppendVariantDetail += '<span class="col-sm-1 separator">/</span>';

                                toAppendVariantDetail += '<div class="col-sm-5">';
                                toAppendVariantDetail += '<select field-name="weight_modifier_type" class="form-control weight_modifier_type ">';
                                toAppendVariantDetail += '<option value="1">{{$weightSymbol}}</option>';
                                toAppendVariantDetail += '<option value="2">%</option>';
                                toAppendVariantDetail += '</select>';
                                toAppendVariantDetail += '</div>';
                                toAppendVariantDetail += '</div>';
                                toAppendVariantDetail += '</td>';

                                toAppendVariantDetail += '<td>';
                                toAppendVariantDetail += '<select field-name="status" class="form-control status ">';
                                toAppendVariantDetail += '<option value="1">Active</option>';
                                toAppendVariantDetail += '<option value="2">Inactive</option>';
                                toAppendVariantDetail += '</select>';
                                toAppendVariantDetail += '</td>';

                                toAppendVariantDetail += '<td>';
                                toAppendVariantDetail += '<a href="javascript:void(0);" class="col-sm-1 for-edit-add-more" option_id="' + optionId + '"><i class="fa fa-plus"></i></a>';
                                toAppendVariantDetail += '<a href="javascript:void(0);" class="col-sm-1 for-edit-remove" option_id="' + optionId + '"><i class="fa fa-remove"></i></a>';
                                toAppendVariantDetail += '</td>';
                                toAppendVariantDetail += '</tr>';
                                $("#variantTBody").append(toAppendVariantDetail);

                            });
                        } else {
                            var toAppendVariantDetail = '';
                            toAppendVariantDetail += '<tr class="variant-data new-variant"  variant-id="' + newVariantCounter + '">';
                            toAppendVariantDetail += '<td><input field-name="variant_name" type="text" class="form-control variant_name "></td>';
                            toAppendVariantDetail += '<td>';
                            toAppendVariantDetail += '<div class="form-group">';
                            toAppendVariantDetail += '<div class="col-sm-5">';
                            toAppendVariantDetail += '<input field-name="price_modifier" type="text" class="form-control price_modifier float-type " >';
                            toAppendVariantDetail += '</div>';
                            toAppendVariantDetail += '<span class="col-sm-1 separator">/</span>';
                            toAppendVariantDetail += '<div class="col-sm-5">';
                            toAppendVariantDetail += '<select field-name="price_modifier_type" class="form-control price_modifier_type ">';
                            toAppendVariantDetail += '<option value="1">{{$priceSymbol}}</option>';
                            toAppendVariantDetail += '<option value="2">%</option>';
                            toAppendVariantDetail += '</select>';
                            toAppendVariantDetail += '</div>';
                            toAppendVariantDetail += '</div>';
                            toAppendVariantDetail += '</td>';

                            toAppendVariantDetail += '<td>';
                            toAppendVariantDetail += '<div class="form-group">';
                            toAppendVariantDetail += '<div class="col-sm-5">';
                            toAppendVariantDetail += '<input field-name="weight_modifier" type="text" class="form-control weight_modifier  float-type ">';
                            toAppendVariantDetail += '</div>';
                            toAppendVariantDetail += '<span class="col-sm-1 separator">/</span>';

                            toAppendVariantDetail += '<div class="col-sm-5">';
                            toAppendVariantDetail += '<select field-name="weight_modifier_type" class="form-control weight_modifier_type">';
                            toAppendVariantDetail += '<option value="1">{{$weightSymbol}}</option>';
                            toAppendVariantDetail += '<option value="2">%</option>';
                            toAppendVariantDetail += '</select>';
                            toAppendVariantDetail += '</div>';
                            toAppendVariantDetail += '</div>';
                            toAppendVariantDetail += '</td>';

                            toAppendVariantDetail += '<td>';
                            toAppendVariantDetail += '<select field-name="status" class="form-control status ">';
                            toAppendVariantDetail += '<option value="1">Active</option>';
                            toAppendVariantDetail += '<option value="2">Inactive</option>';
                            toAppendVariantDetail += '</select>';
                            toAppendVariantDetail += '</td>';

                            toAppendVariantDetail += '<td>';
                            toAppendVariantDetail += '<a href="javascript:void(0);" class="col-sm-1 for-edit-add-more" option_id="' + optionId + '"><i class="fa fa-plus"></i></a>';
                            toAppendVariantDetail += '<a href="javascript:void(0);" class="col-sm-1 for-edit-remove" option_id="' + optionId + '"><i class="fa fa-remove"></i></a>';
                            toAppendVariantDetail += '</td>';
                            toAppendVariantDetail += '</tr>';
                            $("#variantTBody").append(toAppendVariantDetail);
                        }
                    });
                    $("#edit_option_modal").modal('show');
//                if ($("#optionTableBody tr").length == 0) $("#optionTable").addClass('hidden');
                });

                var newVariantCounter = 1;
                $(document.body).on('click', '.for-edit-add-more', function () {
                    var toAppendVariantDetail = '';
                    var optionId = $(this).attr('option_id');
                    toAppendVariantDetail += '<tr class="variant-data new-variant" variant-id="' + newVariantCounter + '">';
                    toAppendVariantDetail += '<td><input field-name="variant_name" type="text" class="form-control variant_name "></td>';
                    toAppendVariantDetail += '<td>';
                    toAppendVariantDetail += '<div class="form-group">';
                    toAppendVariantDetail += '<div class="col-sm-5">';
                    toAppendVariantDetail += '<input field-name="price_modifier" type="text" class="form-control price_modifier float-type " >';
                    toAppendVariantDetail += '</div>';
                    toAppendVariantDetail += '<span class="col-sm-1 separator">/</span>';
                    toAppendVariantDetail += '<div class="col-sm-5">';
                    toAppendVariantDetail += '<select field-name="price_modifier_type" class="form-control price_modifier_type ">';
                    toAppendVariantDetail += '<option value="1">{{$priceSymbol}}</option>';
                    toAppendVariantDetail += '<option value="2">%</option>';
                    toAppendVariantDetail += '</select>';
                    toAppendVariantDetail += '</div>';
                    toAppendVariantDetail += '</div>';
                    toAppendVariantDetail += '</td>';

                    toAppendVariantDetail += '<td>';
                    toAppendVariantDetail += '<div class="form-group">';
                    toAppendVariantDetail += '<div class="col-sm-5">';
                    toAppendVariantDetail += '<input field-name="weight_modifier" type="text" class="form-control weight_modifier  float-type ">';
                    toAppendVariantDetail += '</div>';
                    toAppendVariantDetail += '<span class="col-sm-1 separator">/</span>';

                    toAppendVariantDetail += '<div class="col-sm-5">';
                    toAppendVariantDetail += '<select field-name="weight_modifier_type" class="form-control weight_modifier_type ">';
                    toAppendVariantDetail += '<option value="1">{{$weightSymbol}}</option>';
                    toAppendVariantDetail += '<option value="2">%</option>';
                    toAppendVariantDetail += '</select>';
                    toAppendVariantDetail += '</div>';
                    toAppendVariantDetail += '</div>';
                    toAppendVariantDetail += '</td>';

                    toAppendVariantDetail += '<td>';
                    toAppendVariantDetail += '<select field-name="status" class="form-control status ">';
                    toAppendVariantDetail += '<option value="1">Active</option>';
                    toAppendVariantDetail += '<option value="2">Inactive</option>';
                    toAppendVariantDetail += '</select>';
                    toAppendVariantDetail += '</td>';

                    toAppendVariantDetail += '<td>';
                    toAppendVariantDetail += '<a href="javascript:void(0);" class="col-sm-1 for-edit-add-more"><i class="fa fa-plus"></i></a>';
                    toAppendVariantDetail += '<a href="javascript:void(0);" class="col-sm-1 for-edit-remove"><i class="fa fa-remove"></i></a>';
                    toAppendVariantDetail += '</td>';
                    toAppendVariantDetail += '</tr>';

                    $("#variantTBody").append(toAppendVariantDetail);
                    variantCounterForEdit++;
                    newVariantCounter++;
                });

                $(document.body).on('click', '.for-edit-remove', function () {
                    var count = $('#variantTBody').children('tr').length;
                    if (count > 1) $(this).closest('tr').remove();
                });

                $(document.body).on('click', '#save-option-details', function () {
                    var obj = $(this);
                    var optionId = $(this).attr('option_id');

                    $("#option_name_" + optionId).val($("#option_name_for_edit").val());
                    $("#option_type_" + optionId).val($("#option_type_for_edit").val());
                    $("#description_" + optionId).val($("#option_desc_for_edit").val());
                    $("#comment_" + optionId).val($("#option_comment_for_edit").val());
                    $("#required_" + optionId).val($("#option_required_for_edit").val());
                    $("#option_id_" + optionId + " .option-name").text($("#option_name_for_edit").val());

                    var modalObj = obj.parents('.modal-content').find(".modal-body");
                    $("#option_id_" + optionId + " .all-data-of-a-variant").remove();
                    $("#option_id_" + optionId + " .option-variant-data").remove();
                    $.each(modalObj.find(".variant-data"), function (i, v) {
                        var newVarClass = '';
                        if ($(v).hasClass('new-variant')) {
                            newVarClass = 'new-variant';
                        }
                        var allDataOfAVariant = '<input class="all-data-of-a-variant  ' + newVarClass + ' hidden" type="hidden" variant_id="' + $(this).attr('variant-id') + '" ';
                        $.each($(this).find(":input"), function (index, value) {
                            allDataOfAVariant += $(this).attr('field-name') + ' = "' + $(this).val() + '" ';
                        });
                        allDataOfAVariant += '>';
                        $("#option_id_" + optionId).append(allDataOfAVariant);
                    });
                    $("#edit_option_modal").modal('hide');
                });

//              APPEND VARIANT DATA INTO FORM
                $('form[id=editOptionsForm]').submit(function (event) {
                    $.each($("#optionTableBody tr"), function (i, v) {
                        var variantSerialNumber = 0;
                        var optionId = $(this).attr('option-id');
                        var allDataOfAVariantObj = $(this).find('.all-data-of-a-variant');
                        $.each(allDataOfAVariantObj, function (index, value) {
                            if ($(value).hasClass('new-variant')) {
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][variant_id]" value="' + $(this).attr('variant_id') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][variant_name]" value="' + $(this).attr('variant_name') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][price_modifier]" value="' + $(this).attr('price_modifier') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][price_modifier_type]" value="' + $(this).attr('price_modifier_type') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][weight_modifier]" value="' + $(this).attr('weight_modifier') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][weight_modifier_type]" value="' + $(this).attr('weight_modifier_type') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantDataNew][' + variantSerialNumber + '][status]" value="' + $(this).attr('status') + '">');
                            } else {
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][variant_id]" value="' + $(this).attr('variant_id') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][variant_name]" value="' + $(this).attr('variant_name') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][price_modifier]" value="' + $(this).attr('price_modifier') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][price_modifier_type]" value="' + $(this).attr('price_modifier_type') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][weight_modifier]" value="' + $(this).attr('weight_modifier') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][weight_modifier_type]" value="' + $(this).attr('weight_modifier_type') + '">');
                                $("#editOptionsForm").append('<input class="option-variant-data" type="hidden" name="product_data[options][' + optionId + '][variantData][' + variantSerialNumber + '][status]" value="' + $(this).attr('status') + '">');
                            }
                            variantSerialNumber++;
                        });
                    });
                    callOptionCombination();
                    return true;
                });

                $(document.body).on('change', '#select_category', function () {
                    var catid = $(this).val();
//                    $("#featuresTableBody").html('');
//                    $('#select_feature').html('');
                    if (catid) {
                        $.ajax({
                            url: '/supplier/product-ajax-handler',
                            type: 'POST',
                            datatype: 'json',
                            data: {
                                method: 'getFeaturesWhereCatIdLike',
                                catid: catid
                            },
                            success: function (response) {
                                response = $.parseJSON(response);
                                console.log(response);
                                if (response['code'] == 200) {
                                    $('#featureTab').removeClass('hidden');
                                    var toAppend = '';
                                    $.each(response['data']['featureDetails'], function (i, a) {

                                        var toAppendStatus = '<div class="col-md-offset-1 col-md-4"><label for="product_feature_status_' + a['feature_id'] + '_active" class="col-sm-6">';
                                        toAppendStatus += '<input type="radio" name="product_data[features][' + a['feature_id'] + '][status]" id="product_feature_status_' + a['feature_id'] + '_active" value="1" checked> Active </label>';
                                        toAppendStatus += '<label for="product_feature_status_' + a['feature_id'] + '_disabled" class="col-sm-6">';
                                        toAppendStatus += '<input type="radio" name="product_data[features][' + a['feature_id'] + '][status]" id="product_feature_status_' + a['feature_id'] + '_disabled" value="0"> Disabled </label></div>';

                                        toAppend += '<hr><div class="form-group"><label class="col-sm-3 control-label">' + a['feature_name'] + '</label>';
                                        if (a['feature_type'] == 0) {
                                            toAppend += '<div class="col-md-3"><input class="form-control" type="checkbox" name="product_data[features][' + a['feature_id'] + '][single]"/></div>';//value="' + a['feature_id'] + '"//<div class="clearfix"></div>
                                            toAppend += toAppendStatus;
                                            toAppend += '<div class="clearfix"></div>';
                                        } else if (a['feature_type'] == 1) {
                                            if (a['variant_ids'] != null) {
                                                var variantIds = a['variant_ids'].split(",");
                                                var variantNames = a['variant_names'].split(",");
                                                var variantDescs = a['variant_descriptions'].split(",");
                                                $.each(variantIds, function (iVIds, aVIds) {
                                                    if (iVIds != 0) {
                                                        toAppend += '<div class="col-md-3"></div>';
                                                    }
                                                    toAppend += '<div class="col-md-3"><input class="form-control" type="checkbox" name="product_data[features][' + a['feature_id'] + '][multiple][' + aVIds + ']"/>' + variantNames[iVIds] + '</div>';// value="' + a['feature_id'] + '.' + aVIds + '"//<div class="clearfix"></div>
                                                    if (iVIds == 0) {
                                                        toAppend += toAppendStatus;
                                                    }
                                                    toAppend += '<div class="clearfix"></div>';
                                                });
                                            }
                                        } else if (a['feature_type'] == 2 || a['feature_type'] == 3) {
                                            if (a['variant_ids'] != null) {
                                                var variantIds = a['variant_ids'].split(",");
                                                var variantNames = a['variant_names'].split(",");
                                                var variantDescs = a['variant_descriptions'].split(",");
                                                toAppend += '<div class="col-md-3"><select class="form-control" name="product_data[features][' + a['feature_id'] + '][select]">';
                                                $.each(variantIds, function (iVIds, aVIds) {
                                                    toAppend += '<option value="' + aVIds + '">' + variantNames[iVIds] + '</option>';//+ a['feature_id'] + '.'
                                                });
                                                toAppend += '</select></div>';//<div class="clearfix"></div>
                                                toAppend += toAppendStatus;
                                                toAppend += '<div class="clearfix"></div>';
                                            }
                                        }
                                        toAppend += '</div>';
                                        toAppend += '<div class="clearfix"></div>';
                                    });
                                    $.each(response['data']['featureGroupDetails'], function (iFG, aFG) {
                                        toAppend += '<hr class="hrfeaturegroup"><strong>' + aFG['feature_name'] + '</strong><br>';
                                        $.each(aFG['featureDetails'], function (i, a) {

                                            var toAppendStatus = '<div class="col-md-offset-1 col-md-4"><label for="product_feature_status_' + a['feature_id'] + '_active" class="col-sm-6">';
                                            toAppendStatus += '<input type="radio" name="product_data[features][' + a['feature_id'] + '][status]" id="product_feature_status_' + a['feature_id'] + '_active" value="1" checked> Active </label>';
                                            toAppendStatus += '<label for="product_feature_status_' + a['feature_id'] + '_disabled" class="col-sm-6">';
                                            toAppendStatus += '<input type="radio" name="product_data[features][' + a['feature_id'] + '][status]" id="product_feature_status_' + a['feature_id'] + '_disabled" value="0"> Disabled </label></div>';

                                            toAppend += '<hr><div class="form-group"><label class="col-sm-3 control-label">' + a['feature_name'] + '</label>';
                                            if (a['feature_type'] == 0) {
                                                toAppend += '<div class="col-md-3"><input class="form-control" type="checkbox" name="product_data[features][' + a['feature_id'] + '][single]"/></div>';//value="' + a['feature_id'] + '"//<div class="clearfix"></div>
                                                toAppend += toAppendStatus;
                                                toAppend += '<div class="clearfix"></div>';
                                            } else if (a['feature_type'] == 1) {
                                                if (a['variant_ids'] != null) {
                                                    var variantIds = a['variant_ids'].split(",");
                                                    var variantNames = a['variant_names'].split(",");
                                                    var variantDescs = a['variant_descriptions'].split(",");
                                                    $.each(variantIds, function (iVIds, aVIds) {
                                                        if (iVIds != 0) {
                                                            toAppend += '<div class="col-md-3"></div>';
                                                        }
                                                        toAppend += '<div class="col-md-3"><input class="form-control" type="checkbox" name="product_data[features][' + a['feature_id'] + '][multiple][' + aVIds + ']"/>' + variantNames[iVIds] + '</div>';// value="' + a['feature_id'] + '.' + aVIds + '"//<div class="clearfix"></div>
                                                        if (iVIds == 0) {
                                                            toAppend += toAppendStatus;
                                                        }
                                                        toAppend += '<div class="clearfix"></div>';
                                                    });
                                                }
                                            } else if (a['feature_type'] == 2 || a['feature_type'] == 3) {
                                                if (a['variant_ids'] != null) {
                                                    var variantIds = a['variant_ids'].split(",");
                                                    var variantNames = a['variant_names'].split(",");
                                                    var variantDescs = a['variant_descriptions'].split(",");
                                                    toAppend += '<div class="col-md-3"><select class="form-control" name="product_data[features][' + a['feature_id'] + '][select]">';
                                                    $.each(variantIds, function (iVIds, aVIds) {
                                                        toAppend += '<option value="' + aVIds + '">' + variantNames[iVIds] + '</option>';//+ a['feature_id'] + '.'
                                                    });
                                                    toAppend += '</select></div>';//<div class="clearfix"></div>
                                                    toAppend += toAppendStatus;
                                                    toAppend += '<div class="clearfix"></div>';
                                                }
                                            }
                                            toAppend += '</div><div class="clearfix"></div>';
                                        });
                                    });
                                    $('#featuresData').html(toAppend);
                                    App.initAjax();
                                } else {
                                    $('#featureTab').addClass('hidden');
                                    console.log(response['message']);
                                }
                            },
                            error: function (response) {
                                console.log(response['message']);
                            }
                        });
                    }
                });

                ProductImageUpload.init();

                $(document.body).on('click', '#option-combinations', function () {
                    if (callOptionCombination()) {
                        $("#option_combinations_modal").modal('show');
                    }
                });

                //todo
                $('#submit-main-image').click(function (e) {
                    e.preventDefault();

                    var formData = new FormData(document.getElementById('product-main-image-form'));
                    formData.append('image', $('input[type=file]')[0].files[0]);
                    formData.append('method', 'updateMainImage');
                    formData.append('imageId', $("#main-image").attr('image-id'));
                    formData.append('productId', {{$productData['product_id']}});
                    App.blockUI({
                        boxed: true,
                        message: 'Updating main image...'
                    });
                    $.ajax({
                        url: '/supplier/product-ajax-handler',
                        type: "POST",
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: formData,
                        success: function (response) {
                            App.unblockUI();
                            if (response == 1) {
                                $("#main-image-action").hide();

//                        bootbox.alert("Successfully updated main image!", function () {
//                        });
                            } else if (response == 3) {
                                bootbox.alert("Please select a file first!");
                            } else if (response == 2) {
                                bootbox.alert("Please upload .jpg, .png, .gif and .bmp file type only!");
                            }
                        },
                        error: function (response) {
                            App.unblockUI();
                            bootbox.alert("Sorry, an error occured while updating main image, please try again after sometime!");
                        }
                    });
                });

                //todo
                $(document.body).on('click', '.delete-image', function () {
                    var obj = $(this);
                    var imageId = obj.attr("image-id");
                    var productId ={{$productData['product_id']}};
                    if (imageId != '' && productId != '') {
                        App.blockUI({
                            boxed: true,
                            message: 'Deleting image...'
                        });
                        $.ajax({
                            url: '/supplier/product-ajax-handler',
                            type: 'POST',
                            datatype: 'json',
                            data: {
                                method: 'deleteProductImage',
                                productId: productId,
                                imageId: imageId
                            },
                            success: function (response) {
                                response = $.parseJSON(response);
                                App.unblockUI();
                                if (response['code'] == 200) {
                                    obj.parents(".other-images").remove();
                                } else {
                                    bootbox.alert(response['message']);
                                }
                            },
                            error: function (response) {
                                response = $.parseJSON(response);
                                App.unblockUI();
                                bootbox.alert(response['message']);
                            }
                        });
                    }
                });

                $(document.body).on('click', '.panel-heading', function () {
                    var thisEl = $(this);
                    var panels = $('#tab_1_2 .panel-default');
                    var pgPanels = $('#tab_1_1 .panel-default');
                    $('.panel-heading a').removeClass('collapsed');
                    $.each(panels, function (i, a) {
                        if ($(a).find('.panel-collapse').attr('aria-expanded') != "true") {
                            $(a).removeClass('panel-info');
                            $(a).find('.panel-heading a').addClass('collapsed');
                        }
                    });
                    $(pgPanels).removeClass('panel-info');
                    $('.panel-heading a').addClass('collapsed');
                    if ($(this).parent().find('.panel-collapse').attr('aria-expanded') == "true") {
                        $(this).parent().removeClass('panel-info');
                        $(this).find('a').addClass('collapsed');
                    } else {
                        $(this).parent().addClass('panel-info');
                        $(this).find('a').removeClass('collapsed');
                    }
                });

            });

            function callOptionCombination() {
                if (optionAddedRemovedFlag) {//if options added/removed, clear the table since new set combinations are to be generated
//                    oTable.fnClearTable();
                    $('#variantCombinationTBody').empty();
                }
                var options = $('#optionTableBody > tr');
                if (options.length > 1) {
                    var optionsData = [];
                    $.each(options, function (i, a) {
                        var optionId = $(a).attr('option-id');
                        var temp = [];
                        temp['variantData'] = $(a).find('.all-data-of-a-variant');
                        temp['optionId'] = optionId;
                        temp['optionName'] = $(a).find('[field-name=option_name]').val();
                        optionsData.push(temp);
                    });
                    var optionCount = options.length;
                    optionsData.sort(function (a, b) {
                        a = a['variantData'].length;
                        b = b['variantData'].length;
                        return a > b ? -1 : (a < b ? 1 : 0);
                    });

                    var tempcombs = generateOptionCombination(optionsData, 0, '', {});//call function to generate combination ids
//                console.log(tempcombs);

                    //---------------variants exists check and add combiantion start------------------//
                    var optCombTrs = $('#variantCombinationTBody > tr');//getting all the table rows of combinations previously made
                    $(optCombTrs).addClass('to_delete');
                    var combinputs = $(optCombTrs).find('input[type="number"]');//getting inputs to get combination id
                    $.each(tempcombs, function (i, a) {
                        var combinationIds = a['combinationId'].split("_");//get individual variant ids from newly made combination ids
                        var cIdExistsFlag = false;
                        var varFlagString = '';
                        var namesString = '';
                        var newVarFlag = false;
                        var j = 0;
                        while (j < optionCount) {
                            if (a['newVariantFlag' + j] == 1) {
                                newVarFlag = true;
                            }
                            namesString += a['optionName' + j] + ": " + a['variantName' + j] + '<br>';
                            if (j == 0) {
                                varFlagString += a['newVariantFlag' + j];
                            } else {
                                varFlagString += "_" + a['newVariantFlag' + j];
                            }
                            j++;
                        }
                        $.each(combinputs, function (iIp, aIp) {//loop is to check if new made combination ids already exists in previously made combination ids
                            var varIds = $(aIp).attr('dataCid').split("_");//getting combination ids of previously added combination ids
                            //find if new var flag exists
                            if (_.intersection(varIds, combinationIds).length == optionCount) {
                                $(aIp).parent().parent().removeClass('to_delete');
                                cIdExistsFlag = true;
                                return false;//todo check for new variants added
                            }
                        });
                        if (!cIdExistsFlag) {//if doesn't exists add the combiantion to table rows
                            var toAppend = '<tr>';
                            /* toAppend.push(namesString + '<input name="product_data[opt_combination][' + a['combinationId'] + '][newflag]" type="hidden" value="' + varFlagString + '"/>');
                             toAppend.push('<input name="product_data[opt_combination][' + a['combinationId'] + '][quantity]" type="number" value="1" dataCid="' + a['combinationId'] + '"/>');
                             toAppend.push('<input name="product_data[opt_combination][' + a['combinationId'] + '][barcode]"/>');
                             toAppend.push('<input name="product_data[opt_combination][' + a['combinationId'] + '][excludeflag]" type="checkbox"/>');
                             oTable.fnAddData(toAppend); */
                            var optvarTypeIndex = "existing";
                            if (newVarFlag) {
                                optvarTypeIndex = "new";
                            }
                            toAppend += '<td>' + namesString + '<input name="product_data[opt_combination][' + optvarTypeIndex + '][' + a['combinationId'] + '][newflag]" type="hidden" value="' + varFlagString + '"/></td>';
                            toAppend += '<td><input name="product_data[opt_combination][' + optvarTypeIndex + '][' + a['combinationId'] + '][quantity]" type="number" value="1" min=1 dataCid="' + a['combinationId'] + '"/></td>';
//                            toAppend += '<td><input name="product_data[opt_combination][' + optvarTypeIndex + '][' + a['combinationId'] + '][barcode]"/></td>';
                            toAppend += '<td><input name="product_data[opt_combination][' + optvarTypeIndex + '][' + a['combinationId'] + '][excludeflag]" type="checkbox"/></td>';
                            toAppend += '</tr>';
                            $('#variantCombinationTBody').append(toAppend);
                        }
                    });
                    //---------------variants exists check and add combiantion end------------------//
                    $('.to_delete').remove();
                    optionAddedRemovedFlag = false;
                    return true;
                } else {
                    $('#variantCombinationTBody').empty();
                    toastr['error']("Please select atleast 2 options for combining.");
                    return false;
                }
            }

            function generateOptionCombination(array, currentIndex, toAppendString, tempNamesArr) {
                var arrayLength = array.length;
                var tempArray = [];
                $.each(array[currentIndex]['variantData'], function (iVD, aVD) {
                    var temp1 = [];
                    temp1['combinationId'] = toAppendString + $(aVD).attr('variant_id');
                    if (temp1['combinationId'].split("_").length == arrayLength) {
                        temp1['newVariantFlag' + currentIndex] = 0;
                        if ($(aVD).hasClass('new-variant')) {
                            temp1['newVariantFlag' + currentIndex] = 1;
                        }
                        temp1['optionName' + currentIndex] = array[currentIndex]['optionName'];
                        temp1['optionId' + currentIndex] = array[currentIndex]['optionId'];
                        temp1['variantName' + currentIndex] = $(aVD).attr('variant_name');
                        $.each(tempNamesArr, function (iN, aN) {
                            temp1[iN] = aN;
                        });
                        tempArray.push(temp1);
                    }
                    if (array[currentIndex + 1] != undefined) {
                        console.log('next option exists');
                        var temp2 = [];
                        tempNamesArr['newVariantFlag' + currentIndex] = 0;
                        if ($(aVD).hasClass('new-variant')) {
                            tempNamesArr['newVariantFlag' + currentIndex] = 1;
                        }
                        tempNamesArr['optionName' + currentIndex] = array[currentIndex]['optionName'];
                        tempNamesArr['optionId' + currentIndex] = array[currentIndex]['optionId'];
                        tempNamesArr['variantName' + currentIndex] = $(aVD).attr('variant_name');
                        temp2 = generateOptionCombination(array, currentIndex + 1, toAppendString + $(aVD).attr('variant_id') + '_', tempNamesArr);
                        $.each(temp2, function (iTemp2, aTemp2) {
                            tempArray.push(aTemp2);
                        });
                    }
                });
                return tempArray;
            }

            var ProductImageUpload = function () {

                var handleImages = function () {
                    // see http://www.plupload.com/
                    var uploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight,html4',
                        browse_button: document.getElementById('tab_images_uploader_pickfiles'), // you can pass in id...
                        container: document.getElementById('tab_images_uploader_container'), // ... or DOM Element itself

                        url: '/supplier/edit-product/{{$productData['product_id']}}?updateFormName=otherimages',
                        filters: {
                            max_file_size: '2mb',
                            mime_types: [
                                {title: "Image files", extensions: "jpg,gif,png,bmp"},
                            ]
                        },
                        init: {
                            PostInit: function () {
                                $('#tab_images_uploader_filelist').html("");
                                $('#tab_images_uploader_uploadfiles').click(function () {
                                    uploader.start();
                                    return false;
                                });
                                $('#tab_images_uploader_filelist').on('click', '.added-files .remove', function () {
                                    console.log($(this).parent('.added-files').attr("id"));
                                    uploader.removeFile($(this).parent('.added-files').attr("id").replace("uploaded_file_", ""));
                                    $(this).parent('.added-files').remove();
                                });
                                uploader.setOption('file_data_name', 'otherimage')
                                uploader.setOption('send_file_name', true)
                            },
                            FilesAdded: function (up, files) {
                                plupload.each(files, function (file) {
                                    $('#tab_images_uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file_' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ') <span class="status label label-info"></span>&nbsp;<a href="javascript:;" style="margin-top:-5px" class="remove pull-right btn btn-sm red"><i class="fa fa-times"></i> remove from <i><abbr title="Added images won\'t be removed from prodcut images"><code style="padding: 0px;">upload list</code></abbr></i></a></div>');
                                });
                            },
                            UploadProgress: function (up, file) {
                                $('#uploaded_file_' + file.id + ' > .status').html(file.percent + '%');
                            },
                            FileUploaded: function (up, file, response) {
                                var response = $.parseJSON(response.response);
                                if (response.result && response.result == 'TRUE') {
                                    var id = response.id; // uploaded file's unique name. Here you can collect uploaded file names and submit an jax request to your server side script to process the uploaded files and update the images tabke
                                    $('#uploaded_file_' + file.id + ' > .status').removeClass("label-info").addClass("label-success").html('<i class="fa fa-check"></i> Done'); // set successfull upload

                                    var toAppendForNewImages = '';
                                    toAppendForNewImages = '<div class="item other-images">';
                                    toAppendForNewImages += '<div class = "btn-group pull-right image-action" style = "position: absolute; right: 10px; bottom: 10px;" >';
                                    /*
                                     toAppendForNewImages += '<a class = "btn btn-sm blue btn-circle dropdown-toggle" href = "#" data-toggle = "dropdown" >';
                                     toAppendForNewImages += '<i class = "fa fa-angle-down"> </i>';
                                     toAppendForNewImages += '</a>';
                                     toAppendForNewImages += '<ul class = "dropdown-menu pull-right">';
                                     toAppendForNewImages += '<li>';
                                     toAppendForNewImages += '<a class = "make-main-image" image-id = "' + response.imageId + '" > Make main image </a>';
                                     toAppendForNewImages += '</li>';
                                     toAppendForNewImages += '<li>';
                                     toAppendForNewImages += '<a class = "delete-image" image-id = "' + response.imageId + '" > Delete </a>';
                                     toAppendForNewImages += '</li>';
                                     toAppendForNewImages += '</ul>';
                                     */
                                    toAppendForNewImages += '<button class="btn btn-sm red-thunderbird btn-circle delete-image" image-id="' + response.imageId + '"><i class="fa fa-trash"></i></button>';
                                    toAppendForNewImages += '</div>';
                                    toAppendForNewImages += '<img class = "img-responsive" src = "' + response.imageURL + '" alt = "" >';
                                    toAppendForNewImages += '</div>';

                                    $(toAppendForNewImages).insertBefore($('#newimageappendbefore'));
                                    $('.masonry').removeClass('hidden');
                                } else {
                                    $('#uploaded_file_' + file.id + ' > .status').removeClass("label-info").addClass("label-danger").html('<i class="fa fa-warning"></i> Failed'); // set failed upload
                                    App.alert({
                                        type: 'danger',
                                        message: 'One of uploads failed. Please retry.',
                                        closeInSeconds: 10,
                                        icon: 'warning'
                                    });
                                }
                            },
                            Error: function (up, err) {
                                App.alert({
                                    type: 'danger',
                                    message: err.message,
                                    closeInSeconds: 10,
                                    icon: 'warning'
                                });
                            }
                        }
                    });
                    uploader.init();
                }


                return {
                    //main function to initiate the module
                    init: function () {
                        handleImages();
                    }

                };
            }();

        </script>
    @endif

@endsection
