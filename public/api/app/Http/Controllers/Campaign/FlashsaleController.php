<?php

namespace FlashSaleApi\Http\Controllers\Campaign;


use FlashSaleApi\Http\Models\ProductFilterOption;
use FlashSaleApi\Http\Models\ProductOptionVariants;
use Illuminate\Http\Request;
use FlashSaleApi\Http\Requests;
use FlashSaleApi\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use PDO;
use FlashSaleApi\Http\Models\Campaigns;
use FlashSaleApi\Http\Models\ProductCategories;
use FlashSaleApi\Http\Models\Products;
use FlashSaleApi\Http\Models\User;
use stdClass;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class FlashsaleController extends Controller
{
//    public function __call(){
//
//    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        return view("Admin\admin")
    }


    /**
     * Service for getting all flashsale campaigns details
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 23-02-2016
     */
    public function flashsaleDetails(Request $request)
    {

        $postData = $request->all();
        $response = new stdClass();
        $objUserModel = new User();
        if ($postData) {
            $userId = '';
//            if (isset($postData['id'])) {
//                $userId = $postData['id'];
//
//            }

            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                }
//                else {
//                    if ($userId != '') {
//                        $whereForloginToken = $whereForUpdate = [
//                            'rawQuery' => 'id =?',
//                            'bindParams' => [$userId]
//                        ];
//                        $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);
//
//                        if ($mytoken == $Userscredentials->login_token) {
//                            $authflag = true;
//                        }
//                    }
//                }
            }
            if ($authflag) {//LOGIN TOKEN
                $objCampaingsModel = Campaigns::getInstance();
                $currenttime = time();
                $where = ['rawQuery' => 'available_from < ? AND available_upto > ? AND campaign_status = ?', 'bindParams' => [time(), time(), 1]];
//                $selectedColumns = ['campaigns.*'];
                $campaignDetails = $objCampaingsModel->getFlashsaleDetail($where);


                if ($campaignDetails) {
                    $data = $campaignDetails;
                    $response->code = 200;
                    $response->message = "Success";
                    $response->data = $data;

                } else {
                    $response->code = 100;
                    $response->message = "No Product Details found.";
                    $response->data = null;
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
            }
        } else {
            $response->code = 401;
            $response->message = "Invalid request";
            $response->data = null;
        }

        echo json_encode($response, true);
    }


    /**
     * For Getting All Flashsale Product List
     * @param Request $request
     * @since 24-02-2016
     * @author Vini Dubey <vinidubey@globussoft.in>
     */
    public function flashsaleProducts(Request $request)
    {
        $postData = $request->all();
        $response = new stdClass();
        $objUserModel = new User();
        $objCategoryModel = ProductCategories::getInstance();
        $objProductModel = Products::getInstance();
        $objOptionVariant = ProductOptionVariants::getInstance();
        if ($postData) {
            $userId = '';
            if (isset($postData['id'])) {
                $userId = $postData['id'];
            }
            $FlashsaleId = '';
            if (isset($postData['campaign_id'])) {
                $FlashsaleId = $postData['campaign_id'];
            }

            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                } else {
                    if ($userId != '') {
                        $whereForloginToken = $whereForUpdate = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId]
                        ];
                        $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);

                        if ($mytoken == $Userscredentials->login_token) {
                            $authflag = true;
                        }
                    }
                }
            }
            $campaignDetails = '';
            if ($authflag) {//LOGIN TOKEN
                $objCampaingsModel = Campaigns::getInstance();
                $where = ['rawQuery' => 'campaign_id = ?', 'bindParams' => [$FlashsaleId]];
                $selectedColumn = ['campaigns.*'];

                $campaignDetails = $objCampaingsModel->getFlashsaleDetail($where, $selectedColumn);
                $getProducts = '';
                $getcategory = '';
                if ($campaignDetails[0]) {
                    $campaignDetails[0]->product_info = array();
                    $camp = json_decode($campaignDetails[0]->for_category_ids, true);
                    $categoryMerge = array_merge(array_keys($camp), array_flatten($camp));
                    $where = ['rawQuery' => 'category_id IN(' . implode(",", $categoryMerge) . ') OR
                    products.product_id IN(' . $campaignDetails[0]->for_product_ids . ') AND
                    product_images.image_type = 0'
                    ];

                    $selectedColumn = ['products.*',
                        'product_images.image_url',
                        'productmeta.*',
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id)AS option_ids'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____")AS variant_datas'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')
                    ];
                    $getProducts = $objProductModel->getProductDetailsByCategoryIds($where, $selectedColumn);
                    $campaignDetails[0]->product_info = $getProducts;
                }
                if (($campaignDetails[0])) {
                    $response->code = 200;
                    $response->message = "Success";
                    $response->data = $campaignDetails[0];

                } else {
                    $response->code = 100;
                    $response->message = "Something went Wrong. No Product Details found.";
                    $response->data = null;
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
            }
        } else {
            $response->code = 401;
            $response->message = "Invalid request";
            $response->data = null;
        }

        echo json_encode($response, true);

    }

    /**
     * Get Product Pop Details For Flashsale Products.
     * @param Request $request
     * @since 25-02-2016
     * @author Vini Dubey <vinidubey@globussoft.in>
     */
    public function productPopup(Request $request)
    {

        $postData = $request->all();
        $response = new stdClass();
        $objUserModel = new User();
        $objCategoryModel = ProductCategories::getInstance();
        $objProductModel = Products::getInstance();
        $objOptionVariant = ProductOptionVariants::getInstance();
        if ($postData) {
//            $userId = '';
//            if (isset($postData['id'])) {
//                $userId = $postData['id'];
//            }
            $productId = '';
            if (isset($postData['product_id'])) {
                $productId = $postData['product_id'];
            }

            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                }
//                else {
//                    if ($userId != '') {
//                        $whereForloginToken = $whereForUpdate = [
//                            'rawQuery' => 'id =?',
//                            'bindParams' => [$userId]
//                        ];
//                        $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);
//
//                        if ($mytoken == $Userscredentials->login_token) {
//                            $authflag = true;
//                        }
//                    }
//                }
            }
            $campaignDetails = '';
            if ($authflag) {//LOGIN TOKEN
                $where = ['rawQuery' => 'products.product_id = ?', 'bindParams' => [$productId]];
                $selectedColumn = [
                    'products.*',
                    'product_images.*',
                    'productmeta.*',
                    'product_features.*', 'product_feature_variants.*', 'product_feature_variant_relation.*', 'product_features.full_description AS feature_description', 'productmeta.full_description AS product_full_description',
                    'product_option_variants_combination.combination_id', 'product_option_variants_combination.variant_ids', 'product_option_variants_combination.product_id', 'product_option_variants_combination.barcode_gtin', 'product_option_variants_combination.barcode_upc', 'product_option_variants_combination.barcode_ean', 'product_option_variants_combination.shippinginfo(wi,h,we)', 'product_option_variants_combination.exception_flag',
                    'product_option_variant_relation.*',
                    DB::raw('GROUP_CONCAT(
                    CASE
                    WHEN ((SELECT COUNT(pi_id) FROM product_images  WHERE product_images.for_product_id ="' . $productId . '" AND product_images.for_combination_id !="0")!=0)
                    THEN
                        CASE
                            WHEN (product_images.image_type =1 AND (product_images.for_combination_id!=0 OR product_images.for_combination_id!=""))
                            THEN product_images.image_type
                         END
                     ELSE  product_images.image_type
                    END) AS image_types'),
                    DB::raw('GROUP_CONCAT(DISTINCT
                    CASE
                    WHEN ((SELECT COUNT(pi_id) FROM product_images  WHERE product_images.for_product_id ="' . $productId . '" AND product_images.for_combination_id !="0")!=0)
                    THEN
                        CASE
                            WHEN (product_images.image_type =1 AND (product_images.for_combination_id!=0 OR product_images.for_combination_id!=""))
                            THEN product_images.image_url
                         END
                     ELSE  product_images.image_url
                    END) AS image_urls'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.quantity) AS combination_quantity'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.quantity_sold) AS combination_quantity_sold'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_ids) AS variant_id'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_feature_variants.feature_id) AS feature_ids'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_feature_variants.variant_name) AS feature_variant_name'),
                    DB::raw('GROUP_CONCAT(DISTINCT product_features.feature_name) AS feature_names')
                ];
                $productDetailsForPopUp = $objProductModel->getProductAndImages($where, $selectedColumn);
