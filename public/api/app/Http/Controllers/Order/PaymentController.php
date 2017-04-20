<?php
namespace FlashSaleApi\Http\Controllers\Order;

use FlashSaleApi\Http\Models\Orders;
use FlashSaleApi\Http\Models\ProductOptionVariants;
//use FlashSaleApi\Http\Models\Transactions;
use FlashSaleApi\Http\Models\Transactions;
use Illuminate\Http\Request;
use FlashSaleApi\Http\Requests;
use FlashSaleApi\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use PDO;
use FlashSaleApi\Http\Models\Products;
use FlashSaleApi\Http\Models\User;
use stdClass;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;

use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\Service\PayPalAPIInterfaceServiceService;


class PaymentController extends Controller
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
     * Checkout detail for users added from cart
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function paymentHandler(Request $request)
    {

        $method = $request->input('method');
        $response = new stdClass();
        $objProductModel = Products::getInstance();
        $objOptionVariant = ProductOptionVariants::getInstance();
        $objOrderModel = Orders::getInstance();
        if ($method != "") {
            switch ($method) {
                case 'checkOutDetails':

                    $postData = $request->all();
                    $response = new stdClass();
                    $objUserModel = new User();
                    if ($postData) {
                        $userId = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
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
                        if ($authflag) {//LOGIN TOKEN
                            $objOrderModel = Orders::getInstance();
                            $whereUserId = ['rawQuery' => 'orders.for_user_id = ? AND
                            (product_option_variants_combination.variant_ids LIKE CONCAT("%",REPLACE(SUBSTRING_INDEX(orders.product_id,"-",-1),",","_"),"%")
                             or product_option_variants_combination.variant_ids LIKE CONCAT("%",REVERSE(REPLACE(SUBSTRING_INDEX(orders.product_id,"-",-1),",","_")),"%"))',
                                'bindParams' => [$userId]];
                            $selectColumn = [
                                DB::raw("SUBSTRING_INDEX(orders.product_id,'-',1) as pid ,REPLACE(SUBSTRING_INDEX(orders.product_id,'-',-1),',','_') cid "),
                                'product_images.*',
                                'products.product_name',
                                'products.price_total',
                                'products.in_stock',
                                'orders.quantity',
                                'orders.order_id',
                                'product_option_variant_relation.*',
                                'productmeta.quantity_discount',
                                'usersmeta.*',
                                'location.name as ln_country', 'ls.name as ls_state', 'lc.name as lc_city ',
                                'users.email', 'users.name', 'users.last_name', 'users.username', 'users.role',
                                DB::raw('GROUP_CONCAT(product_option_variant_relation.variant_data SEPARATOR "____") AS variant_datas')

                            ];
                            $cartProductDetails = json_decode($objOrderModel->getCartProductDetails($whereUserId, $selectColumn), true);
//                            print_a($cartProductDetails);
                            $combineVarian = [];
                            $finalCartProductDetails = [];
                            $subTotal = '';
                            $totalShipingPrice = '';
                            $finalCheckoutPrice = '';
                            if ($cartProductDetails['code'] == 200) {
                                foreach (json_decode(json_encode($cartProductDetails['data']), false) as $cartkey => $cartVal) {
                                    if ($cartVal->in_stock >= $cartVal->quantity) {
                                        $variantData = explode("____", $cartVal->variant_datas);
                                        $varian = array_flatten(array_map(function ($v) {
                                            return json_decode($v);
                                        }, $variantData));

                                        $finalPrice = $cartVal->price_total;
                                        $combineVarian[] = array_values(array_filter(array_map(function ($v) use ($varian, &$finalPrice) {
                                            return current(array_filter(array_map(function ($value) use ($v, &$finalPrice) {
                                                if ($v == $value->VID) {
                                                    $finalPrice = $finalPrice + $value->PM;
                                                    return [$v => $value->PM];
                                                }
                                            }, $varian)));
                                        }, explode("_", $cartVal->cid))));
                                        $cartVal->finalPrice = $finalPrice * $cartVal->quantity;

                                        $discountedValue = 0;
                                        $qtyValue = null;
                                        if ($cartVal->quantity_discount != '') {
                                            $quantityDiscount = object_to_array(json_decode($cartVal->quantity_discount));
                                            $quantities = array_column($quantityDiscount, 'quantity');

                                            $quantity = $cartVal->quantity;
                                            $tmpQTY = array_filter(array_map(function ($v) use ($quantity) {
                                                if ($quantity >= $v) return $v;
                                            }, $quantities));
                                            sort($tmpQTY);
                                            $finalQTY = end($tmpQTY);

                                            $qtyValue = current(array_values(array_filter(array_map(function ($v) use ($finalQTY) {
                                                if ($v['quantity'] == $finalQTY) return $v;
                                            }, $quantityDiscount))));

                                            $discountedValue = ($qtyValue['type'] == 1) ? $qtyValue['value'] : ($cartVal->finalPrice * $qtyValue['value'] / 100);
                                            $discountedPrice = $cartVal->finalPrice - $discountedValue;


                                            if ($discountedPrice <= 0) {
                                                $error[] = 'Discount not allowed';
                                            }
                                            $cartVal->discountedPrice = $discountedPrice;
                                        }
                                        $cartVal->shipingPrice = 9; // TODO : NEED TO GET CONFIRMATION FOR SHIPPING DETAILS//

                                        $finalCartProductDetails[] = $cartVal;
                                        $subTotal = $subTotal + (isset($cartVal->discountedPrice) ? $cartVal->discountedPrice : $cartVal->finalPrice);

                                        $totalShipingPrice = $totalShipingPrice + $cartVal->shipingPrice;

                                        $finalCheckoutPrice = $subTotal + $totalShipingPrice;

                                    } else {
                                        $error[] = 'Selected quantity should be greater than In-Stock';
                                    }
                                }
                                if ($subTotal != '' && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->subtotal = $subTotal;
                                }
                                if ($totalShipingPrice != '' && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->totalShippingPrice = $totalShipingPrice;
                                }
                                if ($finalCheckoutPrice != '' && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->finalCheckoutPrice = $finalCheckoutPrice;
                                }
                            }
                            if (isset($finalCartProductDetails)) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $finalCartProductDetails;
                            } else {
                                $response->code = 400;
                                $response->message = "No Details found.";
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

            }

        }


    }

    /**
     * @param Request $request
     * @response json $returnObj returns payment parameters for further processing
     * @desc This function is used for validation of order details and for setting parameters for initializing payment through gateway
     * @author [modified] payment [paypal/rp/gc] part by Akash M. Pai
     */
    public function processCheckout(Request $request)
    {

        $returnObj = array('data' => null, 'message' => "Method not allowed.");
        $dataToReturn = null;
        $returnCode = 405;
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $requestData = $request->all();

            $objModelUsers = new User();
            if ($requestData) {
                $userId = '';
                if (isset($requestData['id'])) {
                    $userId = $requestData['id'];
                }
                $mytoken = '';
                $authflag = false;
                $resultUserDetails = '';
                $whereForUpdateUser = array();
                if (isset($requestData['api_token'])) {
                    $mytoken = $requestData['api_token'];
                    if ($userId != '') {
                        $whereForloginToken = $whereForUpdateUser = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId]
                        ];
                        $resultUserDetails = $objModelUsers->getUsercredsWhere($whereForloginToken);
                        if ($mytoken == env("API_TOKEN")) {
                            $authflag = true;
                        } else {
                            if ($mytoken == $resultUserDetails->login_token) {
                                $authflag = true;
                            }
                        }
                    }
                }
                if ($authflag) { //LOGIN TOKEN

                    $rules = [
                        'id' => 'required',
                        's_addressline1' => 'required', 's_city' => 'required', 's_country' => 'required', 's_zip' => 'required', 's_phone' => 'required',
                        'b_addressline1' => 'required', 'b_city' => 'required', 'b_country' => 'required', 'b_zip' => 'required', 'b_phone' => 'required'
                    ];

                    $messages = [
                        'id.required' => 'Please select a main image for the product.',
                        's_addressline1.required' => 'Please enter an address', 's_city.required' => 'Please enter a city name', 's_country.required' => 'Please enter a country', 's_zip.required' => 'Please enter zipcode', 's_phone.required' => 'Please enter a phone number',
                        'b_addressline1.required' => 'Please enter an address', 'b_city.required' => 'Please enter a city name', 'b_country.required' => 'Please enter a country', 'b_zip.required' => 'Please enter zipcode', 'b_phone.required' => 'Please enter a phone number'
                    ];

                    $validator = Validator::make($requestData, $rules, $messages);
                    $error = [];
                    if ($validator->fails()) {
                        $returnObj = [
                            'data' => $validator->errors()->all(),
                            'message' => 'Please correct the following errors.',
                        ];
                        $returnCode = 400;
                    } else {
                        $currencyCode = "USD";//todo get default currency of user instead and also check if the currency is allowed by the specified payment method
                        $paymentMethod = json_decode($requestData['paymentMethod'], true);
//                        dd($paymentMethod);
                        $paymentMode = $requestData['paymentMode'];
                        //SAME CODE as paymentHandler@checkOutDetails AGAIN HERE START
                        $objOrderModel = Orders::getInstance();
                        $whereUserId = [
                            'rawQuery' => 'orders.for_user_id = ? AND (product_option_variants_combination.variant_ids LIKE CONCAT("%",REPLACE(SUBSTRING_INDEX(orders.product_id,"-",-1),",","_"),"%") or product_option_variants_combination.variant_ids LIKE CONCAT("%",REVERSE(REPLACE(SUBSTRING_INDEX(orders.product_id,"-",-1),",","_")),"%")) and orders.order_type = "P"',
                            'bindParams' => [$userId]
                        ];
                        $selectColumn = [
                            DB::raw("SUBSTRING_INDEX(orders.product_id,'-',1) as pid ,REPLACE(SUBSTRING_INDEX(orders.product_id,'-',-1),',','_') cid "),
                            'product_images.*', 'products.product_name', 'products.price_total', 'products.in_stock',
                            'orders.quantity', 'orders.order_id',
                            'productmeta.quantity_discount',
                            'usersmeta.*',
                            'location.name as ln_country', 'ls.name as ls_state', 'lc.name as lc_city ',
                            'users.email', 'users.name', 'users.last_name', 'users.username', 'users.role',
                            'product_option_variant_relation.*', DB::raw('GROUP_CONCAT(product_option_variant_relation.variant_data SEPARATOR "____") AS variant_datas')
                        ];
                        $cartProductDetails = json_decode($objOrderModel->getCartProductDetails($whereUserId, $selectColumn), true);
                        if ($cartProductDetails['code'] == 200) {
                            $combineVarian = [];
                            $finalCartProductDetails = [];
                            $subTotal = 0;
                            $totalShipingPrice = 0;
                            $finalCheckoutPrice = 0;
                            $txOrderIds = array();
                            $txProductDetails = array();
                            $dbtxErrorFlag = false;
                            DB::beginTransaction();
//                            dd($cartProductDetails);
                            foreach (json_decode(json_encode($cartProductDetails['data']), false) as $cartkey => $cartVal) {
                                if ($cartVal->in_stock >= $cartVal->quantity) {
                                    $variantData = explode("____", $cartVal->variant_datas);
                                    $varian = array_flatten(array_map(function ($v) {
                                        return json_decode($v);
                                    }, $variantData));

                                    $finalPrice = $cartVal->price_total;
//                                    echo $cartkey . "----" . $finalPrice;
                                    $combineVarian[] = array_values(array_filter(array_map(function ($v) use ($varian, &$finalPrice) {
                                        return current(array_filter(array_map(function ($value) use ($v, &$finalPrice) {
                                            if ($v == $value->VID) {
                                                $finalPrice = $finalPrice + $value->PM;
//                                                echo "----" . $value->PM;
                                                return [$v => $value->PM];
                                            }
                                        }, $varian)));
                                    }, explode("_", $cartVal->cid))));
//                                    echo "<br>";
//                                    echo $cartkey . "----" . $finalPrice . "<br>";
                                    $cartVal->finalUnitPrice = $finalPrice;
                                    $cartVal->finalPrice = round($finalPrice * $cartVal->quantity, 2);
//                                    echo $cartkey . "----" . $cartVal->finalPrice . "<br>";
                                    $discountedValue = 0;
                                    $qtyValue = null;
                                    $discountedPrice = 0;
                                    if ($cartVal->quantity_discount != '') {//todo to decide if this for buyer only
                                        $quantityDiscount = object_to_array(json_decode($cartVal->quantity_discount));
                                        $quantities = array_column($quantityDiscount, 'quantity');

                                        $quantity = $cartVal->quantity;
                                        $tmpQTY = array_filter(array_map(function ($v) use ($quantity) {
                                            if ($quantity >= $v) return $v;
                                        }, $quantities));
                                        sort($tmpQTY);
                                        $finalQTY = end($tmpQTY);

                                        $qtyValue = current(array_values(array_filter(array_map(function ($v) use ($finalQTY) {
                                            if ($v['quantity'] == $finalQTY) return $v;
                                        }, $quantityDiscount))));

                                        $discountedValue = ($qtyValue['type'] == 1) ? $qtyValue['value'] : round(round(($cartVal->finalPrice * $qtyValue['value']), 2) / 100, 2);
//                                        echo "dis----" . $qtyValue['value'] . "------" . $discountedValue . "<br>";
                                        $discountedPrice = $cartVal->finalPrice - $discountedValue;


                                        if ($discountedPrice <= 0) {
                                            $error[] = 'Discount not allowed';
                                        }
                                        $cartVal->discountAmount = $cartVal->finalPrice - $discountedPrice;
                                        $cartVal->discountedPrice = $discountedPrice;
                                    }
                                    $cartVal->shipingPrice = 9.00; // TODO : NEED TO GET CONFIRMATION FOR SHIPPING DETAILS//
                                    $txOrderIds[] = $cartVal->order_id;//for transaction data
                                    $orderPDetails = ['pid' => $cartVal->pid, 'p_name' => $cartVal->product_name, 'cid' => $cartVal->for_combination_id, 'cid_val' => $cartVal->cid, 'p_image' => $cartVal->image_url];
                                    $txProductDetails[] = $orderPDetails;
                                    $finalCartProductDetails[] = $cartVal;
                                    $subTotal = $subTotal + (isset($cartVal->discountedPrice) ? $cartVal->discountedPrice : $cartVal->finalPrice);

                                    $totalShipingPrice = $totalShipingPrice + $cartVal->shipingPrice;

                                    $finalCheckoutPrice = $subTotal + $totalShipingPrice;

                                    $dataForUpdateOrder = ['order_status' => 'TP', 'product_details' => json_encode($orderPDetails, true), 'unit_price' => $cartVal->finalUnitPrice, 'quantity' => $cartVal->quantity, 'discount_by' => 2, 'discount_type' => $qtyValue['type'] == 1 ? 'F' : 'P', 'discount_value' => $discountedValue, 'final_price' => $discountedPrice > 0 ? $discountedPrice : $cartVal->finalPrice];
                                    $whereForUpdateOrder = ['rawQuery' => 'order_id = ?', 'bindParams' => [$cartVal->order_id]];
                                    $resultOrderUpdated = json_decode($objOrderModel->updateOrderWhere($dataForUpdateOrder, $whereForUpdateOrder), true);
                                    //todo update product quantity here in product table or in combination table
                                    //todo user locking concept here
                                    if ($resultOrderUpdated['code'] != 200 && $resultOrderUpdated['code'] != 100) {
                                        $dbtxErrorFlag = true;
                                    }
                                } else {
                                    $dbtxErrorFlag = true;
                                    $error[] = 'Selected quantity should be greater than In-Stock';
                                }
                            }
//                            dd($finalCartProductDetails);
//                            echo $finalCheckoutPrice . "<br>";
                            if ($dbtxErrorFlag) {
                                DB::rollBack();
                                $returnObj = [
                                    'data' => null,
                                    'message' => $error,
                                    'code' => 401
                                ];
                                $returnCode = 401;
                            } else {
                                $walletbalUsed = 0;
                                $rewardptsUsed = 0;
                                $dataForUpdateUser = array();
                                if (is_int(array_search('wallet', $paymentMethod))) {
                                    if (!$requestData['allWalletAmtFlag']) {
                                        if (($requestData['walletAmount'] > 0) && ($resultUserDetails->wallet_balance >= $requestData['walletAmount']) && ($requestData['walletAmount'] < $finalCheckoutPrice)) {//TODO low:diff change validation position to optimize response speed
                                            $dataForUpdateUser = ['wallet_balance' => $resultUserDetails->wallet_balance - $requestData['walletAmount']];
                                            $finalCheckoutPrice = $finalCheckoutPrice - $requestData['walletAmount'];
                                            $walletData = new stdClass();
                                            $walletData->finalUnitPrice = -$requestData['walletAmount'];
                                            $walletData->quantity = 1;
                                            $walletData->product_name = "User wallet";
                                            $finalCartProductDetails[] = $walletData;
                                            $walletbalUsed = $requestData['walletAmount'];
                                        } else {
                                            DB::rollBack();
                                            $dbtxErrorFlag = true;
                                            $returnObj = [
                                                'data' => null,
                                                'message' => "Wallet amount not enough",
                                                'code' => 401
                                            ];
                                            $returnCode = 401;
                                            goto endOfFunction;
                                        }
                                    } else {
                                        if ($resultUserDetails->wallet_balance >= $finalCheckoutPrice) {
                                            $remainingWalletBal = $resultUserDetails->wallet_balance - $finalCheckoutPrice >= 0 ? $resultUserDetails->wallet_balance - $finalCheckoutPrice : 0;
                                            $dataForUpdateUser = ['wallet_balance' => $remainingWalletBal];
                                            $finalCheckoutPrice = $finalCheckoutPrice - $resultUserDetails->wallet_balance;
                                            $walletData = new stdClass();
                                            $walletData->finalUnitPrice = $resultUserDetails->wallet_balance - $finalCheckoutPrice >= 0 ? -$finalCheckoutPrice : -$resultUserDetails->wallet_balance;
                                            $walletData->quantity = 1;
                                            $walletData->product_name = "User wallet";
                                            $finalCartProductDetails[] = $walletData;
                                            $walletbalUsed = $resultUserDetails->wallet_balance;
                                        } else {
                                            DB::rollBack();
                                            $dbtxErrorFlag = true;
                                            $returnObj = [
                                                'data' => null,
                                                'message' => "Wallet amount not enough",
                                                'code' => 401
                                            ];
                                            $returnCode = 401;
                                            goto endOfFunction;
                                        }
                                    }
                                }
//                                echo "wallet-------" . $walletbalUsed . "<br>";
                                if (is_int(array_search('rp', $paymentMethod))) {
                                    //TODO high:diff
                                    //insert to rewards table too. Update total rewards in users table?
                                }
                                if (!empty($dataForUpdateUser)) {
                                    $updatedUserDetails = json_decode($objModelUsers->updateUsersWhere($dataForUpdateUser, $whereForUpdateUser), true);
                                    if ($updatedUserDetails['code'] != 200) {
                                        DB::rollBack();
                                        $dbtxErrorFlag = true;
                                        $returnObj = [
                                            'data' => null,
                                            'message' => $updatedUserDetails['message'],
                                            'code' => 401
                                        ];
                                        $returnCode = 401;
                                        goto endOfFunction;
                                    }
                                }

                                if ($subTotal != 0 && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->subtotal = $subTotal;
                                }
                                if ($totalShipingPrice != 0 && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->totalShippingPrice = $totalShipingPrice;
                                }
                                if ($finalCheckoutPrice != 0 && !empty($finalCartProductDetails)) {
                                    $finalCartProductDetails[0]->finalCheckoutPrice = $finalCheckoutPrice;
                                }
                                $txUserDetails = ["firstname" => $resultUserDetails->name, "lastname" => $resultUserDetails->last_name];
                                $txShippingAddr = ['addrline1' => $requestData['s_addressline1'], 'addrline2' => $requestData['s_addressline2'], 'city' => $requestData['s_city'], 'state' => $requestData['s_state'], 'country' => $requestData['s_country'], 'zip' => $requestData['s_zip'], 'phone' => $requestData['s_phone']];
                                $txBillingAddr = ['addrline1' => $requestData['b_addressline1'], 'addrline2' => $requestData['b_addressline2'], 'city' => $requestData['b_city'], 'state' => $requestData['b_state'], 'country' => $requestData['b_country'], 'zip' => $requestData['b_zip'], 'phone' => $requestData['b_phone']];

                                $dataForTransaction = array('tx_pmethod_id' => '1',//TODO [hardcoded] instead get id from payment_method table
                                    'tx_type' => 'P',
                                    'tx_unique_code' => time() . "-" . $userId . "-P-" . implode("-", $txOrderIds),
//                            'payment_mode' => '',//updated in paypal response
                                    'payment_details' => json_encode($txProductDetails, true),
                                    'user_details' => json_encode($txUserDetails, true),
                                    'shipping_addr' => json_encode($txShippingAddr, true),
                                    'billing_addr' => json_encode($txBillingAddr, true),
                                    //TODO below 3 lines to be updated ONLY if discount provided by paypal or payu etc coupon-codes/gift-certificates
                                    'discount_by' => 0,
                                    'discount_type' => null,
                                    'discount_value' => 0,
                                    'tx_amount' => $subTotal,
                                    'walletbal_used' => $walletbalUsed,
                                    'rewardpts_used' => $rewardptsUsed,
                                    'tx_date' => time(),
                                    'tx_status' => 'P',
                                );
                                $objModelTransactions = Transactions::getInstance();
                                $insertedTxResponse = json_decode($objModelTransactions->addTransaction($dataForTransaction), true);

                                if ($insertedTxResponse['code'] == 200) {
                                    if ((is_int(array_search('wallet', $paymentMethod)) && $finalCheckoutPrice > 0) || !is_int(array_search('wallet', $paymentMethod))) {

                                        switch ($paymentMode) {
                                            case "web":
                                                //FOR SITE HERE
                                                if (is_int(array_search('paypal', $paymentMethod))) {
                                                    $config = array(
                                                        // Signature Credential
                                                        "acct1.UserName" => env('PAYPAL_SANDBOX_USERNAME'),//"akashpai-facilitator_api1.globussoft.com",
                                                        "acct1.Password" => env('PAYPAL_SANDBOX_PASSWORD'),//"EF6QNKTYRETQBMYE",
                                                        "acct1.Signature" => env('PAYPAL_SANDBOX_SIGNATURE'),// "Aaa2LqZCnxPUve1-f0FrCKM5YDMyA-ufQ1ROfJle147cxpNUX4GnG7ws",
                                                        "mode" => env('PAYPAL_MODE')
                                                    );

                                                    $url = "http://" . env('WEB_URL');//todo check if http is required on server
                                                    $returnUrl = "$url/paypal-success";//TODO change success response url
                                                    $cancelUrl = "$url/paypal-cancel";//TODO change cancel response url
                                                    $notifyUrl = "$url/paypal-ipn";//TODO change notify response url

                                                    if (env('PAYPAL_MODE') == "live") {//TODO CHANGE LIVE SETTINGS HERE
                                                        $config = array(
                                                            // Signature Credential
                                                            "acct1.UserName" => env('PAYPAL_USERNAME'),//"akashpai-facilitator_api1.globussoft.com",
                                                            "acct1.Password" => env('PAYPAL_PASSWORD'),//"EF6QNKTYRETQBMYE",
                                                            "acct1.Signature" => env('PAYPAL_SIGNATURE'),// "Aaa2LqZCnxPUve1-f0FrCKM5YDMyA-ufQ1ROfJle147cxpNUX4GnG7ws",
                                                            "mode" => env('PAYPAL_MODE')
                                                        );
                                                    }

                                                    $address = new AddressType();
                                                    $address->CityName = $finalCartProductDetails[0]->lc_city;
                                                    $address->Name = $finalCartProductDetails[0]->name;
                                                    $address->Street1 = $finalCartProductDetails[0]->addressline1;
                                                    $address->StateOrProvince = $finalCartProductDetails[0]->ls_state;
                                                    $address->PostalCode = $finalCartProductDetails[0]->zipcode;
                                                    $address->Country = $finalCartProductDetails[0]->ln_country;
                                                    $address->Phone = $finalCartProductDetails[0]->phone;//TODO phone country code
                                                    $shippingTotal = new BasicAmountType($currencyCode, round($finalCartProductDetails[0]->totalShippingPrice, 2));
//                                                    dd($shippingTotal);
                                                    $paymentDetails = new PaymentDetailsType();
                                                    $itemTotalValue = 0;
                                                    $taxTotalValue = 0;
                                                    $productArrayCount = 0;
//                                                    echo "paypal cal -------- <br>";
                                                    foreach ($finalCartProductDetails as $fCPDKey => $fCPDVal) {
                                                        $itemAmount = new BasicAmountType($currencyCode, round($fCPDVal->finalUnitPrice, 2));//UNIT PRICE
                                                        $itemTotalValue += round($fCPDVal->finalUnitPrice * $fCPDVal->quantity, 2);
//                                                $taxTotalValue += 3.00 * $fCPDVal['quantity'];
                                                        $itemDetails = new PaymentDetailsItemType();
                                                        $itemDetails->Name = $fCPDVal->product_name;
                                                        $itemDetails->Amount = $itemAmount;
                                                        $itemDetails->Quantity = $fCPDVal->quantity;
                                                        $itemDetails->ItemCategory = 'Physical';
//                                                $itemDetails->Tax = new BasicAmountType($currencyCode, 3.00);//$_REQUEST['itemSalesTax'][$i]
//                                                        echo "price-----" . $fCPDVal->finalUnitPrice . "----quantity-----" . $fCPDVal->quantity . "----total-----" . $fCPDVal->finalUnitPrice * $fCPDVal->quantity . "<br>";
                                                        $paymentDetails->PaymentDetailsItem[$productArrayCount] = $itemDetails;
                                                        $productArrayCount++;

                                                        //IF DISCOUNT IS TO BE PROVIDED
                                                        if (isset($fCPDVal->discountAmount) && !empty($fCPDVal->discountAmount)) {
                                                            $itemAmount = new BasicAmountType($currencyCode, round(-$fCPDVal->discountAmount, 2));
                                                            $itemTotalValue += round(-$fCPDVal->discountAmount, 2);
//            $taxTotalValue += 3.00 * 3;//$_REQUEST['itemSalesTax'][$i] * $_REQUEST['itemQuantity'][$i];
                                                            $itemDetails = new PaymentDetailsItemType();
                                                            $itemDetails->Name = "$fCPDVal->discountAmount $currencyCode Discount";//$_REQUEST['itemName'][$i];
                                                            $itemDetails->Amount = $itemAmount;
//            $itemDetails->ItemTotal = $itemAmount;
                                                            $itemDetails->Quantity = 1;//$_REQUEST['itemQuantity'][$i];
//            $itemDetails->ItemCategory = 'Physical';
//            $itemDetails->Tax = new BasicAmountType($currencyCode, 3.00);//$_REQUEST['itemSalesTax'][$i]
                                                            $paymentDetails->PaymentDetailsItem[$productArrayCount] = $itemDetails;
                                                            $productArrayCount++;
                                                        }

                                                    }

                                                    $orderTotalValue = $itemTotalValue + $shippingTotal->value;// + 0.01;//+ $taxTotalValue;// + + $handlingTotal->value + $insuranceTotal->value
//                                                    echo $orderTotalValue;
//                                                    echo "<br>";
                                                    $paymentDetails->ShipToAddress = $address;
                                                    $paymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
//                                        $paymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
                                                    $paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);
                                                    $paymentDetails->ShippingTotal = $shippingTotal;
//                                                    dd($shippingTotal);

                                                    $paymentDetails->PaymentAction = "Sale";//$_REQUEST['paymentType'];
//                                                    dd($paymentDetails);
                                                    $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
                                                    $setECReqDetails->PaymentDetails[0] = $paymentDetails;
                                                    $setECReqDetails->CancelURL = $cancelUrl;
                                                    $setECReqDetails->ReturnURL = $returnUrl;
                                                    $setECReqDetails->NoShipping = 1; //$_REQUEST['noShipping'];
                                                    $setECReqDetails->AddressOverride = 0; //$_REQUEST['addressOverride'];
                                                    $setECReqDetails->ReqConfirmShipping = 0; //$_REQUEST['reqConfirmShipping'];
//                                                    dd($setECReqDetails);
                                                    $setECReqType = new SetExpressCheckoutRequestType();
                                                    $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
                                                    $setECReq = new SetExpressCheckoutReq();
                                                    $setECReq->SetExpressCheckoutRequest = $setECReqType;

                                                    $paypalService = new PayPalAPIInterfaceServiceService($config);

                                                    $setECResponse = array();

                                                    try {
                                                        /* wrap API method calls on the service object with a try catch */
                                                        $setECResponse = $paypalService->SetExpressCheckout($setECReq);
                                                    } catch (Exception $ex) {
                                                        $ppMessage['message'] = "";
                                                        $ppMessage['detailed_message'] = "";
                                                        $ppMessage['error_type'] = "Unknown";
                                                        if (isset($ex)) {
                                                            $ppMessage['message'] = $ex->getMessage();
                                                            $ppMessage['error_type'] = get_class($ex);

                                                            if ($ex instanceof PPConnectionException) {
                                                                $ppMessage['detailed_message'] = "Error connecting to " . $ex->getUrl();
                                                            } else if ($ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException) {
                                                                $ppMessage['detailed_message'] = $ex->errorMessage();
                                                            } else if ($ex instanceof PPConfigurationException) {
                                                                $ppMessage['detailed_message'] = "Invalid configuration. Please check your configuration file";
                                                            }
                                                        }
                                                        DB::rollBack();
                                                        $returnObj = [
                                                            'data' => $ppMessage,
                                                            'message' => 'Paypal error.',
                                                            'code' => 400
                                                        ];
                                                        $returnCode = 400;
                                                    }
//                                                    dd($setECResponse);
                                                    if (isset($setECResponse) && !empty($setECResponse)) {

                                                        if ($setECResponse->Ack == 'Success') {
                                                            DB::commit();
                                                            $token = $setECResponse->Token;
                                                            $payPalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token;//todo paypal url
                                                            $returnObj = [
                                                                'data' => ['message' => 'Order placed successfully. Paypal status: Click <a href="' . $payPalURL . '"> here to complete payment.', 'link' => $payPalURL, 'token' => $token],
                                                                'message' => 'Paypal setup ' . $setECResponse->Ack . '. Redirect to [data->link] to complete the payment and get response.',
                                                                'code' => 200
                                                            ];
                                                            $returnCode = 200;
                                                        } else {
                                                            DB::rollBack();
                                                            $returnObj = [
                                                                'data' => ['message' => $setECResponse->Ack],
                                                                'message' => 'Paypal error.',
                                                                'code' => 400
                                                            ];
                                                            $returnCode = 400;
                                                        }
                                                    }
                                                }
                                                break;

                                            case "mobile":
                                                //TODO Need to check with mobile team wether they use webview or paypal API and write service based on that
                                                break;

                                            default:
                                                $dataToReturn = $finalCartProductDetails;
                                                break;
                                        }

                                    } else {
                                        DB::commit();
                                        $returnObj = [
                                            'data' => ['message' => "Order placed successfully. $currencyCode $walletbalUsed from your wallet. Wallet balance remaining: ", 'link' => "Click <a href=\"/order-details/" . $insertedTxResponse['data'] . "> here </a>to view your order details."],//todo working here
                                            'message' => 'Ordered placed successfully.',
                                            'code' => 200
                                        ];
                                        $returnCode = 200;
                                    }
                                } else {
                                    DB::rollBack();
                                    $returnObj = [
                                        'data' => $finalCartProductDetails,
                                        'message' => 'Transaction not inserted.',
                                        'code' => 400
                                    ];
                                    $returnCode = 400;
                                }
                                //SAME CODE as paymentHandler@checkOutDetails AGAIN HERE END

                            }

//                            rollbackBlock : {//todo low:diff use goto instead of if else, to optimize code readability
//                                if ($dbtxErrorFlag)
//                                    DB::rollBack();
//                            }
                            endOfFunction: {

                            }
                        }
                    }
                } else {
                    $returnObj = [
                        'data' => null,
                        'message' => 'Access Denied.',
                        'code' => 401
                    ];
                    $returnCode = 401;
                }
            } else {
                $returnObj = [
                    'data' => null,
                    'message' => 'Invalid request.',
                    'code' => 401
                ];
                $returnCode = 401;
            }

        }
//        return response($returnObj, $returnCode);
        echo json_encode($returnObj, true);
        die;
    }


}

