<?php


namespace FlashSale\Http\Modules\Payment\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Redirect;
use PayPal\Types\AP\InvoiceData;
use PayPal\Types\AP\InvoiceItem;
use PayPal\Types\AP\PaymentDetailsRequest;
use PayPal\Types\AP\ReceiverIdentifier;
use PayPal\Types\AP\ReceiverOptions;
use PayPal\Types\AP\SenderOptions;
use PayPal\Types\AP\SetPaymentOptionsRequest;
use PayPal\Types\Common\RequestEnvelope;
use PDO;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Curl\CurlRequestHandler;
use PayPal\Core\PPHttpConfig;
use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;

//PAYPAL UES START
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

//PAYPAL USE END

//PAYPAL RESPONSE USE START
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;

//PAYPAL RESPONSE USE END

//DB start
use FlashSale\Http\Modules\Payment\Models\Transactions;

//DB end

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
    }

    /**
     * For Passing Payment Paramters To Paypal //NOT REQUIRED
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author Vini Dubey<vinidubey@globussoft.in>
     * @since 16-06-2016
     */
    public function payPayment(Request $request)
    {
        $objCurl = CurlRequestHandler::getInstance();
//        $postData = $request->all();
//        if (array_key_exists('productId', $postData)) {
//            $productId = $postData['productId'];
//        }
//        if (array_key_exists('variantId', $postData)) {
//            $selectedVariantId = str_replace(',', '_', $postData['variantId']);
//
//        }
//        if (array_key_exists('quantityId', $postData)) {
//            $quantityId = $postData['quantityId'];
//        }
//
//        $url = Session::get("domainname") . env("API_URL") . '/' . "order-ajax-handler";
//        $mytoken = env("API_TOKEN");
//        $user_id = '';
//        if (Session::has('fs_user')) {
//            $user_id = Session::get('fs_user')['id'];
//        }
//        $data = array('api_token' => $mytoken, 'id' => $user_id, 'productId' => $productId, 'selectedVariantId' => $selectedVariantId, 'quantityId' => $quantityId, 'method' => 'payment-product-detail');
//        $curlResponse = $objCurl->curlUsingPost($url, $data);
//
//        print_a($curlResponse);
        $postData = $request->all();
        $url = Session::get("domainname") . env("API_URL") . '/' . "payment-handler";
        $mytoken = env("API_TOKEN");
        $user_id = '';
        if (Session::has('fs_user')) {
            $user_id = Session::get('fs_user')['id'];
        }
        $data = array('api_token' => $mytoken, 'id' => $user_id, 'method' => 'checkOutDetails');
        $curlResponse = $objCurl->curlUsingPost($url, $data);
//        print_a($curlResponse);
        if ($curlResponse->code == 200) {
            $finalData = $curlResponse->data;
            $receiver = array();
            $invoiceData = array();
            $receiverOptions = array();
            $invoiceItem = new InvoiceItem();
            $invoiceItems = array();
            $payRequest = new PayRequest();      // REQUEST FOR PAY
//            $receiver[0] = new Receiver(); // OBJECT FOR RECIEVER
//            $receiver[0]->amount = number_format((float)$finalData[0]['finalPrice'], 2, '.', '');

            $receiver[0] = new Receiver();
            $receiver[0]->amount = "2.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
            $receiver[0]->email = env("PAYPAL_ADMINID");
//            $receiver[0]->email = "vinidubey-facilitator@globussoft.in";
            $receiver[0]->primary = "false";
//            print_a($receiver);
            $receiver[1] = new Receiver();
            $receiver[1]->amount = "8.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
//            $receiver[1]->email = env("PAYPAL_ADMINID");
//            $receiver[1]->email = "vinidubey-facilitator@globussoft.in";
            $receiver[1]->email = "srik9009@gmail.com";
            $receiver[1]->primary = "true";

            $receiverList = new ReceiverList($receiver); // GET RECIEVER LIST
            $payRequest->receiverList = $receiverList;

            $requestEnvelope = new RequestEnvelope("en_US"); // FOR DEFAULT COUNTY REQUEST
            $payRequest->requestEnvelope = $requestEnvelope;
//            $payRequest->actionType = "PAY_PRIMARY"; //METHOD TYPE FOR DELAYED PAYMENT
            $payRequest->actionType = "PAY_PRIMARY"; //METHOD TYPE FOR DELAYED PAYMENT
            $payRequest->cancelUrl = "http://localhost.flashsale.com/paymentError?cancel=true"; //CANCEL URL
//        $payRequest->returnUrl = "http://localhost.flash.com/expressCallback";
            $payRequest->returnUrl = env("PAYPAL_WEB_URL") . '/expressCallback'; // RETURN URL WITH SERVER URL // SHOULD ALWAYS BE STARTED WITH HTTP
            $payRequest->currencyCode = "USD"; //DEFAULT CURRENCY CODE FOR PAYPAL
            $payRequest->ipnNotificationUrl = "http://replaceIpnUrl.com"; // IPN NOTIFY URL
//            print_a($payRequest);

            $sdkConfig = array(
                "mode" => env('MODE'),
                "acct1.UserName" => env('PAYPAL_USERNAME'),
                "acct1.Password" => env('PAYPAL_PASSWORD'),
                "acct1.Signature" => env('PAYPAL_SIGNATURE'),    // TODOO : THIS ALL IS SET IN ENV NEED TO CHANGE
                "acct1.AppId" => env('PAYPAL_APPLICATIONID') // TODOO : NEED TO CHANGE AS THIS IS TEMPORARY PAYPAL APP ID
            );
//        echo'<pre>';print_r($sdkConfig2);
            $adaptivePaymentsService = new AdaptivePaymentsService($sdkConfig); // PASSING USER PAY CONFIGURATION

            $payResponse = $adaptivePaymentsService->Pay($payRequest); // GET PAYREQUEST AND REQUEST FOR RECIEVER PAYKEY
//            echo'<pre>';print_r($payResponse);die("dfch");
            $paykey = $payResponse->payKey;
//            print_a($paykey);
            $setPaymentOptionsRequest = new SetPaymentOptionsRequest($requestEnvelope);
//            $email = $finalData[0]['email']; TODOO : OPTIONAL NEED TO BE CHANGED FOR NOW USING PRIMARY RECIEVER MAIL ID
//            $email = env("PAYPAL_ADMINID");
//            $email = "vinidubey-facilitator@globussoft.in";
            $email = "srik9009@gmail.com";
            $phoneNumber = $finalData[0]['phone'];
//            foreach ($finalData as $payKey => $payVal) { TODOO : UNCOMMENT THIS
            $item = new InvoiceItem(); // FOR INVOICE ITEMS
//                $item->name = $payVal['product_name'];
            $item->name = "Brand";
            $item->identifier = 1; //TODOO: THIS ONE FOR TEST PURPOSE ONLY//
//                $item->price = number_format((float)$payVal['shipingPrice'], 2, '.', '') + isset($payVal['discountedPrice']) ? number_format((float)$payVal['discountedPrice'], 2, '.', '') : number_format((float)$payVal['finalPrice'], 2, '.', '');
            $item->price = "8.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
//                $item->itemPrice = $payVal['price_total'];
            $item->itemPrice = "8.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
//                $item->itemCount = count($finalData);
            $item->itemCount = 32;//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
            $invoiceItems[] = $item;


            //  TODOO: OPTIONAL FIELDS //

//                if($_POST['description'] != "") {
//                    /*
//                     * (Optional) A description you want to associate with the payment. This overrides the value of the memo in Pay API for each receiver. If this is not specified the value in the memo will be used.
//                     */
//                    $receiverOptions->description = $_POST['description'];
//                }
            /*
             *  (Optional) An external reference or identifier you want to associate with the payment.
             */
//                if($_POST['customId'] != "") {
//                    $receiverOptions->customId = $_POST['customId'];
//                }
            /*
             *
             */
//                if($_POST['receiverReferrerCode'] != "") {
//                    $receiverOptions->referrerCode = $_POST['receiverReferrerCode'];
//                }


//            }
//            print_a($invoiceItems);
            $totalTax['totalTax'] = 2;
            $totalShipping['totalShipping'] = $finalData[0]['totalShippingPrice'];
            $receiverOptions = new ReceiverOptions();
            $setPaymentOptionsRequest->receiverOptions[] = $receiverOptions;
            if (count($invoiceItems) > 0 || $totalTax['totalTax'] != "" || $totalShipping['totalShipping'] != "") {
                $receiverOptions->invoiceData = new InvoiceData(); // FOR GETTING INVOICE DATA
                if ($totalTax['totalTax'] != "") {
                    $receiverOptions->invoiceData->totalTax = $totalTax['totalTax'];
                }
                if ($totalShipping['totalShipping'] != "") {
                    $receiverOptions->invoiceData->totalShipping = $totalShipping['totalShipping'];
                }
                if (count($invoiceItems) > 0) {
                    $receiverOptions->invoiceData->item = $invoiceItems;
                }
            }

            //   TODOO : OPTIONAL FIELDS //
//            if($_POST['emailIdentifier'] != "" || ($_POST['phoneNumber'] != "" && $_POST['phoneCountry'] != "")) {
            if ($email != "") {
                $receiverId = new ReceiverIdentifier(); // FOR RECIEVER IDENTIFICATION AND IDENTIFIERS
                if ($email != "") {
                    $receiverId->email = $email;
                }
                //       TODOO: OPTIONAL FIELDS CAN BE DONE LATER //
//                if($phoneNumber != "") {
//                    $receiverId->phone = new PhoneNumberType($_POST['phoneCountry'], $_POST['phoneNumber']);
//                    if($_POST['phoneExtn'] != "") {
//                        $receiverId->phone->extension = $_POST['phoneExtn'];
//                    }
//                }
                $receiverOptions->receiver = $receiverId;
            }
//            echo'<pre>';print_r($payRequest);echo'</pre>';
//            print_a($receiverOptions);
//            echo'<pre>';print_r($receiverOptions); echo'</pre>';


            /*
             * (Required) The pay key that identifies the payment for which you want to set payment options. This is the pay key returned in the PayResponse message.
             */
            $setPaymentOptionsRequest->payKey = $paykey;
//            $setPaymentOptionsRequest->payKey = "AP-7TG72448JJ099191D";
            $shipAddress = $finalData[0]['addressline1'];
            if ($shipAddress != "") {
                $setPaymentOptionsRequest->senderOptions = new SenderOptions();
                if ($shipAddress != "") {
                    /*
                     * (Optional) If true, require the sender to select a shipping address during the embedded payment flow; default is false.
                     */
                    $setPaymentOptionsRequest->senderOptions->requireShippingAddressSelection = $shipAddress;
                }
//                if($_POST['senderReferrerCode'] != "") {
//                    $setPaymentOptionsRequest->senderOptions->referrerCode = $_POST['senderReferrerCode'];
//                }
            }
//            print_a($setPaymentOptionsRequest);
            $service = new AdaptivePaymentsService($sdkConfig);

            try {
                /* wrap API method calls on the service object with a try catch */
                $response = $service->SetPaymentOptions($setPaymentOptionsRequest);
                print_a($response);
            } catch (Exception $ex) {
//                require_once 'Common/Error.php';
                echo "error";
                exit;
            }


            Session::put('paykey', $paykey);


            return redirect("https://www.sandbox.paypal.com/webscr?cmd=_ap-payment&paykey=$paykey");
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: xx-xx-xxxx
     */
    public function expressCallBack(Request $request)
    {

        $payRequest = new PayRequest();
        $sdkConfig = array(
            "mode" => "sandbox",
            "acct1.UserName" => "vinidubey_api1.globussoft.in",
            "acct1.Password" => "W33YQEMR3PAY6F8T",
            "acct1.Signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31A4y-QdgKwGOZraSJh--ynyi5UNJF",
            "acct1.AppId" => "APP-80W284485P519543T"
        );//these data now should come from env file.

        $adaptivePaymentsService = new AdaptivePaymentsService($sdkConfig);
        $requestEnvelope = new RequestEnvelope("en_US");
        $paymentDetailsReq = new PaymentDetailsRequest($requestEnvelope);
        $paykey = Session::get('paykey');
        $paymentDetailsReq->payKey = $paykey;
        $response = $adaptivePaymentsService->PaymentDetails($paymentDetailsReq);
        $payResponse = json_decode(json_encode($response), true);
//        print_a($payResponse);
        $actualPayKey = $payResponse['payKey'];
        $shippingAddress = $payResponse['shippingAddress'];
        $initialStatus = $payResponse['status'];
        $senderEmail = $payResponse['senderEmail'];
        $payInfoResponse = $payResponse['paymentInfoList'];
        $paymentDetail = new Collection();
        foreach ($payInfoResponse['paymentInfo'] as $mainkey => $mainval) {
            $paymentDetail->push([
                'tx_code' => $mainval['senderTransactionId'],
                'tx_type' => 'purchase',
                'tx_status' => $mainval['transactionStatus'],
                'tx_amount' => $mainval['receiver']['amount'],
                'user_details' => $mainval['receiver']['email'],
//                'senderStatus' => $mainval['senderTransactionStatus'],
            ]);
        }
//        print_a($paymentDetail);
        $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "order/insert-transaction-details";
        $mytoken = env("API_TOKEN");
        $user_id = '';
        if (Session::has('fs_user')) {
            $user_id = Session::get('fs_user')['id'];

        }

        $data = array('api_token' => $mytoken, 'id' => $user_id, 'payresponse' => json_encode($paymentDetail->toArray()));
        $curlResponse = $objCurl->curlUsingPost($url, $data);
        if ($curlResponse->code == 200) {
            return redirect('/');
        }
//        return $paymentDetail;
//        if($paymentDetail){
//            $executePayResponse = $adaptivePaymentsService->ExecutePayment($paymentDetailsReq);
//            $requestEnvelope = new RequestEnvelope("en_US");
//            $paymentDetailsReq = new PaymentDetailsRequest($requestEnvelope);
//            $paykey = Session::get('paykey');
//            $paymentDetailsReq->payKey = $paykey;
//            $paymentResponse = $adaptivePaymentsService->PaymentDetails($paymentDetailsReq);
//            $mainPayResponse = json_decode(json_encode($paymentResponse), true);
//
//        }
//        print_a($mainPayResponse);


    }

    public function paymentError(Request $request)
    {
        dd($request->all());


    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author [modified] Akash M. Pai
     */
    public function checkOutDetails(Request $request)
    {

        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $user_id = '';
        if (Session::has('fs_user')) {
            $user_id = Session::get('fs_user')['id'];
        } else if (Session::has('fs_buyer')) {
            $user_id = Session::get('fs_buyer')['id'];
        }

        if ($request->isMethod('post')) {
            $inputData = $request->all();
            $urlP = Session::get("domainname") . env("API_URL") . "/process-checkout";
            $rules = [
                'paymentMethod' => 'required'
            ];
            $messages['paymentMethod.required'] = 'Please select atleast one payment method.';
            $validator = Validator::make($inputData, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["code" => 400, "status" => 'error', 'message' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $inputData['allWalletAmtFlag'] = array_search("wallet", $inputData['paymentMethod']) ? ((isset($inputData['useallwallet']) && $inputData['useallwallet'] == 'on') ? true : false) : false;
                $inputData['walletAmount'] = (!$inputData['allWalletAmtFlag']) ? $inputData['walletvalue'] : 0;

                $inputData['allRpFlag'] = array_search("rp", $inputData['paymentMethod']) ? ((isset($inputData['useallrp']) && $inputData['useallrp'] == "on") ? true : false) : false;
                $inputData['rpAmount'] = (!$inputData['allRpFlag']) ? (isset($inputData['rpvalue']) && $inputData['rpvalue'] != '' ? $inputData['rpvalue'] : 0) : 0;

                unset($inputData['useallrp'], $inputData['useallwallet'], $inputData['walletvalue'], $inputData['rpvalue']);

                $inputData['api_token'] = $mytoken;
                $inputData['id'] = $user_id;
                $inputData['paymentMode'] = 'web';
                $inputData['paymentMethod'] = json_encode($inputData['paymentMethod']);
                //"s_addressline1" => $inputData['s_addressline1'], "s_addressline2" => $inputData['s_addressline1'], "s_city" => $inputData['s_city'], "s_state" => $inputData['s_state'], "s_country" => $inputData['s_country'], "s_phone" => $inputData['s_phone'], "s_zip" => $inputData['s_zip'], "b_addressline1" => $inputData['b_addressline1'], "b_addressline2" => $inputData['b_addressline1'], "b_city" => $inputData['b_city'], "b_state" => $inputData['b_state'], "b_country" => $inputData['b_country'], "b_phone" => $inputData['b_phone'], "b_zip" => $inputData['b_zip']
//                dd($inputData);
                $curlResponse = $objCurl->curlUsingPost($urlP, $inputData);
                dd($curlResponse);
                if ($curlResponse->code == 200) {
                    if ($inputData['paymentMethod'] == "paypal") {
                        return Redirect($curlResponse->data['link']);
                    } else {
                        return Redirect::back()
                            ->with(["code" => 200, "status" => 'success', 'message' => $curlResponse->data['message']])
                            ->withInput();
                    }
                } else {
                    return Redirect::back()
                        ->with(["code" => 400, "status" => 'error', 'message' => $curlResponse->message])
                        ->withInput();
                }
//                dd($curlResponse);
            }


        }

        $url = Session::get("domainname") . env("API_URL") . '/' . "payment-handler";
        $data = array('api_token' => $mytoken, 'id' => $user_id, 'method' => 'checkOutDetails');
        $curlResponse = $objCurl->curlUsingPost($url, $data);
        if ($curlResponse->code == 200) {
            if (Session::has('fs_user')) {
                return view('Payment/Views/payment/payment', ['paymentDetails' => $curlResponse->data]);
            } else if (Session::has('fs_buyer')) {
                return view('Payment/Views/payment/buyercheckout', ['paymentDetails' => $curlResponse->data]);
            }
        }

    }

    /**
     * @param Request $request
     * @return shows response message and show invoice to user //TODO
     * @author [modified by] Akash M. Pai
     */
    public function paypalResponse(Request $request)
    {
//        todo if token not set or previous page from history not of paypal show error message
        $requestData = $request->all();
        if (isset($requestData['token']) && !empty($requestData['token'])) {
            $token = $requestData['token'];

            $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
            $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();

            $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

            $config = array(
                // Signature Credential
                "acct1.UserName" => env('PAYPAL_SANDBOX_USERNAME'),//"akashpai-facilitator_api1.globussoft.com",
                "acct1.Password" => env('PAYPAL_SANDBOX_PASSWORD'),//"EF6QNKTYRETQBMYE",
                "acct1.Signature" => env('PAYPAL_SANDBOX_SIGNATURE'),// "Aaa2LqZCnxPUve1-f0FrCKM5YDMyA-ufQ1ROfJle147cxpNUX4GnG7ws",
                "mode" => env('PAYPAL_MODE')
            );

            $paypalService = new PayPalAPIInterfaceServiceService($config);//Configuration::getAcctAndConfig()

            $getECResponse = '';
            try {
                $getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
            } catch (Exception $ex) {

                $ex_message = "";
                $ex_detailed_message = "";
                $ex_type = "Unknown";

                if (isset($ex)) {
                    $ex_message = $ex->getMessage();
                    $ex_type = get_class($ex);

                    if ($ex instanceof PPConnectionException) {
                        $ex_detailed_message = "Error connecting to " . $ex->getUrl();
                    } else if ($ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException) {
                        $ex_detailed_message = $ex->errorMessage();
                    } else if ($ex instanceof PPConfigurationException) {
                        $ex_detailed_message = "Invalid configuration. Please check your configuration file";
                    }
                }
                exit;
            }

            if (isset($getECResponse) && $getECResponse != '') {
                echo "<tr><td>Ack :</td><td><div id='Ack'>" . $getECResponse->Ack . "</div> </td></tr>";
                echo "<tr><td>Token :</td><td><div id='Token'>" . $getECResponse->GetExpressCheckoutDetailsResponseDetails->Token . "</div></td></tr>";
                echo "<tr><td>PayerID :</td><td><div id='PayerID'>" . $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID . "</div></td></tr>";
                echo "<tr><td>PayerStatus :</td><td><div id='PayerStatus'>" . $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerStatus . "</div></td></tr>";
                echo '<pre>';
                dd($getECResponse);
                echo '</pre>';
                $paymentDetails = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails;
                $PPcheckoutStatus = $getECResponse->GetExpressCheckoutDetailsResponseDetails->CheckoutStatus;
                $payerId = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
                dd($paymentDetails[0]->OrderTotal);

                //steps for validation of response start
                DB::beginTransaction();
                $objModelTransactions = Transactions::getInstance();
                $objModelOrders = Orders::getInstance();


                $orderStatus = "P";
                $txStatus = "P";
                if ($getECResponse->Ack == "Success") {//   if Ack==Success
                    if ($PPcheckoutStatus == "PaymentActionNotInitiated") { //   if CheckoutStatus == PaymentActionNotInitiated
                        //  perform doexpresscheckout
                        //DOEXPRESS CHECKOUT START

                        /*
                         * The DoExpressCheckoutPayment API operation completes an Express Checkout transaction. If you set up a billing agreement in your SetExpressCheckout API call, the billing agreement is created when you call the DoExpressCheckoutPayment API operation
                         */

//                    $paymentAction = $paymentDetails[0]->PaymentAction;//urlencode($_REQUEST['paymentAction']);//todo Sale||Authorization||Order

                        /*
                         * The total cost of the transaction to the buyer. If shipping cost (not applicable to digital goods) and tax charges are known, include them in this value. If not, this value should be the current sub-total of the order. If the transaction includes one or more one-time purchases, this field must be equal to the sum of the purchases. Set this field to 0 if the transaction does not include a one-time purchase such as when you set up a billing agreement for a recurring payment that is not immediately charged. When the field is set to 0, purchase-specific fields are ignored.
                         * For digital goods, the following must be true:
                         * total cost > 0
                         * total cost <= total cost passed in the call to SetExpressCheckout
                        */
                        $orderTotal = new BasicAmountType();
                        $orderTotal->currencyID = $paymentDetails[0]->OrderTotal->currencyId; //$_REQUEST['currencyCode'];
                        $orderTotal->value = $paymentDetails[0]->OrderTotal->value; //$_REQUEST['amt'];

                        $paymentDetails = new PaymentDetailsType();
                        $paymentDetails->OrderTotal = $orderTotal;

                        /*
                         * Your URL for receiving Instant Payment Notification (IPN) about this transaction. If you do not specify this value in the request, the notification URL from your Merchant Profile is used, if one exists.
                         */
                        if ($paymentDetails[0]->NotifyURL != null && $paymentDetails[0]->NotifyURL != "") {
                            $paymentDetails->NotifyURL = $paymentDetails[0]->NotifyURL; //$_REQUEST['notifyURL'];
                        }

                        $DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
                        $DoECRequestDetails->PayerID = $payerId;
                        $DoECRequestDetails->Token = $token;
//                    $DoECRequestDetails->PaymentAction = $paymentAction;todo
                        $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

                        $DoECRequest = new DoExpressCheckoutPaymentRequestType();
                        $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

                        $DoECReq = new DoExpressCheckoutPaymentReq();
                        $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

                        $DoECResponse = array();

                        try {
                            /* wrap API method calls on the service object with a try catch */
                            $DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);
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
                        if (isset($DoECResponse) && !empty($DoECResponse)) {
//                        echo "<table>";
                            echo "<tr><td>Ack :</td><td><div id='Ack'>$DoECResponse->Ack</div> </td></tr>";
                            if (isset($DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo)) {
                                echo "<tr><td>TransactionID :</td><td><div id='TransactionID'>" . $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->TransactionID . "</div> </td></tr>";
                            }
//                        echo "</table>";
                            echo "<pre>";
                            print_r($DoECResponse);
                            echo "</pre>";
                        }

                        //DOEXPRESS CHECKOUT END

                        //  call GetExpressCheckoutDetails again to get updated details of the payment
                        //  if Ack==Success
                        //      set order/transaction status == status of response
                        //      [if still payment not completed this will be handled in IPN handler]
                    } else if ($PPcheckoutStatus == "PaymentActionFailed") { // else if CheckoutStatus == PaymentActionFailed
                        //  set order/transaction status == failed
                        $orderStatus = "F";
                        $txStatus = "F";
                    } else if ($PPcheckoutStatus == "PaymentActionInProgress") {//  else if CheckoutStatus == PaymentActionInProgress
                        //  set order/transaction status == in progress
                        $orderStatus = "P";
                        $txStatus = "IP";
                    } else if ($PPcheckoutStatus == "PaymentActionCompleted") { //  else if CheckoutStatus == PaymentActionCompleted
                        //  set order/trasaction status == success
                        $orderStatus = "S";
                        $txStatus = "S";
                    }
                    //  get order details using explode GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->invoiceId
                    //  if ordertype == P (product)
                    //      foreach order
                    //          validate the total amount [+ discount + tax + shipping] for each order from PaymentDetailsItem
                    //  else if ordertype == G (gift certificate)
                    //      validate the total amount
                    $paymentFor = explode($paymentDetails[0]->PaymentDetailsItem);
                    $whereForOrder = ['rawQuery'=> "order_id IN ?", 'bindParams'=>[]];
                    $ordersRes = json_decode($objModelOrders->getAllOrdersWhere($whereForOrder), true);
                    switch ($paymentFor[1]) {
                        case "P":
                            foreach ($ordersRes as $keyOrdersRes => $valOrderRes) {
                                $valOrderRes;
                                //////////////////////////////working here//////////////////////
                            }
                            break;

                        case "G":

                            break;

                        default:

                            break;

                    }

                    //  validate user details
                } else {//  else
                    //  set order/transaction status == Failed
                    $orderStatus = "F";
                    $txStatus = "F";
                }
                //  update order and insert transaction
                $dataForOrderUpdate = [];
                $whereForOrderUpdate = ['rawQuery' => '', 'bindParams' => []];
                $resUpdOrder = json_decode($objModelOrders->updateOrderWhere($whereForOrderUpdate, $dataForOrderUpdate));

                $dataForTxInsert = [];
//            $whereForTxInsert = ['rawQuery' => '', 'bindParams' => []];
                $resInsTx = json_decode($objModelTransactions->addNewTransaction($dataForTxInsert), true);
                if ($resInsTx['code'] == 200) {
                    //  set message and show payment status to user [by redirecting (to avoid using the same link again by reloading etc)]
                } else {

                }

                //steps for validation of response end

            }

//        echo htmlspecialchars($paypalService->getLastRequest());
//        echo htmlspecialchars($paypalService->getLastResponse());
        } else {

        }

    }

    public function paypalCancel(Request $request)
    {

    }

    public function paypalIPNListener(Request $request)
    {

    }

    /**
     * @param Request $request
     * @author Akash M. Pai
     * @desc working example of paypal express checkout //NOT REQUIRED example only
     */
    public function paypalPayment(Request $request)
    {
        $config = array(
            // Signature Credential
            "acct1.UserName" => "akashpai-facilitator_api1.globussoft.com",
            "acct1.Password" => "EF6QNKTYRETQBMYE",
            "acct1.Signature" => "Aaa2LqZCnxPUve1-f0FrCKM5YDMyA-ufQ1ROfJle147cxpNUX4GnG7ws",
            "mode" => "sandbox"
        );

        $url = dirname('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI']);
        $url = "http://localhost.flashsale.com/admin";
        $returnUrl = "$url/test-paypal-success";
        $cancelUrl = "$url/test-paypal-cancel";
        $notifyUrl = "$url/test-paypal-ipn";
//dd($returnUrl);
        $currencyCode = "USD";//TODOO GET DEFAULT CURRENCT FROM FROM USERS TABLE;

//        $shippingTotal = new BasicAmountType($currencyCode, 10.00);//TODOO GET SHIPPING COST FROM PRODUCTS TABLE;
//        $handlingTotal = new BasicAmountType($currencyCode, $_REQUEST['handlingTotal']);//NOT USED
//        $insuranceTotal = new BasicAmountType($currencyCode, $_REQUEST['insuranceTotal']);//NOT USED

        $address = new AddressType();
        $address->CityName = "Mysore";//TODOO GET ADDRESS FROM THE CHECKOUT FORM //$_REQUEST['city'];
        $address->Name = "Akash";//$_REQUEST['name'];
        $address->Street1 = "Test street";//$_REQUEST['street'];
        $address->StateOrProvince = "Karnataka"; //$_REQUEST['state'];
        $address->PostalCode = 570004; //$_REQUEST['postalCode'];
        $address->Country = "IN"; //$_REQUEST['countryCode'];
        $address->Phone = 919632872993; //$_REQUEST['phone'];

        $paymentDetails = new PaymentDetailsType();
        $itemTotalValue = 0;
        $taxTotalValue = 0;
        //TODOO GET DATA OF CART FROM COOKIES
        $productCount = 4; //count($_REQUEST['itemAmount']);
        for ($i = 0; $i < $productCount; $i = $i + 2) {
            $itemAmount = new BasicAmountType($currencyCode, 10.00);//$_REQUEST['itemAmount']
            $itemTotalValue += 10.00 * 3;//$_REQUEST['itemQuantity'][$i]
            $taxTotalValue += 3.00 * 3;//$_REQUEST['itemSalesTax'][$i] * $_REQUEST['itemQuantity'][$i];
            $itemDetails = new PaymentDetailsItemType();
            $itemDetails->Name = "Test item $i";//$_REQUEST['itemName'][$i];
            $itemDetails->Amount = $itemAmount;
//            $itemDetails->ItemTotal = $itemAmount;
            $itemDetails->Quantity = 3;//$_REQUEST['itemQuantity'][$i];
            $itemDetails->ItemCategory = 'Physical';
            $itemDetails->Tax = new BasicAmountType($currencyCode, 3.00);//$_REQUEST['itemSalesTax'][$i]

            $paymentDetails->PaymentDetailsItem[$i] = $itemDetails;

//IF DISCOUNT IS TO BE PROVIDED
            $itemAmount = new BasicAmountType($currencyCode, -1.00);//$_REQUEST['itemAmount']
            $itemTotalValue += (-1.00 * 3);//$_REQUEST['itemQuantity'][$i]
//            $taxTotalValue += 3.00 * 3;//$_REQUEST['itemSalesTax'][$i] * $_REQUEST['itemQuantity'][$i];
            $itemDetails = new PaymentDetailsItemType();
            $itemDetails->Name = "Discount " . ($i + 1);//$_REQUEST['itemName'][$i];
            $itemDetails->Amount = $itemAmount;
//            $itemDetails->ItemTotal = $itemAmount;
            $itemDetails->Quantity = 3;//$_REQUEST['itemQuantity'][$i];
//            $itemDetails->ItemCategory = 'Physical';
//            $itemDetails->Tax = new BasicAmountType($currencyCode, 3.00);//$_REQUEST['itemSalesTax'][$i]

            $paymentDetails->PaymentDetailsItem[$i + 1] = $itemDetails;

        }
//dd($itemTotalValue);
        $orderTotalValue = $itemTotalValue + $taxTotalValue;//$shippingTotal->value + + $handlingTotal->value + $insuranceTotal->value
//dd($orderTotalValue);

        $paymentDetails->ShipToAddress = $address;
        $paymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
        $paymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
        $paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);

//        dd($paymentDetails);
        $paymentDetails->PaymentAction = "Sale";//$_REQUEST['paymentType'];
//        $paymentDetails->HandlingTotal = $handlingTotal;
//        $paymentDetails->InsuranceTotal = $insuranceTotal;
//        $paymentDetails->ShippingTotal = $shippingTotal;

        $paymentDetails->NotifyURL = $notifyUrl; //$_REQUEST['notifyURL'];

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $paymentDetails;
        $setECReqDetails->CancelURL = $cancelUrl;
        $setECReqDetails->ReturnURL = $returnUrl;
        $setECReqDetails->NoShipping = 1; //$_REQUEST['noShipping'];
        $setECReqDetails->AddressOverride = 0; //$_REQUEST['addressOverride'];
        $setECReqDetails->ReqConfirmShipping = 0; //$_REQUEST['reqConfirmShipping'];

        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

//        dd("here");
        $paypalService = new PayPalAPIInterfaceServiceService($config);

        try {
            /* wrap API method calls on the service object with a try catch */
            $setECResponse = $paypalService->SetExpressCheckout($setECReq);
        } catch (Exception $ex) {

            $ex_message = "";
            $ex_detailed_message = "";
            $ex_type = "Unknown";

            if (isset($ex)) {

                $ex_message = $ex->getMessage();
                $ex_type = get_class($ex);

                if ($ex instanceof PPConnectionException) {
                    $ex_detailed_message = "Error connecting to " . $ex->getUrl();
                } else if ($ex instanceof PPMissingCredentialException || $ex instanceof PPInvalidCredentialException) {
                    $ex_detailed_message = $ex->errorMessage();
                } else if ($ex instanceof PPConfigurationException) {
                    $ex_detailed_message = "Invalid configuration. Please check your configuration file";
                }
            }

            echo $ex_type;
            echo "-----------------<br>";
            echo $ex_message;
            echo "-----------------<br>";
            echo $ex_detailed_message;
            exit;
        }

        if (isset($setECResponse)) {
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>$setECResponse->Ack</div> </td></tr>";
            echo "<tr><td>Token :</td><td><div id='Token'>$setECResponse->Token</div> </td></tr>";
            echo "</table>";
            echo '<pre>';
            print_r($setECResponse);
            echo '</pre>';
            if ($setECResponse->Ack == 'Success') {
                $token = $setECResponse->Token;
                // Redirect to paypal.com here
                $payPalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token;
                echo " <a href=$payPalURL><b>* Redirect to PayPal to login </b></a><br>";
            }
        }


    }


}
