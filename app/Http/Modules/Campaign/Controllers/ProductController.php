<?php

namespace FlashSale\Http\Modules\Campaign\Controllers;


//use FlashSale\Http\Modules\Campaign\Models\User;
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
     * Get All product Details
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Vini Dubey
     */
    public function productDetails(Request $request, $productId, $productName)
    {

        $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "product-popup";


        $mytoken = env("API_TOKEN");
//        $user_id = '';
//        if (Session::has('fs_customer')) {
//            $user_id = Session::get('fs_customer')['id'];
//
//        }
        $data = array('product_id' => $productId, 'api_token' => $mytoken);
        $curlResponse = $objCurl->curlUsingPost($url, $data);
        //  print_a($curlResponse);
        if ($curlResponse->code == 200) {
            return view('Campaign.Views.product.product-details', ['productdetails' => $curlResponse->data]);
        }


    }



//$curlResponse = $objCurl->curlUsingPost($url, $data);
//        $url = Session::get("domainname") . env("API_URL") . '/' . "product-ajax-handler";
//
//        $reviewdata['method'] = "productreviewDetail";
//        $reviewdata['product_id'] = $productId;
//        $reviewdata['start'] = 0;
//
//        $curlResponse1 = $objCurl->curlUsingPost($url, $reviewdata);


    public function productAjaxHandler(Request $request)
    {

        $method = $request->input('method');
        $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "product-ajax-handler";
        if ($method != "") {
            switch ($method) {
                case 'productsizingdetails':
                    $productmetaId = $request->input('productmetaid');
                    $mytoken = env("API_TOKEN");
                    $user_id = '';
                    if (Session::has('fs_customer')) {
                        $user_id = Session::get('fs_customer')['id'];

                    }
                    $data = array('productmeta_id' => $productmetaId, 'mytoken' => $mytoken, 'id' => $user_id, 'method' => 'productsizingdetails');
//                      echo "<pre>";print_r($data);die('ere');
                    DB::setFetchMode(PDO::FETCH_ASSOC);
                    $curlResponse = $objCurl->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse->data);
                    }

                    break;
                default:
                    break;

                case "productreviewDetail":

                    $data['method'] = "productreviewDetail";
                    $data['product_id'] = $request->input('productId');
                    $data['start'] = $request->input('start');

                        $curlResponse = $objCurl->curlUsingPost($url, $data);

                        if ($curlResponse->code == 200) {
//                            echo $curlResponse->data;
                            echo json_encode($curlResponse->data);
                        } else
                            echo json_encode('error');

                        break;

                case "add_review":

//                    $data['review_type'] = $request->input('review_type');

                    $data['method'] = "add_review";
                    $data['review_rating'] = $request->input('starrating');
                    $data['review_details'] = $request->input('review_details');
                    $data['product_id'] = $request->input('review_by');
                    $data['review_status'] = $request->input(' reviewstatus');//TODO


                    if (Session::has('fs_user')) {
                        $user_id = Session::get('fs_user')['id'];
                        $data['user_id'] = $user_id;
                        $data['review_by'] = $user_id;
                        $data['review_status_setby'] = $user_id;
                        $data['review_status'] = 'P';
                        $data['review_type'] = 'P';
                        $curlResponse = $objCurl->curlUsingPost($url, $data);

                        if ($curlResponse->code == 200) {
                                echo json_encode($curlResponse);
                        } else

                            echo json_encode('error');
                        break;
                    } else {
                        return 0;
                    }
                case "searchProduct":
                    $data['method']="searchProduct";
                    $data['product_id']=$request->input('productId');
                    $data['searchterm']=$request->input('searchterm');
                    $curlResponse = $objCurl->curlUsingPost($url, $data);

                    if ($curlResponse->code == 200) {
//                            echo $curlResponse->data;
                        echo json_encode($curlResponse->data);
                    } else
                        echo json_encode('error');

                    break;
//                    $searchTerm = $this->getRequest()->getPost('searchterm');
//                    $dataForSearch = array('user_id' => $userId, 'mytoken' => $mytoken, 'searchterm' => $searchTerm);
//                    $urlForSearch = $this->_appSetting->apiLink . 'product/product-search-suggestions/';
//                    $curlResponseForSearch = $objCurlHandler->curlUsingPost($urlForSearch, $dataForSearch);
//                    echo json_encode($curlResponseForSearch, TRUE);
//                    break;

//                default :
//                    break;
            }
        }
    }

    public function productFilter(Request $request)  // Product filter action
    {

        $selectedcolorName = $request->input('selectedcolors');
        $selectedbrandName = $request->input('selectedbrand');
        $selectedsizeName = $request->input('selectedsize');
        $selectedmaterialName = $request->input('selectedmaterial');
        $selectedpatternName = $request->input('selectedpattern');
//        echo"<pre>";print_r($sortbyName);die("xdgv");
        return view('Campaign.Views.product.products');
//        die("szaf");
        $objCurl = CurlRequestHandler::getInstance();
//        $subcategoryName = $request->input('');
        $url = Session::get("domainname") . env("API_URL") . '/' . "product-filter";

        $mytoken = env("API_TOKEN");
        $user_id = '';
        if (Session::has('fs_customer')) {
            $user_id = Session::get('fs_customer')['id'];

        }
        $data = array('mytoken' => $mytoken, 'id' => $user_id, 'color' => $selectedcolorName, 'brand' => $selectedbrandName, 'size' => $selectedsizeName, 'material' => $selectedmaterialName, 'pattern' => $selectedpatternName);

        DB::setFetchMode(PDO::FETCH_ASSOC);
        $curlResponse = $objCurl->curlUsingPost($url, $data);

        if ($curlResponse->code == 200) {
            return view('Campaign.Views.product.products', ['productfilter' => (array)$curlResponse->data]);
        }


    }
}