//                dd($productDetailsForPopUp[0]);
                $whereVariant = array();
                if ($productDetailsForPopUp[0]->combination_id != 0 && ($productDetailsForPopUp[0]->combination_id != '') && ($productDetailsForPopUp[0]->combination_id != null)) {
                    $whereVariant = ['rawQuery' => 'variant_id IN(' . str_replace("_", ",", $productDetailsForPopUp[0]->variant_ids_combination) . ')'];
                } else if ($productDetailsForPopUp[0]->variant_id != null && $productDetailsForPopUp[0]->variant_id != 0 && $productDetailsForPopUp[0]->variant_id != '') {
                    $whereVariant = ['rawQuery' => 'variant_id IN(' . $productDetailsForPopUp[0]->variant_id . ')'];

                }
                $selectedColumn = ['product_option_variants.*', 'product_options.*'];
                $temp = array();
                if (!empty($whereVariant)) {
                    $variantDetails = $objOptionVariant->getOptionVariantsInfo($whereVariant, $selectedColumn);
                    if (!empty($variantDetails)) {
                        $uniqueOptionIDs = array_values(array_unique(array_map(function ($variantDetails) {
                            return $variantDetails->option_id;
                        }, $variantDetails)));

                        foreach ($uniqueOptionIDs as $OKey => $OValue) {
                            $tempOption = array();
                            foreach ($variantDetails as $VKey => $VValue) {
                                if ($OValue == $VValue->option_id) {
                                    $tempOption['option_name'] = $VValue->option_name;
                                    $tempOption['option_id'] = $VValue->option_id;
                                    $tempOption['option_type'] = $VValue->option_type;
                                    $tempOption['description'] = $VValue->description;
                                    $tempOption['required'] = $VValue->required;
                                    $tempOption['comment'] = $VValue->comment;
                                    $tempOption['variantData']['variant_id'][] = $VValue->variant_id;
                                    $tempOption['variantData']['variant_name'][] = $VValue->variant_name;
                                    $tempOption['variantData']['price_modifier'][] = $VValue->price_modifier;
                                    $tempOption['variantData']['price_modifier_type'][] = $VValue->price_modifier_type;
                                    $tempOption['variantData']['weight_modifier'][] = $VValue->weight_modifier;
                                    $tempOption['variantData']['weight_modifier_type'][] = $VValue->weight_modifier_type;

                                }
                            }
                            $temp[] = $tempOption;
                        }
                    }
                }
                $productDetailsForPopUp[0]->options = $temp;
                $relatedCategoryIds = $productDetailsForPopUp[0]->category_id;
                $relatedVariantIds = $productDetailsForPopUp[0]->variant_id;
