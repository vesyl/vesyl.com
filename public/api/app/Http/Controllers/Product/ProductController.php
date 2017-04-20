<?php

namespace FlashSaleApi\Http\Controllers\Product;

use DB;
use FlashSaleApi\Http\Controllers\Controller;
use FlashSaleApi\Http\Models\Campaigns;
use FlashSaleApi\Http\Models\ProductCategories;
use FlashSaleApi\Http\Models\ProductFilterOption;
use FlashSaleApi\Http\Models\Products;
use FlashSaleApi\Http\Models\Reviews;
use FlashSaleApi\Http\Models\Shops;
use FlashSaleApi\Http\Models\User;
use FlashSaleApi\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use stdClass;


class ProductController extends Controller
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

//        return view("Admin\admin")
    }

    /**
     * Get all the products based on filtering and category selection.
     * Gets product listing
     * Gets campaign by campaign id and product list,filtering option
     * Gets campaign type product and filtering
     * Category,Subcategory,Filters & Feature Variant name,Sort By.
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 05/05/2016
     */
    public function productList(Request $request)
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

            $shopId = '';
            if (isset($postData['shop_id'])) {
                $shopId = $postData['shop_id'];
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
                if (isset($postData['limit']) && isset($postData['pagenumber'])) {

                    $filterOptionInfo = [];

                    $objProductModel = Products::getInstance();
                    $objProductCategoryModel = ProductCategories::getInstance();

                    $wherePriceRange = ['rawQuery' => 1];
                    if (isset($postData['price_range_from']) && !empty($postData['price_range_from']) && isset($postData['price_range_upto']) && !empty($postData['price_range_upto'])) {
                        $priceFrom = intval($postData['price_range_from']);
                        $priceTo = intval($postData['price_range_upto']);
//                        $wherePriceRange = ['rawQuery' => '(products.price_total >= ' . $priceFrom . ' AND products.price_total <= ' . $priceTo . ')'];
                        $wherePriceRange = ['rawQuery' => '(products.price_total BETWEEN ' . $priceFrom . ' AND ' . $priceTo . ')'];
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
                    if (isset($postData['category_name']) && !empty($postData['category_name'])) {
                        $categoryName = urldecode($postData['category_name']);
                        if (isset($postData['subcategory_name']) && !empty($postData['subcategory_name'])) {
                            $subcategoryName = urldecode($postData['subcategory_name']);
                        }
                        $objCategoryModel = ProductCategories::getInstance();
                        $whereCategoryName = ['rawQuery' => 'category_name = ? AND parent_category_id = ? AND category_status = ?', 'bindParams' => [$categoryName, 0, 1]];
                        $selectedColumn = ['product_categories.*'];
                        if (isset($postData['campaign_type'])) {
                            $whereCategoryName = ['rawQuery' => 'category_name = ? AND category_status = ?', 'bindParams' => [$categoryName, 1]];
                        }
                        $categoryDetails = $objCategoryModel->getCategoryWhere($whereCategoryName, $selectedColumn);
                        if (!empty($categoryDetails)) {

                            $categoryTreeIds = array($categoryDetails[0]->category_id);
                            $clauseForCTI = implode(',', array_fill(0, ((count($categoryTreeIds)) < 1 ? 1 : (count($categoryTreeIds))), '?'));
                            $whereForCategoryFilter = ['rawQuery' => "category_id IN($clauseForCTI)", 'bindParams' => $categoryTreeIds];

                            $whereForSubcat = ['rawQuery' => 'parent_category_id = ? AND category_status = ?', 'bindParams' => [$categoryDetails[0]->category_id, 1]];
                            $selectedColumn = ['product_categories.*', DB::raw('GROUP_CONCAT(DISTINCT category_id) AS subcatIds')];
                            $allSubcatsInCat = $objCategoryModel->getAllCategoryWhereByGrouping($whereForSubcat, $selectedColumn);

                            if (!empty($allSubcatsInCat)) {
                                $allSubcatsInCatIds = array();
                                $count = 1;
                                foreach ($allSubcatsInCat as $valueAllSubcatsInCat) {
                                    array_push($allSubcatsInCatIds, $valueAllSubcatsInCat->subcatIds);
                                    $count++;
                                }
                                $categoryTreeIds = array_merge($categoryTreeIds, $allSubcatsInCatIds);

                                if ($subcategoryName != '') {
                                    $whereForSelectedSubcat = ['rawQuery' => 'category_name = ? AND parent_category_id = ?', 'bindParams' => [$subcategoryName, $categoryDetails[0]->category_id]];

                                    $selectedColumn = ['product_categories.*'];
                                    $selectedSubcatDetails = $objCategoryModel->getCategoryWhere($whereForSelectedSubcat, $selectedColumn);

                                    if ($selectedSubcatDetails) {
                                        $allSubcatsInCatIds = array($selectedSubcatDetails[0]->category_id);
                                        $categoryTreeIds = $allSubcatsInCatIds;
                                    }
                                }

                                $clauseForCTI = implode(',', array_fill(0, ((count($categoryTreeIds)) < 1 ? 1 : (count($categoryTreeIds))), '?'));
                                $whereForCategoryFilter = ['rawQuery' => "parent_category_id IN ($clauseForCTI)", 'bindParams' => $categoryTreeIds];

                                $selectedColumn = ['product_categories.*', DB::raw('GROUP_CONCAT(DISTINCT category_id) AS subcatIds')];
                                $allSubsubcatsInCat = $objCategoryModel->getAllCategoryWhereByGrouping($whereForCategoryFilter, $selectedColumn);

                                if (!empty($allSubsubcatsInCat)) {
                                    foreach ($allSubsubcatsInCat as $valueAllSubsubcatsInCat) {
                                        $categoryTreeIds = array_merge($categoryTreeIds, explode(",", $valueAllSubsubcatsInCat->subcatIds));
                                    }
                                }
                            }
                            $clauseForCTI = implode(',', array_fill(0, ((count($categoryTreeIds)) < 1 ? 1 : (count($categoryTreeIds))), '?'));
                            $whereForCategoryFilter = ['rawQuery' => "category_id IN ($clauseForCTI)", 'bindParams' => $categoryTreeIds];
                            /*
                             *  For Filter Option and features
                             */

                            $ObjProductFilterOptionModel = ProductFilterOption::getInstance();
                            $where = ['rawQuery' => 'product_filter_option.product_filter_option_status = ? AND product_filter_option.product_filter_category_id REGEXP "' . implode("|", array_unique($categoryTreeIds)) . '"', 'bindParams' => [1]];
                            $selectColumn = ['product_filter_option.*',
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_name) AS variant_name'),
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_id) AS variant_ids')];

                            $filterOptionInfo = $ObjProductFilterOptionModel->getAllFilterOption($where, $selectColumn);

                            /*
                             *  End for filter option and featur
                             */
                        }
                    }
                    $whereOption = ['rawQuery' => 1];
                    if (isset($postData['option']) && !empty($postData['option'])) {
                        $whereOption = ['rawQuery' => 'product_option_variants.variant_id IN (' . $postData["option"] . ')'];
                    }

                    $whereFilterVariant = ['rawQuery' => 1];
                    if (isset($postData['filter_id']) && !empty($postData['filter_id'])) {
                        $whereFilterVariant = ['rawQuery' => 'filter_product_relation.option_ids IN(' . $postData['filter_id'] . ')'];
                    }

                    $whereForFilter = $whereOption;
                    $whereForActualProducts = ['rawQuery' => 'product_status = ?', 'bindParams' => [1]];
                    $selectedColumnForactualproducts = ['products.*',
                        'product_images.image_url',
                        'productmeta.*',
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id)AS option_ids'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____")AS variant_datas'),
                        DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')];

                    $pagenumber = 1;
                    $limit = 5;
                    if (isset($postData['limit']) && !empty($postData['limit']) && isset($postData['pagenumber']) && !empty($postData['pagenumber'])) {
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

                        $campaignDetails = $objCampaingsModel->getFlashsaleDetail($where, $selectedColumn);
                        $getProducts = '';
                        $getcategory = '';
                        if (isset($campaignDetails[0]) && !empty($campaignDetails[0])) {
                            $campaignDetails[0]->product_info = array();
                            $camp = json_decode($campaignDetails[0]->for_category_ids, true);
                            $categoryMerge = array_merge(array_keys($camp), array_flatten($camp));
                            $whereCampaignId = ['rawQuery' => '(category_id IN(' . implode(",", $categoryMerge) . ') OR
                    products.product_id IN(' . $campaignDetails[0]->for_product_ids . ')) AND
                    product_images.image_type = 0'
                            ];
                            $selectedColumnForCampaignId = [
                                'products.*',
                                'product_images.image_url',
                                'productmeta.*',
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id)AS option_ids'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____")AS variant_datas'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')];

                            $getProducts = $objProductModel->getProductDetailsByCategoryIds($whereCampaignId, $selectedColumnForCampaignId, $whereForFilter, $sortClause, $limit, $offset, $wherePriceRange, $whereFilterVariant);
                            $getProductsCount = count($objProductModel->getProductDetailsByCategoryIdsCount($whereCampaignId, $selectedColumnForCampaignId, $wherePriceRange));
                            $campaignDetails[0]->product_info = $getProducts;
                            $campaignDetails[0]->total = $getProductsCount;

                            /*
                            * For Campiagn Filter Option Based On Campaign Id
                           */
                            $ObjProductFilterOptionModel = ProductFilterOption::getInstance();
                            $whereForCampaignFilterById = ['rawQuery' => 'product_filter_option.product_filter_option_status = ? AND product_filter_option.product_filter_category_id REGEXP  "' . implode("|", array_unique($categoryTreeIds)) . '"', 'bindParams' => [1]];
                            $selectColumn = ['product_filter_option.*',
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_name)AS variant_name'),
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_id)AS variant_ids')];

                            $filterOptionForCampaignId = $ObjProductFilterOptionModel->getAllFilterOption($whereForCampaignFilterById, $selectColumn);
                            /*
                            * End For Filter Option
                            */
                            $campaignDetails[0]->filter_info = $filterOptionForCampaignId;
                        }
                        $FilterDatas['campaignlist'] = $campaignDetails;
                    }

                    /*
                     * Get Products based on campaign type where FS = Flashsale And DS = Dailyspecial
                     * Get All Products for specific campaign category.
                     * Date: 04 june 2016
                     */
                    if (isset($postData['campaign_type']) == 'FS' && !empty($postData['campaign_type'])) {
                        $campType = 2;
                    } else if (isset($postData['campaign_type']) == 'DS' && !empty($postData['campaign_type'])) {
                        $campType = 1;
                    }
                    if (isset($campType)) {
                        $whereCampaignType = ['rawQuery' => 'campaign_type = ? AND for_category_ids LIKE \'%"' . $categoryDetails[0]->category_id . '"%\'', 'bindParams' => [$campType]];
                        $selectedColumnForCampaignType = ['campaigns.*'];
                        $campaigns = $objCampaingsModel->getFlashsaleDetail($whereCampaignType, $selectedColumnForCampaignType);
                        if (isset($campaigns) && !empty($campaigns)) {
                            $campProductIds = implode(',', array_unique(array_flatten(array_map(function ($V) {
                                return explode(',', $V->for_product_ids);
                            }, $campaigns))));
                            if ($campProductIds != '') {
                                $whereForCampaignProdId = ['rawQuery' => 'products.product_id IN(' . $campProductIds . ') AND  product_images.image_type = 0'];
                                $selectedColumnForCampaignProdId = ['products.*',
                                    'product_images.image_url',
                                    'productmeta.*',
                                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id)AS option_ids'),
                                    DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____")AS variant_datas'),
                                    DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')];
                                $getCampaignProducts = $objProductModel->getProductDetailsByCategoryIds($whereForCampaignProdId, $selectedColumnForCampaignProdId, $whereForFilter, $sortClause, $limit, $offset, $wherePriceRange, $whereFilterVariant);
                                $getCampaignProductsCount = count($objProductModel->getProductDetailsByCategoryIdsCount($whereForCampaignProdId, $selectedColumnForCampaignProdId, $wherePriceRange));
                            }
                            $FilterDatas['campaignCategoryProducts']['product'] = $getCampaignProducts;
                            $FilterDatas['campaignCategoryProducts']['total'] = $getCampaignProductsCount;
                        }

                        /*
                         * Filter options for campaigns products
                         */
                        $ObjProductFilterOptionModel = ProductFilterOption::getInstance();
                        $whereFilterForCampaign = ['rawQuery' => 'product_filter_option.product_filter_option_status = ? AND product_filter_option.product_filter_category_id LIKE \'%' . $categoryDetails[0]->category_id . '%\'', 'bindParams' => [1]];
                        $selectColumn = ['product_filter_option.*',
                            DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_name)AS variant_name'),
                            DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_id)AS variant_ids')];

                        $filterOptionForCampaign = $ObjProductFilterOptionModel->getAllFilterOption($whereFilterForCampaign, $selectColumn);
                        $FilterDatas['campaignCategoryProducts']['filter'] = $filterOptionForCampaign;

                    }

                    /**
                     * For Shop list
                     */
                    if (isset($postData['shop_id'])) {
                        $whereShop = [
                            'rawQuery' => 'shops.shop_id = ? AND products.product_status = ? AND products.product_type = ?',
                            'bindParams' => [$shopId, 1, 0]
                        ];
                        $selectedColumns = ['shops.*', 'products.*', 'product_images.*'];
                        $objShopModel = Shops::getInstance();
                        $shopDetails = $objShopModel->getAllShopsByShopId($whereShop, $selectedColumns, $offset, $limit);
//                        echo '<pre>';
//                        print_r($shopDetails);
//                        print_a($shopDetails);

                        $productIDS = implode(',', array_map(function ($shop) {
                            return $shop->product_id;
                        }, $shopDetails));

                        $productCatIDS = implode(',', array_map(function ($shop) {
                            return $shop->category_id;
                        }, $shopDetails));

//                        echo $productIDS;
//                        print_a(implode(',', array_map(function ($shop) {
//                            return $shop->parent_category_id;
//                        }, $shopDetails)));
                        $getProducts = '';
                        $getcategory = '';
                        if (isset($shopDetails[0]) && !empty($shopDetails[0])) {
                            $shopDetails[0]->product_info = array();
                            $whereForShopDetails = [
                                'rawQuery' => '(category_id IN(' . $productCatIDS . ')  OR products.product_id IN(' . $productIDS . ')) AND product_images.image_type = 0'
                            ];
//                            print_a($whereForShopDetails);
                            $selectedColumnForShop = [
                                'products.*',
                                'product_images.image_url',
                                'productmeta.*',
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.option_id)AS option_ids'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_options.option_name)AS option_names'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variant_relation.variant_data  SEPARATOR "____")AS variant_datas'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_option_variants_combination.variant_ids) AS variant_ids_combination')
                            ];

                            $getProducts = $objProductModel->getProductDetailsByCategoryIds($whereForShopDetails, $selectedColumnForShop, $whereForFilter, $sortClause, $limit, $offset, $wherePriceRange, $whereFilterVariant, 'shop_id');
