<?php
namespace FlashSale\Http\Modules\Product\Controllers;


use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use PDO;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Curl\CurlRequestHandler;

class ShopController extends Controller
{


    /**
     * shoplist get all active shops
     * @param Request $request
     * @return View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function shopList(Request $request)
    {

        $inputData = $request->all();
        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $url = Session::get("domainname") . env("API_URL") . '/' . "shop-list";
        $limit = 6;
        $pagenumber = 1;
        $data = array('api_token' => $mytoken, 'limit' => $limit, 'pagenumber' => $pagenumber);
        $curlResponse = $objCurl->curlUsingPost($url, $data);
        if ($curlResponse->code == 200) {
            return view('Product.Views.shop.shop-list', ['shopList' => $curlResponse->data]);
        }

    }


    /**
     * Get All shops products based on shopid
     * @param Request $request
     * @param $shopId
     * @param $shopName
     * @return View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function shopDetail(Request $request, $shopId, $shopName)
    {

        $inputData = $request->all();
        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $url = Session::get("domainname") . env("API_URL") . '/' . "product-list";
        $option = $request->input('option');
        $limit = 6;
        $pagenumber = intval($request->input('pagenumber'));
        $priceRangeFrom = $request->input('priceRangeFrom');
        $priceRangeUpto = $request->input('priceRangeUpto');
        $gender = $request->input('gender');
        $sortBy = $request->input('sort_by');
        $filter_id = $request->input('filter_id');
        $campaign_type = $request->input('campaign_type');
        $data = array('api_token' => $mytoken, 'shop_id' => $shopId,
            'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $filter_id,
            'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy);
        $curlResponse = $objCurl->curlUsingPost($url, $data);
//        dd($curlResponse);//todo jhaa coding here in this API again by Vini
        if ($curlResponse->code == 200) {
            if (!empty($curlResponse->data['shopProductsList'])) {
                return view('Product.Views.shop.shop-detail', ['shopDetail' => $curlResponse->data['shopProductsList'][0]]);
            } else {
                return view('Product.Views.shop.shop-detail', ['shopProductError' => "There are no products in the shop."]);
            }
        } else {
            return view('Product.Views.shop.shop-detail', ['shopError' => "There is no such shop available."]);
        }

    }

    /**
     * Shop ajax handler
     * getShopsListByFilter method details by load more
     * getProductShopDetails method for getting all shops product
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function shopAjaxHandler(Request $request)
    {

        $method = $request->input('method');
        $api_url = Session::get("domainname") . env("API_URL");
        $mytoken = env('API_TOKEN');
        $objCurlHandler = CurlRequestHandler::getInstance();
        $user_id = '';
        if (Session::has('fs_customer')) {
            $user_id = Session::get('fs_customer')['id'];
        }
        if ($method) {
            switch ($method) {

                case'getShopsListByFilter':
                    $inputData = $request->all();
                    $objCurl = CurlRequestHandler::getInstance();
                    $mytoken = env("API_TOKEN");
                    $url = Session::get("domainname") . env("API_URL") . '/' . "shop-list";
                    $limit = 6;
                    $pagenumber = intval($request->input('pagenumber'));
                    $data = array('api_token' => $mytoken, 'limit' => $limit, 'pagenumber' => $pagenumber);
                    $curlResponse = $objCurl->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse->data);
                    }
                    break;
                case 'getProductShopDetails':
                    $inputData = $request->all();
                    $shopId = $request->input('shopId');
                    $objCurl = CurlRequestHandler::getInstance();
                    $mytoken = env("API_TOKEN");
                    $url = Session::get("domainname") . env("API_URL") . '/' . "product-list";
                    $option = $request->input('option');
                    $limit = 5;
                    $pagenumber = intval($request->input('pagenumber'));
                    $priceRangeFrom = $request->input('priceRangeFrom');
                    $priceRangeUpto = $request->input('priceRangeUpto');
                    $gender = $request->input('gender');
                    $sortBy = $request->input('sort_by');
                    $filter_id = $request->input('filter_id');
                    $campaign_type = $request->input('campaign_type');
                    $data = array('api_token' => $mytoken, 'shop_id' => $shopId,
                        'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $filter_id,
                        'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy);
                    $curlResponse = $objCurl->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse->data['shopProductsList'][0]);
                    }
                    break;
                default:
                    break;
            }
        }
    }
}