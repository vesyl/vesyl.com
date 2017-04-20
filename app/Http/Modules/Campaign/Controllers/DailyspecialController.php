<?php

namespace FlashSale\Http\Modules\Campaign\Controllers;


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

class DailyspecialController extends Controller
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

    public function dailyspecialDetails(Request $request)
    {

        $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "dailyspecial-details";

        $user_id = '';
        if (Session::has('fs_customer')) {
            $user_id = Session::get('fs_customer')['id'];

        }
        $mytoken = env("API_TOKEN");
        /*
        $data = array('mytoken' => $mytoken, 'id' => $user_id);
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $curlResponse = $objCurl->curlUsingPost($url, $data);
//        echo "<pre>";print_r((array)$curlResponse->data);die("xdg");
        */
        $flashid = $request->input('flashId');
        $option = $request->input('option');
        $limit = 6;
        $pagenumber = intval($request->input('pagenumber'));
        $priceRangeFrom = intval($request->input('priceRangeFrom'));
        $priceRangeUpto = intval($request->input('priceRangeUpto'));
        $sortBy = $request->input('sort_by');
        $categoryName = urlencode($request->input('catName'));
        $filter_id = $request->input('filter_id');
        $curlResponse = array();
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
//        dd((array)$curlResponse->data['campaignlist'][0]);

        if ($curlResponse->code == 200) {
            return view('Campaign.Views.dailyspecial.dailyspecial-list', ['dailyspecialdetails' => (array)$curlResponse->data['campaignlist'][0]]);
        }

    }

}