//                            print_a($getProducts);
                            $getProductsCount = count($objProductModel->getProductDetailsByCategoryIdsCount($whereForShopDetails, $selectedColumnForShop, $wherePriceRange));
                            $shopDetails[0]->product_info = $getProducts;
                            $shopDetails[0]->total = $getProductsCount;

                            /*
                            * For Campiagn Filter Option Based On Campaign Id
                           */
                            $ObjProductFilterOptionModel = ProductFilterOption::getInstance();
                            $whereForCampaignFilterById = [
                                'rawQuery' => 'product_filter_option.product_filter_option_status = ?
                                 AND FIND_IN_SET(' . $shopDetails[0]->parent_category_id . ',product_filter_option.product_filter_category_id)',
                                'bindParams' => [1]
                            ];
                            $selectColumn = [
                                'product_filter_option.*',
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_name)AS variant_name'),
                                DB::raw('GROUP_CONCAT(DISTINCT pg.product_filter_option_id)AS variant_ids')
                            ];

                            $filterOptionForCampaignId = $ObjProductFilterOptionModel->getAllFilterOption($whereForCampaignFilterById, $selectColumn);
                            /*
                            * End For Filter Option
                            */
                            $shopDetails[0]->filter_info = $filterOptionForCampaignId;
                        }
                        $FilterDatas['shopProductsList'] = $shopDetails;

                    }

                    $productsFiltered = $objProductModel->getProducts($whereForActualProducts, $whereForCategoryFilter, $whereForFilter, $sortClause, $limit, $offset, $wherePriceRange, $selectedColumnForactualproducts, $whereFilterVariant);

                    $productscount = count($objProductModel->getProductsCount($whereForActualProducts, $whereForCategoryFilter, $whereForFilter, $sortClause, $wherePriceRange, $whereFilterVariant, $selectedColumnForactualproducts));
                    $FilterDatas['productList']['filter'] = $filterOptionInfo;
                    $FilterDatas['productList']['product'] = $productsFiltered;
                    $FilterDatas['productList']['total'] = $productscount;

                    if ($FilterDatas) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $FilterDatas;

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

    public function productAjaxHandler(Request $request)
    {

        $method = $request->input('method');
        $objReview = Reviews::getInstance();
        $response = new stdClass();
        if ($method != "") {
            switch ($method) {
                case 'product-filter-option':
                    $API_TOKEN = env('API_TOKEN');
                    if ($request->isMethod("POST")) {
                        $postData = $request->all();

                        if (isset($postData['api_token'])) {
                            $apitoken = $postData['api_token'];

                        }
                        if ($apitoken == $API_TOKEN) {
                            $ObjProductFilterOptionModel = ProductFilterOption::getInstance();
                            $where = ['rawQuery' => 'product_filter_option_status = ?', 'bindParams' => [1]];
                            $selectColumn = ['product_filter_option.*', 'product_features.*', 'product_feature_variants.*',
                                DB::raw('GROUP_CONCAT(DISTINCT product_filter_category_id)AS product_filter_categories'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_feature_variants.variant_name)AS feature_variant_names'),
                                DB::raw('GROUP_CONCAT(DISTINCT product_feature_variants.description)AS feature_description')];
                            $filterOptionInfo = $ObjProductFilterOptionModel->getAllFilterOption($where, $selectColumn);
                            if ($filterOptionInfo) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $filterOptionInfo;
                            } else {
                                $response->code = 400;
                                $response->message = "No user Details found.";
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

                case "productreviewDetail":
                    $data['review_for'] = $request->input('product_id');
                    $data['start'] = $request->input('start');


                    $reviewsDetail = $objReview->getAllPRWhere($data);
                    $reviewCount = $objReview->getAllPRcountwhere($data);
                    $avreagedetails =$objReview->getReviewsavg($data);
                    $reviewdata['review_details']=$reviewsDetail;
                    $reviewdata['total_count']=$reviewCount;
                    $reviewdata['avreagedetails']=$avreagedetails;
                    if ($reviewdata) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $reviewdata;
                    } else {
                        $response->code = 198;
                        $response->message = "No Review Details.";
                        $response->data = null;
                    }
                    return json_encode($response);
                    break;
//                    dd($Avreagedetails);

//                    dd($ReviewsDetail);
//                    $ReviewsDetail = json_decode($ReviewsDetail);
//                    $ReviewsDetail = $ReviewsDetail->data;
//                      if($ReviewsDetail){
//                              $count=count($ReviewsDetail);
//                          if($count!=0){
//                                  $rating= 0;
//                                  $avgrating=0.0;
//                                  foreach($ReviewsDetail as $val){
//                                      $rating=$rating + $val->review_rating;
//                                  }
//                                  $avgrating = round(($rating / $count),1);
//                               $data['count']=$count;
//                               $data['avg_rating']=$avgrating;
////                              dd($avgrating);
//                              }
//                          }


//
//                case "avgreviews":
//
//
//                    if ($Avreagedetails) {
//                        $response->code = 200;
//                        $response->message = "Success";
//                        $response->data = $Avreagedetails;
//                    } else {
//                        $response->code = 198;
//                        $response->message = "No Review Details.";
//                        $response->data = null;
//                    }
//                    return json_encode($response);
//                    break;
                case "add_review":
                    $data['review_rating'] = $request->input('review_rating');
                    $data['review_details'] = $request->input('review_details');
                    $data['review_for'] = $request->input('product_id');
                    $data['review_status'] = $request->input('review_status');
                    $data['review_status_setby'] = $request->input('review_status_setby');
                    $data['review_by'] = $request->input('review_by');
                    $data['review_type'] = $request->input('review_type');
                    $ReviewsDetail = $objReview->getAlladdReviews($data);

                    if ($ReviewsDetail) {
                    return $ReviewsDetail;
                    }

                default:
                    break;
            }

        }


    }
}