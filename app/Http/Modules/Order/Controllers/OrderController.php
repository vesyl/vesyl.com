<?php


namespace FlashSale\Http\Modules\Order\Controllers;

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
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;


class OrderController extends Controller
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
     * order ajax handler
     * add-to-cart methid for inserting cart data into orders
     * cartDetails method for getting all cart details information of users by user id
     * removerCartOrder method for removing cart details from order by cart id/
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function orderAjaxHandler(Request $request)
    {
        $inputData = $request->input();
        $method = $inputData['method'];
        $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "order-ajax-handler";
        $mytoken = env("API_TOKEN");
        switch ($method) {

            case 'add-to-cart':
                $user_id = '';
                if (Session::has('fs_user')) {
                    $user_id = Session::get('fs_user')['id'];
                } else if (Session::has('fs_buyer')) {//todo if this is done then user and buyer cannot operate in same browser window
                    $user_id = Session::get('fs_buyer')['id'];
                }
                $productId = $request->input('prodId');
                $quantity = $request->input('quantity');
                $combinationId = isset($inputData['selected']) ? implode(",", $request->input('selected')) : 0;
                $cartBind['quantity'] = $quantity;
                $cartBind['product_id'] = $productId . '-' . $combinationId;
                $cartBind['for_user_id'] = $user_id;
                $data = array('api_token' => $mytoken, 'id' => $user_id, 'mainCartData' => json_encode([$cartBind]), 'method' => 'insert-order');
                $curlResponse = $objCurl->curlUsingPost($url, $data);
                if ($curlResponse->code == 200) {
                    echo json_encode(['status' => 'success', 'msg' => $curlResponse->message]);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                }
                break;

            case 'cartDetails':
//                if (isset($_COOKIE['cart_cookie_name']) && !empty(json_decode($_COOKIE['cart_cookie_name']))) {
//                    $cartCookie = json_decode($_COOKIE['cart_cookie_name']);
//                    foreach ($cartCookie as $key => $value) {
//                        $combinationId = implode("#", array_filter(array_unique(explode("@", $value->combination_id))));
//                        $productId = $value->prodId;
//                        $cartBind[$key]['quantity'] = $value->quantity;
//                        $cartBind[$key]['product_id'] = $productId . '-' . $value->combination_id;
//
//                    }
//                    $finalCartInfo = array_map(function ($values) {
//                        $temp['quantity'] = $values['quantity'];
//                        $temp['product_id'] = $values['product_id'];
//                        return $temp;
//
//                    }, $cartBind);
//
//                    $dataForCart = array('api_token' => $mytoken, 'cookieCartData' => json_encode($finalCartInfo), 'method' => 'user-cookie-cart-details');
//                    $curlResponseForCookie = $objCurl->curlUsingPost($url, $dataForCart);
////                    print_a($curlResponseForCookie);
//                    if ($curlResponseForCookie->code == 200) {
//                        echo json_encode($curlResponseForCookie->data);
//                    }
//                } else {
                if (Session::has('fs_user')) {
                    $user_id = Session::get('fs_user')['id'];
                } else if (Session::has('fs_buyer')) {//todo if this is done then user and buyer cannot operate in same browser window
                    $user_id = Session::get('fs_buyer')['id'];
                }
                $data = array('api_token' => $mytoken, 'id' => $user_id, 'method' => 'user-cart-details');
                $curlResponse = $objCurl->curlUsingPost($url, $data);
                if ($curlResponse->code == 200) {
                    echo json_encode($curlResponse->data);
//                    }
                }
                break;
            case 'removerCartOrder':
                $user_id = '';
                if (Session::has('fs_user')) {
                    $user_id = Session::get('fs_buyer')['id'];
                } else if (Session::has('fs_user')) {
                    $user_id = Session::get('fs_buyer')['id'];
                }
                $order_id = $request->input('orderId');
                $data = array('api_token' => $mytoken, 'id' => $user_id, 'order_id' => $order_id, 'method' => 'remove-cart-detail');
                $curlResponse = $objCurl->curlUsingPost($url, $data);
                if ($curlResponse->code == 200) {
                    echo json_encode(['status' => 'success', 'msg' => 'Removed From Cart']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                }
                break;
            default:
                break;
        }

    }


    public function orderHistory(Request $request)
    {
        //payumoney test start
//        {"paymentParts":
//            [
//                {
//                    "name":"splitId1",
//                    "merchantId ":"4510911",
//                    "value":"10",
//                    "commission" : "5",
//                    "description":"splitId1 summary"
//                },
//                {
//                    "name":"splitId2",
//                    "merchantId ":"4510912",
//                    "value":"10",
//                    "commission" : "5",
//                    "description":"splitId2 summary"
//                }
//            ]
//        }
//        $temp = json_encode(array("paymentParts" => array(["name" => 123456, "merchantId" => 1234567, "value" => 100000, "commission" => 1000], ["name" => 123456, "merchantId" => 1234467, "value" => 100000, "commission" => 1000])), true);
//        echo $temp;
//        dd(strlen($temp));
        //payumoney test end

// DON'T need this comment block here
        /* $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "order-ajax-handler";
        $mytoken = env("API_TOKEN");
        $userId = Session::get('fs_user')['id'];
        $data = array('api_token' => $mytoken, 'id' => $userId, 'method' => 'orderHistory');
        $curlResponse = json_decode($objCurl->curlUsingPost($url, $data), true); */

        return view("Order/Views/order/orderHistory");

    }

    public function ordersDatatablesHandler(Request $request)
    {

        //        P=pending [in cart],
        //        TS=tx success [if not cod then show cancel button],
        //        TP=tx in process[if COD show cancel button],
        //        TF=tx failed,
        //        S=shipping [show cancel button],
        //        UC=user cancel request [show dispute button],
        //        UCA=user cancel approved,
        //        MC=merchant cancel,
        //        D=delivered [show dispute button, show refund button],
        //        RR=refund request,
        //        RP=refund in process,
        //        RD=refund done [show dispute button]

        if ($request->isMethod('POST') || $request->isMethod('GET')) {

            $requestData = $request->all();
            if (isset($requestData['tablename']) && !empty($requestData['tablename'])) {
                $tableName = $requestData['tablename'];

                if ($request->isMethod('POST')) {
                    $objCurl = CurlRequestHandler::getInstance();
                    $mytoken = env("API_TOKEN");
                    $url = Session::get("domainname") . env("API_URL") . '/' . "order-ajax-handler";
                    $userId = Session::get('fs_user')['id'];
                    switch ($tableName) {
                        case  "orderstableuser":

                            $dataForCurl = array('api_token' => $mytoken, 'id' => $userId, 'method' => 'orderHistory');
//                            dd($objCurl->curlUsingPost($url, $dataForCurl));
                            $curlResponse = json_decode(json_encode($objCurl->curlUsingPost($url, $dataForCurl), true), true);

                            $allOrders = new Collection();

                            if ($curlResponse['code'] == 200) {
                                foreach ($curlResponse['data'] as $key => $val) {
                                    $orderStatus = $val['order_status'];
                                    $actionsDiv = "";
                                    $viewDiv = "<a class=\"btn btn-sm default btn-editable\" href=\"/order-details/" . $val['order_id'] . "\"><i class=\"fa fa-search\"></i> Details </a>";
                                    if ($orderStatus == "TS") { // TS=tx success [if not cod then show cancel button],
                                        $orderStatus = "Waiting for dispatch";
//                                    } else if ($orderStatus == "P") {
//                                        $orderStatus = "";
                                    } else if ($orderStatus == "TP") { // TP=tx in process[if COD show cancel button],
                                        $orderStatus = "Transaction pending";
                                    } else if ($orderStatus == "TF") { // TF=tx failed,
                                        $orderStatus = "Transaction failed";
                                    } else if ($orderStatus == "S") { // S=shipping [show cancel button],
                                        $orderStatus = "Shipping";
                                    } else if ($orderStatus == "UC") { // UC=user cancel request [show dispute button],
                                        $orderStatus = "Cancel request";
                                    } else if ($orderStatus == "UCA") { // UCA=user cancel approved,
                                        $orderStatus = "Cancelled";
                                    } else if ($orderStatus == "MC") { // MC=merchant cancel,
                                        $orderStatus = "Withheld by seller";
                                    } else if ($orderStatus == "D") { // D=delivered [show dispute button, show refund button],
                                        $orderStatus = "Delivered";
                                    } else if ($orderStatus == "RR") { // RR=refund request,
                                        $orderStatus = "Refund request";
                                    } else if ($orderStatus == "RP") { // RP=refund in process,
                                        $orderStatus = "Refund in process";
                                    } else if ($orderStatus == "RD") { // RD=refund done [show dispute button]
                                        $orderStatus = "Refund complete";
                                    } else {
                                        $orderStatus = "NA";
                                    }
                                    $prodDetails = json_decode($val['product_details'], true);
                                    $prodDiv = '<h3><a href="#">' . $prodDetails['p_name'] . '</a></h3>
                                        <p><strong>Item 1</strong> - Color: Green; Size: S</p>
                                        <em>More info is here</em>';
                                    $imageDiv = '<div class="goods-page-image"><a href="#"><img src="' . $prodDetails['p_image'] . '" alt="Berry Lace Dress"></a></div>';
                                    $allOrders->push([
                                        'order_id' => "<div class=\"goods-page-ref-no\">" . $val['order_id'] . "</div>",
                                        'p_image' => $imageDiv,
                                        'product_details' => $prodDiv,
                                        'order_date' => "<div class=\"goods-page-price\">" . date('d-F-Y', strtotime($val['added_date'])) . "</div>",
                                        'order_amount' => "<div class=\"goods-page-total\">
                                            <strong>" . $val['final_price'] . "</strong></div>",
                                        'status' => "<div class=\"goods-page-quantity\"> <div class=\"product-quantity\"> <input id=\"product-quantity\" type=\"text\" value=\"$orderStatus\" readonly class=\"form-control input-sm\"> </div> </div>",
                                        'action' => $viewDiv,
                                    ]);
                                }
                                return Datatables::of($allOrders)
                                    ->make();
                            } else {
                                return Datatables::of($allOrders)
                                    ->make();
                            }
                            break;


                        case "orderstablebuyer":

                            $dataForCurl = array('api_token' => $mytoken, 'id' => $userId, 'method' => 'orderHistory');
//                            dd($objCurl->curlUsingPost($url, $dataForCurl));
                            $curlResponse = json_decode(json_encode($objCurl->curlUsingPost($url, $dataForCurl), true), true);

                            $allOrders = new Collection();

                            if ($curlResponse['code'] == 200) {
                                foreach ($curlResponse['data'] as $key => $val) {
                                    $orderStatus = $val['order_status'];
                                    $actionsDiv = "";
                                    $viewDiv = "<a class=\"btn btn-sm default btn-editable\" href=\"/order-details/" . $val['order_id'] . "\"><i class=\"fa fa-search\"></i> Details </a>";
                                    if ($orderStatus == "TS") { // TS=tx success [if not cod then show cancel button],
                                        $orderStatus = "Waiting for dispatch";
//                                    } else if ($orderStatus == "P") {
//                                        $orderStatus = "";
                                    } else if ($orderStatus == "TP") { // TP=tx in process[if COD show cancel button],
                                        $orderStatus = "Transaction pending";
                                    } else if ($orderStatus == "TF") { // TF=tx failed,
                                        $orderStatus = "Transaction failed";
                                    } else if ($orderStatus == "S") { // S=shipping [show cancel button],
                                        $orderStatus = "Shipping";
                                    } else if ($orderStatus == "UC") { // UC=user cancel request [show dispute button],
                                        $orderStatus = "Cancel request";
                                    } else if ($orderStatus == "UCA") { // UCA=user cancel approved,
                                        $orderStatus = "Cancelled";
                                    } else if ($orderStatus == "MC") { // MC=merchant cancel,
                                        $orderStatus = "Withheld by seller";
                                    } else if ($orderStatus == "D") { // D=delivered [show dispute button, show refund button],
                                        $orderStatus = "Delivered";
                                    } else if ($orderStatus == "RR") { // RR=refund request,
                                        $orderStatus = "Refund request";
                                    } else if ($orderStatus == "RP") { // RP=refund in process,
                                        $orderStatus = "Refund in process";
                                    } else if ($orderStatus == "RD") { // RD=refund done [show dispute button]
                                        $orderStatus = "Refund complete";
                                    } else {
                                        $orderStatus = "NA";
                                    }
                                    $prodDetails = json_decode($val['product_details'], true);
                                    $prodDiv = '<h3><a href="#">' . $prodDetails['p_name'] . '</a></h3>
                                        <p><strong>Item 1</strong> - Color: Green; Size: S</p>
                                        <em>More info is here</em>';
                                    $imageDiv = '<div class="goods-page-image"><a href="#"><img src="' . $prodDetails['p_image'] . '" alt="Berry Lace Dress"></a></div>';
                                    $allOrders->push([
                                        'order_id' => "<div class=\"goods-page-ref-no\">" . $val['order_id'] . "</div>",
                                        'p_image' => $imageDiv,
                                        'product_details' => $prodDiv,
                                        'order_date' => "<div class=\"goods-page-price\">" . date('d-F-Y', strtotime($val['added_date'])) . "</div>",
                                        'order_amount' => "<div class=\"goods-page-total\">
                                            <strong>" . $val['final_price'] . "</strong></div>",
                                        'status' => "<div class=\"goods-page-quantity\"> <div class=\"product-quantity\"> <input id=\"product-quantity\" type=\"text\" value=\"$orderStatus\" readonly class=\"form-control input-sm\"> </div> </div>",
                                        'action' => $viewDiv,
                                    ]);
                                }
                                return Datatables::of($allOrders)
                                    ->make();
                            } else {
                                return Datatables::of($allOrders)
                                    ->make();
                            }
                            break;

                        default:
                            return Datatables::of(new Collection())
                                ->make();
                            break;
                    }
                } else {
                    dd("Invalid table request");
                }
            } else {
                dd("No such dataTable exists");
            }
        } else {
            dd("Invalid request");
        }
    }


    public function orderDetails(Request $request, $orderId)
    {
        if ($orderId) {
            $objCurl = CurlRequestHandler::getInstance();
            $url = Session::get("domainname") . env("API_URL") . '/' . "order-details";
            $mytoken = env("API_TOKEN");
            $userId = isset(Session::get('fs_user')['id']) ? Session::get('fs_user')['id'] : Session::get('fs_admin')['id'];//TODO write everywhere to check for admin session or write in middleware to set fs_user also when admin login
            $data = array('api_token' => $mytoken, 'id' => $userId, 'orderId' => $orderId);
            $curlResponse = json_decode(json_encode($objCurl->curlUsingPost($url, $data)), true);
//            dd($curlResponse);
        } else {
//            "Invalid request";
        }
        return view("Order/Views/order/orderDetails", ['orderDetails' => $curlResponse]);
    }

    public function buyerOrderHistory(Request $request)
    {
        return view("Order/Views/order/buyerOrderHistory");
    }

    public function buyerOrderDetails(Request $request, $orderId)
    {
        if ($orderId) {
            $objCurl = CurlRequestHandler::getInstance();
            $url = Session::get("domainname") . env("API_URL") . '/' . "order-details";
            $mytoken = env("API_TOKEN");
            $userId = isset(Session::get('fs_buyer')['id']) ? Session::get('fs_buyer')['id'] : '';
            $data = array('api_token' => $mytoken, 'id' => $userId, 'orderId' => $orderId);
            $curlResponse = json_decode(json_encode($objCurl->curlUsingPost($url, $data)), true);
//            dd($curlResponse);
        } else {
//            "Invalid request";
        }
        return view("Order/Views/order/buyerOrderDetails", ['orderDetails' => $curlResponse]);
    }

}

