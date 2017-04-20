<?php

namespace FlashSale\Http\Modules\Campaign\Controllers;


//use FlashSale\Http\Modules\Admin\Models\ProductOption;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use PDO;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Curl\CurlRequestHandler;

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
     * Get Flashsale Products And Category
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Vini Dubey
     * @since 24-02-2016
     */
    public function flashsaleDetails(Request $request)
    {
        $flashid = $request->input('flashId');
        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $user_id = '';
//        if (Session::has('fs_customer')) {
//            $user_id = Session::get('fs_customer')['id'];
//        }
        $option = $request->input('option');
        $limit = 6;
        $pagenumber = intval($request->input('pagenumber'));
        $priceRangeFrom = intval($request->input('priceRangeFrom'));
        $priceRangeUpto = intval($request->input('priceRangeUpto'));
        $sortBy = $request->input('sort_by');
        $categoryName = urlencode($request->input('catName'));
        $filter_id = $request->input('filter_id');

        if (isset($categoryName) && !empty($categoryName)) {
            $url = Session::get("domainname") . env("API_URL") . '/' . "product-list";
            $data = array('api_token' => $mytoken, 'campaign_id' => $flashid, 'category_name' => $categoryName,
                'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $option,
                'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy);
            $curlResponse = $objCurl->curlUsingPost($url, $data);

        } else if ($categoryName == '' && empty($categoryName)) {
            $url = Session::get("domainname") . env("API_URL") . '/' . 'campaign-details';
            $data = array('api_token' => $mytoken, 'campaign_id' => $flashid,
                'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $option,
                'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy);
            $curlResponse = $objCurl->curlUsingPost($url, $data);

        }

        if ($curlResponse->code == 200) {
            return view('Campaign.Views.flashsale.flashsale-list', ['flashsaledetails' => isset($curlResponse->data['campaignlist'][0]) ? $curlResponse->data['campaignlist'][0] : array()]);
        }
    }


    /**
     * @param Request $request
     */
    public function flashsaleAjaxHandler(Request $request)
    {

        $inputData = $request->input();
        $method = $inputData['method'];
        switch ($method) {

            case 'getProductDetailsForPopUp':
                $productId = $request->input('prodId');
                $objCurl = CurlRequestHandler::getInstance();
                $url = Session::get("domainname") . env("API_URL") . '/' . "product-popup";
                $mytoken = env("API_TOKEN");
                $user_id = '';
                if (Session::has('fs_customer')) {
                    $user_id = Session::get('fs_customer')['id'];
                }
                $data = array('api_token' => $mytoken, 'id' => $user_id, 'product_id' => $productId);
                $curlResponse = $objCurl->curlUsingPost($url, $data);
                if ($curlResponse->code == 200) {
                    echo json_encode($curlResponse->data);
                }
                break;
            case 'getOptionVariantDetails':
                $variantId = $request->input('variantId');
                $priceModifier = $request->input('priceModifier');
                $prodId = $request->input('prodId');
                $selectedCombination = implode("_", $request->input('selectedCombination'));
                $objCurl = CurlRequestHandler::getInstance();
                $url = Session::get("domainname") . env("API_URL") . "/flashsale-ajax-handler";
                $mytoken = env("API_TOKEN");
                $user_id = '';
//                if (Session::has('fs_customer')) {
//                    $user_id = Session::get('fs_customer')['id'];
//                }
                $data = array('api_token' => $mytoken, 'variant_id' => $variantId, 'product_id' => $prodId, 'selectedCombination' => $selectedCombination, 'method' => 'optionVariantDetails');
                $curlResponse = $objCurl->curlUsingPost($url, $data);
                if ($curlResponse->code == 200) {
                    echo json_encode($curlResponse->data);
                }
                break;
            case 'getFilterCampaignDetails':
                $flashid = $request->input('flashId');
                $objCurl = CurlRequestHandler::getInstance();
                $mytoken = env("API_TOKEN");
                $user_id = '';
//                if (Session::has('fs_customer')) {
//                    $user_id = Session::get('fs_customer')['id'];
//                }
                $option = $request->input('option');
                $limit = 6;
                $pagenumber = intval($request->input('pagenumber'));
                $priceRangeFrom = intval($request->input('priceRangeFrom'));
                $priceRangeUpto = intval($request->input('priceRangeUpto'));
                $sortBy = $request->input('sort_by');
                $categoryName = urlencode($request->input('catName'));

                if (isset($categoryName) && !empty($categoryName)) {
                    $url = Session::get("domainname") . env("API_URL") . '/' . "product-list";
                    $data = array('api_token' => $mytoken, 'campaign_id' => $flashid, 'category_name' => $categoryName,
                        'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $option,
                        'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy);
                    $curlResponse = $objCurl->curlUsingPost($url, $data);

                } else if ($categoryName == '' && empty($categoryName)) {
                    $url = Session::get("domainname") . env("API_URL") . '/' . 'campaign-details';
                    $data = array('api_token' => $mytoken, 'campaign_id' => $flashid,
                        'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $option,
                        'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy);
                    $curlResponse = $objCurl->curlUsingPost($url, $data);
                }
                if ($curlResponse->code == 200) {
                    echo json_encode($curlResponse->data['campaignlist'][0]);
                }
                break;
            default:
                break;

        }
    }


}