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
        //
//        return view("Admin\admin")
    }


    /**
     * Get All Products list By options,campaign type,pricerange.
     * For Product list page.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Vini Dubey
     * @since 29-04-2016
     */
    public function productList(Request $request)
    {

        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $url = Session::get("domainname") . env("API_URL") . '/' . "product-list";
        $subcategoryName = urlencode($request->input('subcatName'));
        $categoryName = urlencode($request->input('catName'));
        $option = $request->input('option');
        $limit = 5;
        $pagenumber = $request->input('pagenumber');
        $priceRangeFrom = $request->input('priceRangeFrom');
        $priceRangeUpto = $request->input('priceRangeUpto');
        $gender = $request->input('gender');
        $sortBy = $request->input('sort_by');
        $filter_id = $request->input('filter_id');
        $campaign_type = $request->input('campaign_type');
        $data = array('api_token' => $mytoken, 'subcategory_name' => $subcategoryName, 'category_name' => $categoryName,
            'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $filter_id,
            'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy, 'campaign_type' => $campaign_type);
        $curlResponse = $objCurl->curlUsingPost($url, $data);
        if ($curlResponse) {
            return view('Product.Views.productList', ['productDetailList' => $curlResponse->data]);
        }

    }

    /**
     * Product ajax handler
     * getFilterProductsList get all product by filter options
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function productAjaxHandler(Request $request)
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
                case 'getFilterProductsList':
                    $subcategoryName = urlencode($request->input('subcatName'));
                    $categoryName = urlencode($request->input('catName'));
                    $option = $request->input('option');
                    $limit = 5;
                    $pagenumber = intval($request->input('pagenumber'));
                    $priceRangeFrom = intval($request->input('priceRangeFrom'));
                    $priceRangeUpto = intval($request->input('priceRangeUpto'));
                    $sortBy = $request->input('sort_by');
                    $campaign_type = $request->input('campaign_type');
                    $url = Session::get("domainname") . env("API_URL") . '/' . "product-list";
                    $data = array('api_token' => $mytoken, 'id' => $user_id, 'subcategory_name' => $subcategoryName, 'category_name' => $categoryName,
                        'option' => $option, 'limit' => $limit, 'pagenumber' => $pagenumber, 'price_range_from' => $priceRangeFrom, 'filter_id' => $option,
                        'price_range_upto' => $priceRangeUpto, 'sort_by' => $sortBy, 'campaign_type' => $campaign_type);
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse->data);
                    }
                    break;
                default:
                    break;
            }

        }

    }


}