//                DB::statement('SET @relatedProd = "variant_id"');
//                $whereRelatedProducts = ['rawQuery' => 'products.product_id != '.$productId.' AND products.category_id  LIKE "%' . $relatedCategoryIds . '%" OR product_option_variant_relation.variant_ids REGEXP ' . implode("|", explode(",", $relatedVariantIds)) . ''];
                $whereRelatedProducts = ['rawQuery' => 'products.product_id != ' . $productId . ' AND products.category_id  LIKE "%' . $relatedCategoryIds . '%"'];
//                $whereRelatedProducts = ['rawQuery' => 'products.category_id  LIKE "%' . $relatedCategoryIds . '%"  AND product_option_variant_relation.variant_ids IN('.$relatedVariantIds.')'];
                $selectedColumn = ['products.*', 'product_option_variant_relation.*', 'product_images.*', 'productmeta.*', DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_ids) AS variant_id')];
                $productDetail = $objProductModel->getRelatedProducts($whereRelatedProducts, $selectedColumn);

                /*
                 * To Do for Optional Variants if needed
                 */
//                $varian = implode(',',array_unique(array_flatten(array_map(function($varInd){
//                    return explode(',',$varInd->variant_id);
//                },$productDetail))));
//                $whereVariant = ['rawQuery' => 'products.product_id != '.$productId.' AND product_option_variant_relation.variant_ids REGEXP ' . implode("|", explode(",", $varian)) . ''];
//                $selectedColumn = ['products.*', 'product_option_variant_relation.*', 'product_images.*', 'productmeta.*'];
//                $productVariant = $objProductModel->getRelatedVariant($whereVariant, $selectedColumn);
                /*
                 * End for optional value
                 */
                $productDetailsForPopUp[0]->relatedProducts = $productDetail;

                if ($productDetailsForPopUp) {
                    $response->code = 200;
                    $response->message = "Success";
                    $response->data = $productDetailsForPopUp;

                } else {
                    $response->code = 100;
                    $response->message = "Something went Wrong. No Product Details found.";
                    $response->data = null;
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
            }
        } else {
            $response->code = 401;
            $response->message = "Invalid request";
            $response->data = null;
        }

        echo json_encode($response, true);

    }


    /**
     * Flashsale Ajax Handler
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function flashsaleAjaxHandler(Request $request)
    {

        $method = $request->input('method');
        $objUserModel = new User();
        $objCategoryModel = ProductCategories::getInstance();
        $objProductModel = Products::getInstance();
        $objOptionVariant = ProductOptionVariants::getInstance();
        $objCampaigns = Campaigns::getInstance();
        if ($method != "") {
            switch ($method) {
                case 'optionVariantDetails':
                    $postData = $request->all();
                    $response = new stdClass();
                    if ($postData) {
                        $userId = '';
//                        if (isset($postData['id'])) {
//                            $userId = $postData['id'];
//                        }
                        $productId = '';
                        if (isset($postData['variant_id'])) {
                            $variantId = $postData['variant_id'];
                        }
                        if (isset($postData['product_id'])) {
                            $productId = $postData['product_id'];
                        }
                        if (isset($postData['selectedCombination'])) {
                            $selectedCombination = $postData['selectedCombination'];
                        }


                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;

                            }
//                            else {
//                                if ($userId != '') {
//                                    $whereForloginToken = $whereForUpdate = [
//                                        'rawQuery' => 'id =?',
//                                        'bindParams' => [$userId]
//                                    ];
//                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);
//
//                                    if ($mytoken == $Userscredentials->login_token) {
//                                        $authflag = true;
//                                    }
//                                }
//                            }
                        }
                        $variantDetails = '';
//                        print_a($selectedCombination);
//                        echo $authflag;
//                        echo env("API_TOKEN");
//                        var_dump(env("API_TOKEN")==$postData['api_token']);
//                        print_a($postData);
                        if ($authflag) {


//                            $and=(explode(',',$selectedCombination)>1))?'':'';
                            $and = (count(explode('_', $selectedCombination)) > 1 ?
                                'product_option_variants_combination.variant_ids IN("' . $selectedCombination . '","' . strrev($selectedCombination) . '")' :
                                "product_option_variants.variant_id = " . $selectedCombination);

//                            $where = ['rawQuery' => 'product_option_variants_combination.product_id = ? AND product_option_variants_combination.variant_ids IN("' . $selectedCombination . '","' . strrev($selectedCombination) . '")', 'bindParams' => [$productId]];
                            $where = ['rawQuery' => 'product_option_variants_combination.product_id = ? AND ' . $and, 'bindParams' => [$productId]];
                            $selectedColumn = ['products.price_total',
                                'product_option_variants.*',
                                'product_images.*',
                                'product_option_variants_combination.*',
                                'product_option_variant_relation.*',
                                DB::raw("GROUP_CONCAT(
                    CASE
                    WHEN ((SELECT COUNT(pi_id) FROM product_images WHERE product_images.for_combination_id != '0' and for_product_id = $productId ) != 0)
                    THEN
                        CASE
                            WHEN (product_images.image_type = 1 AND (product_images.for_combination_id != 0 AND product_images.for_combination_id != ''))
                            THEN product_images.image_type
                         END
                     ELSE  product_images.image_type
                    END) AS image_types"),
                                DB::raw("GROUP_CONCAT(DISTINCT
                    CASE
                    WHEN ((SELECT COUNT(pi_id) FROM product_images  WHERE product_images.for_combination_id != '0' and for_product_id = $productId ) != 0)
                    THEN
                        CASE
                            WHEN (product_images.image_type = 1 AND (product_images.for_combination_id != 0 AND product_images.for_combination_id != ''))
                            THEN product_images.image_url
                         END
                     ELSE  product_images.image_url
                    END) AS image_urls"),

                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_ids) AS variant_id'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data SEPARATOR "____") AS variant_datas')

                            ];
                            $optionVariantDetailsForPopUp = $objOptionVariant->getOptionVariantDetailsForPopup($where, $selectedColumn);

                            $varian = array();
                            if (isset($optionVariantDetailsForPopUp->variant_ids_combination) && $optionVariantDetailsForPopUp->variant_ids_combination != '') {
                                $variantData = explode("____", $optionVariantDetailsForPopUp->variant_datas);
                                $varian = array_flatten(array_map(function ($v) {
                                    return json_decode($v);
                                }, $variantData));

                            }
                            $finalPrice = $optionVariantDetailsForPopUp->price_total;
                            $combineVarian = array_values(array_filter(array_map(function ($v) use ($varian, &$finalPrice) {
                                return current(array_filter(array_map(function ($value) use ($v, &$finalPrice) {
                                    if ($v == $value->VID) {
                                        $finalPrice = $finalPrice + $value->PM;
                                        return [$v => $value->PM];
                                    }
                                }, $varian)));
                            }, explode("_", $optionVariantDetailsForPopUp->variant_ids_combination))));
                            $optionVariantDetailsForPopUp->finalPrice = $finalPrice;

                            if ($optionVariantDetailsForPopUp) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $optionVariantDetailsForPopUp;

                            } else {
                                $response->code = 100;
                                $response->message = "Something went Wrong. No Product Details found.";
                                $response->data = null;
                            }

                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;

                case 'getCampaignsForMenu':
                    $postData = $request->all();
                    $response = new stdClass();
                    if ($postData) {
//                        $userId = '';
//                        if (isset($postData['id'])) {
//                            $userId = $postData['id'];
//                        }
                        $mytoken = '';
                        $authflag = false;
                        if (isset($postData['api_token'])) {
                            $mytoken = $postData['api_token'];

                            if ($mytoken == env("API_TOKEN")) {
                                $authflag = true;


//                             else {//
                                /*if ($userId != '') {//
                                    $whereForloginToken = $userId;//
                                    $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);//
//
                                    if ($mytoken == $Userscredentials['login_token']) {//
                                        $authflag = true;//
                                    }//
                                }*///
                            }
                        }
                        $variantDetails = '';
                        if ($authflag) {
                            $where = ['rawQuery' => 'available_from < ? AND available_upto > ? AND campaign_status = ?', 'bindParams' => [time(), time(), 1]];
                            $selectedColumns = ['campaigns.*'];
                            $campaignDetails = $objCampaigns->getFlashsaleDetail($where, $selectedColumns);
                            $campData = [];
                            foreach ([1 => 'DS', 2 => 'FS'] as $index => $item) {
                                $campData[$item] = implode(",", array_unique(array_flatten(array_filter(array_map(function ($camp) use ($index) {
                                    if ($camp->campaign_type == $index) {
                                        return array_unique(array_merge(array_keys(json_decode($camp->for_category_ids, true)), array_flatten(json_decode($camp->for_category_ids, true))));
                                    } else {
                                        return null;
                                    }
                                }, $campaignDetails)))));

                            }

                            foreach ([1 => 'DS', 2 => 'FS'] as $index => $item) {
                                $campDatasForCampaignName[$item] = implode(",", array_unique(array_flatten(array_filter(array_map(function ($campDatasForCampaignName) use ($index) {
                                    if ($campDatasForCampaignName->campaign_type == $index) {
                                        return $campDatasForCampaignName->campaign_banner;
                                    } else {
                                        return null;
                                    }
                                }, $campaignDetails)))));
                            }

//                            $where = ['rawQuery' => 'category_status = ? AND category_id IN(' . implode(',', array_unique(explode(',', implode(',', $campData)))) . ')', 'bindParams' => [1]];
                            $where = ['rawQuery' => 'category_status = ? AND category_id IN(' . implode(',', array_unique(array_filter(explode(',', implode(',', array_values($campData)))))) . ')', 'bindParams' => [1]];
//                            $selectColumn = ['product_categories.*'];
                            $categoryInfo = $objCategoryModel->getCategoryWhere($where);
                            $final['categoryInfo'] = $categoryInfo;
                            $final['campaignCatId'] = $campData;
                            $final['campName'] = $campDatasForCampaignName;

                            $campsData = [];
//                            $whereTodaysCamp = ['rawQuery' => 'available_from >= \''.time().'\' AND campaign_status = ?', 'bindParams' => [1]];
                            $whereTodaysCamp = ['rawQuery' => '(available_from BETWEEN ' . strtotime("now") . ' AND ' . strtotime("+1 day") . ' )AND campaign_status = ?', 'bindParams' => [1]];
                            $selectedColumns = ['campaigns.*'];
                            $todaySale = $objCampaigns->getFlashsaleDetail($whereTodaysCamp, $selectedColumns);
                            if (isset($todaySale)) {
                                foreach ([1 => 'DS', 2 => 'FS'] as $index => $item) {
                                    $todayCampData[$item] = implode(",", array_unique(array_flatten(array_filter(array_map(function ($todayCampData) use ($index) {
                                        if ($todayCampData->campaign_type == $index) {
                                            return array_unique(array_merge(array_keys(json_decode($todayCampData->for_category_ids, true)), array_flatten(json_decode($todayCampData->for_category_ids, true))));
                                        } else {
                                            return null;
                                        }
                                    }, $todaySale)))));

                                }
                                foreach ([1 => 'DS', 2 => 'FS'] as $index => $item) {
                                    $temp = [];
                                    $todayCamp[$item] = array_values(array_filter(array_map(function ($todayCamp) use ($index) {
                                        if ($todayCamp->campaign_type == $index) {
                                            return [$todayCamp->campaign_id => $todayCamp->campaign_name];
                                        } else {
                                            return null;
                                        }
                                    }, $todaySale)));
                                }
                                $final['todaySale'] = $todayCamp;
                            }
                            if ($final) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $final;

                            } else {
                                $response->code = 100;
                                $response->message = "Something went Wrong. No Product Details found.";
                                $response->data = null;
                            }

                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Invalid request";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;
                default:
                    break;
            }
        }
    }

    public function campaignDetails(Request $request)
    {

        $postData = $request->all();
        $response = new stdClass();
        $objUserModel = new User();
        $objCampaingsModel = Campaigns::getInstance();
        if ($postData) {
            $userId = '';
//            if (isset($postData['id'])) {
//                $userId = $postData['id'];
//
//            }
            $FlashsaleId = '';
            if (isset($postData['campaign_id'])) {
                $FlashsaleId = $postData['campaign_id'];
            }

            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                }
//                else {
//                    if ($userId != '') {
//                        $whereForloginToken = $whereForUpdate = [
//                            'rawQuery' => 'id =?',
//                            'bindParams' => [$userId]
//                        ];
//                        $Userscredentials = $objUserModel->getUsercredsWhere($whereForloginToken);
//
//                        if ($mytoken == $Userscredentials->login_token) {
//                            $authflag = true;
//                        }
//                    }
//                }
            }
            if ($authflag) {//LOGIN TOKEN
                if (isset($postData['option']) && isset($postData['limit']) && isset($postData['pagenumber'])) {
                    $objProductModel = Products::getInstance();
                    $objProductCategoryModel = ProductCategories::getInstance();

                    $wherePriceRange = ['rawQuery' => 1];
                    if (isset($postData['price_range_from']) && !empty($postData['price_range_from']) && isset($postData['price_range_upto']) && !empty($postData['price_range_upto'])) {
                        $priceFrom = $postData['price_range_from'];
                        $priceTo = $postData['price_range_upto'];
                        $wherePriceRange = ['rawQuery' => 'price_total >= ' . $priceFrom . ' AND price_total <= ' . $priceTo . ''];
                    }
                    $sortClause = ['products.product_id' => 'desc'];
                    if (isset($postData['sort_by']) && !empty($postData['sort_by'])) {
                        $sortBy = $postData['sort_by'];
                        switch ($sortBy) {
                            case "null-asc":
                                $sortClause = ['products.product_id' => 'asc'];
                                break;
                            case "timestamp-asc":
                                $sortClause = ['products.product_id' => 'asc'];
                                break;
                            case "position-asc":
                                $sortClause = ['products.product_id' => 'asc'];
                                break;
                            case "position-desc":
                                $sortClause = ['products.product_id' => 'asc'];
                                break;
                            case "price-asc":
                                $sortClause = ['products.price_total' => 'asc'];
                                break;
                            case "price-desc":
                                $sortClause = ['products.price_total' => 'desc'];
                                break;
                            case "popularity-asc":
                                $sortClause = ['products.price_total' => 'asc'];
                                break;
                            case "bestsellers-asc":
                                $sortClause = ['products.product_id' => 'asc'];
                                break;
                            case "bestsellers-desc":
                                $sortClause = ['products.product_id' => 'desc'];
                                break;
                            case "on_sale-asc":
                                $sortClause = ['products.product_id' => 'asc'];
                                break;
                            case "on_sale-desc":
                                $sortClause = ['products.product_id' => 'desc'];
                                break;
                            case "pricelowtohigh":
                                $sortClause = ['products.price_total' => 'asc'];
                                break;
                            case "pricehightolow":
                                $sortClause = ['products.price_total' => 'desc'];
                                break;

                            default:
                                break;
                        }
                    }

                    $categoryName = '';
                    $subcategoryName = '';
                    $whereForCategoryFilter = ['rawQuery' => 1];
                    $objProductModel = Products::getInstance();
                    $whereOption = ['rawQuery' => 1];
                    if (isset($postData['option']) && !empty($postData['option'])) {
                        $whereOption = ['rawQuery' => 'product_option_variants.variant_id IN (' . $postData["option"] . ')'];
                    }

                    $whereFilterVariant = ['rawQuery' => 1];
                    if (isset($postData['filter_id']) && !empty($postData['filter_id'])) {
                        $whereFilterVariant = ['rawQuery' => 'filter_product_relation.option_ids IN(' . $postData['filter_id'] . ')'];
                    }

                    $whereForFilter = $whereOption;
                    $where = ['rawQuery' => 'product_status = ?', 'bindParams' => [1]];
                    $selectedColumn = ['products.*',
                        'product_images.image_url',
                        'productmeta.*',
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id)AS option_ids'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____")AS variant_datas'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')];

                    $pagenumber = 1;
                    $limit = 5;
                    if (isset($pagenumber) && !empty($postData['pagenumber']) && isset($postData['limit']) && !empty($postData['limit'])) {
                        $pagenumber = $postData['pagenumber'];
                        $limit = $postData['limit'];
                    }
                    $offset = ((int)$pagenumber - 1) * ((int)$limit);


                    /*
                     * Get Specific Campaign product list
                     * Get Product list for campaign by campaign id
                     * Date: 04 june 2016
                     */

                    if (isset($postData['campaign_id'])) {

                        $where = ['rawQuery' => 'campaign_id = ?', 'bindParams' => [$FlashsaleId]];
                        $selectedColumn = ['campaigns.*'];

                        $campaignList = $objCampaingsModel->getFlashsaleDetail($where, $selectedColumn);
                        $getProducts = '';
                        $getcategory = '';
                        if (isset($campaignList[0])) {
                            $campaignList[0]->product_info = array();
                            $camp = json_decode($campaignList[0]->for_category_ids, true);
                            $categoryMerge = array_merge(array_keys($camp), array_flatten($camp));
                            $whereCampaignId = ['rawQuery' => 'category_id IN(' . implode(",", $categoryMerge) . ') OR
                    products.product_id IN(' . $campaignList[0]->for_product_ids . ') AND
                    product_images.image_type = 0'
                            ];
                            $selectedColumnCampaignId = ['products.*',
                                'product_images.image_url',
                                'productmeta.*',
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id) AS option_ids'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____") AS variant_datas'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')];

                            $getProducts = $objProductModel->getProductDetailsByCategoryIds($whereCampaignId, $selectedColumnCampaignId, $whereForFilter, $sortClause, $limit, $offset, $wherePriceRange, $whereFilterVariant);
                            $getProductsCount = count($objProductModel->getProductDetailsByCategoryIdsCount($whereCampaignId, $selectedColumnCampaignId, $wherePriceRange));
                            $campaignList[0]->product_info = $getProducts;
                            $campaignList[0]->total = $getProductsCount;

                            /*
                            * For Campiagn Filter Option Based On Campaign Id
                           */
                            $ObjProductFilterOptionModel = ProductFilterOption::getInstance();
                            $whereForCampaignFilterById = ['rawQuery' => 'product_filter_option.product_filter_option_status = ? AND product_filter_option.product_filter_category_id REGEXP  "' . implode("|", array_unique($categoryMerge)) . '"', 'bindParams' => [1]];
                            $selectColumn = ['product_filter_option.*',
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_name)AS variant_name'),
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_id)AS variant_ids')];

                            $filterOptionForCampaignId = $ObjProductFilterOptionModel->getAllFilterOption($whereForCampaignFilterById, $selectColumn);
                            /*
                            * End For Filter Option
                            */
                            $campaignList[0]->filter_info = $filterOptionForCampaignId;
                        }
                        $FilterData['campaignlist'] = $campaignList;
                    }

                    if ($FilterData) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $FilterData;

                    } else {
                        $response->code = 100;
                        $response->message = "Something went Wrong. No Product Details found.";
                        $response->data = null;
                    }
                } else {
                    $errorMsg = "No parameters were found.";
                    $response->code = 100;
                    $response->message = $errorMsg;
                    $response->data = null;
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
            }
        } else {
            $response->code = 401;
            $response->message = "Invalid request";
            $response->data = null;
        }

        echo json_encode($response, true);

    }

    public function inputChecker($data)
    {

        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = json_encode($data);
        $data = addslashes($data);
        return $data;
    }
}
