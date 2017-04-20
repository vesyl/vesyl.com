<?php

namespace FlashSaleApi\Http\Controllers\Product;

use DB;
use FlashSaleApi\Http\Controllers\Controller;
use FlashSaleApi\Http\Models\Campaigns;
use FlashSaleApi\Http\Models\ProductCategories;
use FlashSaleApi\Http\Models\ProductFilterOption;
use FlashSaleApi\Http\Models\Products;
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


class ShopController extends Controller
{


    /**
     * Get all active shop list
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function shopList(Request $request)
    {

        $postData = $request->all();

        $response = new stdClass();
        $objUserModel = new User();
        if ($postData) {
            $limit = '';
            if (isset($postData['limit'])) {
                $limit = $postData['limit'];
            }
            $pagenumber = '';
            if (isset($postData['pagenumber'])) {
                $pagenumber = $postData['pagenumber'];

            }
            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                }
            }
            if ($authflag) {//LOGIN TOKEN
                if (isset($limit) && isset($pagenumber)) {

                    $pagenumber = 1;
                    $limit = 1;
                    if (isset($postData['limit']) && !empty($postData['limit']) && isset($postData['pagenumber']) && !empty($postData['pagenumber'])) {
                        $pagenumber = $postData['pagenumber'];
                        $limit = $postData['limit'];
                    }
                    $offset = ((int)$pagenumber - 1) * ((int)$limit);
                    $whereShop = ['rawQuery' => 'shop_status =?', 'bindParams' => [1]];
                    $selectedColumns = ['shops.*'];
                    $objShopModel = Shops::getInstance();
                    $shopList = $objShopModel->getAllActiveShop($whereShop, $selectedColumns, $offset, $limit);
                    $finalData = [
                        'count' => count($objShopModel->getAllActiveShop($whereShop, ['shop_id'])),
                        'shops' => $shopList
                    ];

                    $response->code = 200;
                    $response->message = (count($shopList) > 0) ? "Success" : 'No more shops details found.';
                    $response->data = $finalData;

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

    /**
     * Get all shop products based on shop id
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function shopDetail(Request $request)
    {

        $postData = $request->all();

        $response = new stdClass();
        $objUserModel = new User();
        if ($postData) {
            $limit = '';
            if (isset($postData['limit'])) {
                $limit = $postData['limit'];
            }
            $shop_id = '';
            if (isset($postData['shop_id'])) {
                $shop_id = $postData['shop_id'];
            }
            $pagenumber = '';
            if (isset($postData['pagenumber'])) {
                $pagenumber = $postData['pagenumber'];

            }
            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;

                }
            }
            if ($authflag) {//LOGIN TOKEN
                if (isset($limit) && isset($pagenumber) && isset($shop_id)) {

                    $pagenumber = 1;
                    $limit = 1;
                    if (isset($postData['limit']) && !empty($postData['limit']) && isset($postData['pagenumber']) && !empty($postData['pagenumber'])) {
                        $pagenumber = $postData['pagenumber'];
                        $limit = $postData['limit'];
                    }
                    $offset = ((int)$pagenumber - 1) * ((int)$limit);

                    $whereShop = ['rawQuery' => 'shops.shop_id = ? AND products.product_status = ? AND products.product_type = ?', 'bindParams' => [$shop_id, 1, 0]];
                    $selectedColumns = ['shops.*', 'products.*', 'product_images.*'];
                    $objShopModel = Shops::getInstance();
                    $shopDetails = $objShopModel->getAllShopsByShopId($whereShop, $selectedColumns, $offset, $limit);

                    if ($shopDetails) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $shopDetails;

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
}