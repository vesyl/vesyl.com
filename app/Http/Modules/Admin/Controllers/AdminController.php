<?php

namespace FlashSale\Http\Modules\Admin\Controllers;


use FlashSale\Http\Modules\Admin\Models\Products;
use FlashSale\Http\Modules\Admin\Models\SettingsSection;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use FlashSale\Http\Modules\Admin\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use SendGrid\Client;
use SendGrid\Email;
use SendGrid\Mail;

require public_path() . '/../vendor/autoload.php';

//* TEST USE START
use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\AP\InvoiceItem;
use PayPal\Types\AP\InvoiceData;
use PayPal\Types\Common\RequestEnvelope;

use PayPal\Types\AP\SetPaymentOptionsRequest;
use PayPal\Types\AP\ReceiverOptions;
use PayPal\Types\AP\ReceiverIdentifier;

//use PayPal\Api\Amount;
//use PayPal\Api\Details;
//use PayPal\Api\Item;
//use PayPal\Api\ItemList;
//use PayPal\Api\Payer;
//use PayPal\Api\Payment;
//use PayPal\Api\RedirectUrls;
//use PayPal\Api\Transaction;

//EXPRESS libraries start
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;

use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;

//EXPRESS libraries end

//TEST USE END


class AdminController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dashboard()
    {
//        echo "<pre>";
//        print_r(Session::all());
//        die;
//        $userModel = new User();
//        $users = User::all();
//        echo "<pre>";
//        foreach ($users as $user => $userval) {
//            echo $userval;
//        }
//        die;
//        echo "<pre>";
//        print_r($users);
//        die;

        $objUserModel = Products::getInstance();

        $where= ['rawQuery' => 'order_status =?', 'bindParams' => ['P']];
        $productdetails = $objUserModel->getOrderProducts($where);
        $orderdetails = $objUserModel->getTotalOrders();
        $productgraphDetails=$objUserModel->getProductDetails();
        $ordergraphDetails=$objUserModel->getOrdersDetails();
        $where1 = ['rawQuery'=>'order_status=?','bindParams'=>['D']];
        $revenueDetails=$objUserModel->getRevenueDetails($where1);
        $where2 = ['rawQuery'=>'order_status=? OR order_status=? ','bindParams'=>['D','R']];
        $revenueOrderDetails=$objUserModel->getRevenueOrdersDetails($where2);
//        dd($revenueOrderDetails);
        return view("Admin/Views/admin/dashboard",
            ['productdetails' => $productdetails,
            'count' => $orderdetails,
            'orderdetails'=>$ordergraphDetails,
            'products'=>$productgraphDetails,
            'revenuestatus'=>$revenueDetails,
            'orderstatus'=>$revenueOrderDetails]);

    }

    public function adminlogin(Request $data)
    {
//        dd($data); die;
        if (Session::has('fs_admin') || $data->session()->has('fs_admin')) {//|| Session::has('fs_manager')
            return redirect('/admin/dashboard');
        }
        if ($data->isMethod('post')) {
            $email = $data->input('email');
            $password = $data->input('password');

            /* BELOW BLOCK TO INSERT ADMIN USER FIRST TIME
            $objUser = new User();
            $data = array(
                'name' => 'FlashSale Admin',
                'username' => 'admin',
                'email' => 'admin@flashsale.com',
                'password' => Hash::make('admin'),
//                'added_date' => time(),
                'role' => "5",
                'status' => '1'
            );
            $result = DB::table('users')->insert($data);
//            $result = $objUser->addNewUser($data);
            echo "<pre>"; print_r($result);
            die; */

            $this->validate($data, [
                'email' => 'required|email',
                'password' => 'required',
            ], ['email.required' => 'Please enter email address or username',
                    'password.required' => 'Please enter a password']
            );
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $objModelUsers = User::getInstance();
//                User::getInstance();
                $userDetails = $objModelUsers->getUserById(Auth::id()); //THIS IS TO GET THE MODEL OBJECT
//                $userDetails = DB::table('users')->select()->where('id', 1)->first(); //USED TO GET ROW OBJECT
//                echo "<pre>"; print_r($userDetails); die;

                if ($userDetails->role == 5) {
                    $sessionName = 'fs_admin';
                    Session::put($sessionName, $userDetails['original']);
                    return redirect('/admin/dashboard');
                } else {
                    return redirect('/admin/login')->withErrors([
                        'errMsg' => 'Invalid credentials.'
                    ]);
                }

//                if ($userDetails['role'] == 4) {
//                    $sessionName = 'fs_manager';
//                }

            } else {
                return redirect('/admin/login')->withErrors([
                    'errMsg' => 'Invalid credentials.'
                ]);
            }
        }
        return view("Admin/Layouts/adminlogin");
    }


    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminLogout()
    {
        Session::forget('fs_admin');
        return redirect('/admin/login');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function managerLogout()
    {
        Session::forget('fs_manager');
        return redirect()->guest('/manager/login');
    }


    /**
     * @return array|bool|object
     */
    public static function getSettingsSection()
    {
        $objSettingsSection = SettingsSection::getInstance();
        $whereForSetting = ['rawQuery' => 'parent_id =? AND type =? AND status =?', 'bindParams' => [0, 'CORE', 1]];
        $allSections = $objSettingsSection->getAllSectionWhere($whereForSetting);
        return $allSections;
    }


    public static function forgotpassword(Request $data)
    {
        if ($data->isMethod('post')) {
            $email1 = $data->input('email');
            $res = $resetcode = mt_rand(1000000, 9999999);
            $user_model = User::getInstance();
            $password = $resetcode;
            $data = ['reset_code' => $password];
            $where = ['rawQuery' => 'email=?','bindParams' => [$email1]];
            $result = $user_model->updateUserWhere($data,$where);
            $from = new \SendGrid\Email(null, "support@flashsale.com");
            $subject = "Forgot password!";
            $to = new \SendGrid\Email(null, $email1);
            $content = new \SendGrid\Content("text/html", "<!doctype html>
               <html>
               <p>$resetcode </p>
                </html>");
            $mail = new \SendGrid\Mail($from, $subject, $to, $content);
            $apiKey = env('SENDGRID_API_KEY');
            $sg = new \SendGrid($apiKey);

            $response = $sg->client->mail()->send()->post($mail);
            if ($response->statusCode() == 202) {
                echo json_encode(['status' => 200]);
            }
        }


    }


    public function password(Request $request)
    {

//        $method = $request->input('method');
        if ($request->isMethod('get')) {
            return view('Admin/Views/password/updatepassword');
        }


//
//        switch ($method) {
//
//            case 'updatePassword':
//
//
//                Validator::extend('passwordCheck', function ($attribute, $value, $parameters) {
//                    return Hash::check($value, Auth::user()->getAuthPassword());
//                }, 'Your current password is incorrect');
//
//                $passwordRules = array(
//                    'oldpassword' => 'required | passwordCheck',
//                    'newpassword' => 'required',
//                    'confirmpassword' => 'required | same:newpassword'
//                );
//
//                $passwordValidator = Validator::make($request->all(), $passwordRules);
//                if ($passwordValidator->fails()) {
//                    echo json_encode(array('status' => 'error', 'message' => $passwordValidator->messages()->all(), 'error' => 198));
//                } else {
//                    $user = Auth::user();
//                    $user->password = Hash::make($request->input('new_password'));
//                    $user->save();
//                    echo json_encode(array('status' => 'success', 'message' => 'Your password has been successfully updated . '));
//                }
//                break;
        if ($request->isMethod('post')) {
            $user_model = User::getInstance();
            $mainId = Session::get('fs_admin')['id'];

            $oldpassrd = $request->get('oldpassword');

            $sres = $user_model->chck_passord($mainId);
            if (Hash::check($oldpassrd, $sres->password)) {
//                dd(true);
                if ($request->get('newpassword') == $request->get('confirmpassword')) {
                    $password = Hash::make($request->get('newpassword'));
                    $data = ['password' => $password];
                    $where = ['rawQuery' => '   id =?', 'bindParams' => [$mainId]];
                    $result = $user_model->updateUserWhere($data, $where);
                    return Redirect::back()->with(['status' => 'success', 'msg' => 'Successfully Updated']);
                }
                return Redirect::back()->with(['status' => 'failed', 'msg' => 'enter same password in confirm password as entered in new password']);
            }
            return Redirect::back()->with(['status' => 'failed', 'msg' => 'Confirm Password is not Correct ']);
        }

    }

    public function submit(Request $request)
    {

        if ($request->isMethod('post')) {
            $user_model = User::getInstance();
            $userDetails = $user_model->getDetails($request->email, $request->password);
            // $userDetails ==ewtuening null u dooo now ok
            //THIS IS TO GET THE MODEL OBJECT
            if ($userDetails) {
                return 1;
            } else {
                return 0;

            }
        }
    }

    public function changepassword(Request $request)
    {
        $user_model = User::getInstance();

        if ($request->isMethod('post')) {
//            dd($request);
            $email = $request->get('email');
            $resetcode = $request->get('password');
            $userDetails = $user_model->getDetails($email, $resetcode);

            if ($request->get('newpassword') == $request->get('ConfirmPassword')) {
                $password = Hash::make($request->get('newpassword'));
                $data = ['password' => $password];
                $where = ['bindParams' => [$email]];
                $result = $user_model->updateUserInfo2($data, $where);
                //we need to redirt to login right? yes
//
                return 1;
            } else {
                return 0;

            }
        }
    }


    /**
     * @param Request $request
     * @return Redirect
     * @author Akash M. Pai <akashpai@globussoft.in>
     * @desc testing paypal adaptive simple/split/chained payments
     */
    public function testPaypal(Request $request)
    {
        //SUDO GEDIT MY_PRINT_DEFAULTS.c
//        dd($request);
//        $receiver = array();
        $invoiceData = array();
        $receiverOptions = array();
//        $invoiceItem = new InvoiceItem();
        $invoiceItems = array();
        $payRequest = new PayRequest();      // REQUEST FOR PAY
//        $receiver[0] = new Receiver(); // OBJECT FOR RECIEVER
//            $receiver[0]->amount = number_format((float)$finalData[0]['finalPrice'], 2, '.', '');

        $receiver = array();
        $receiver = new Receiver();
        $receiver->amount = "10.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $receiver->email = "akashpai-facilitator@globussoft.com";//env("PAYPAL_ADMINID");
//        $receiver->paymentType = "DIGITALGOODS";
//        $receiver[0]->primary = "true";

//        $receiver = array();
//        $receiver[0] = new Receiver();
//        $receiver[0]->amount = "2.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
//        $receiver[0]->email = "srik9009@gmail.com";//env("PAYPAL_ADMINID");
//        $receiver[0]->primary = "false";

//        $receiver[1] = new Receiver();
//        $receiver[1]->amount = "8.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
//        $receiver[1]->email = "akashpai-facilitator@globussoft.com";
//        $receiver[1]->primary = "false";

        $receiverList = new ReceiverList($receiver); // GET RECIEVER LIST
        $payRequest->receiverList = $receiverList;


        $payRequest->senderEmail = "akashpai-buyer@globussoft.com";

        $requestEnvelope = new RequestEnvelope("en_US");
        $payRequest->requestEnvelope = $requestEnvelope;
        $payRequest->actionType = "CREATE";
        $payRequest->cancelUrl = "http://localhost.flashsale.com/admin/test-paypal-cancel";
        $payRequest->returnUrl = "http://localhost.flashsale.com/admin/test-paypal-success";
        $payRequest->currencyCode = "USD";
        $payRequest->ipnNotificationUrl = "http://localhost.flashsale.com/admin/test-paypal-ipn";

        $sdkConfig = array(
//            "mode" => env('MODE'),
//            "acct1.UserName" => env('PAYPAL_USERNAME'),
//            "acct1.Password" => env('PAYPAL_PASSWORD'),
//            "acct1.Signature" => env('PAYPAL_SIGNATURE'),    // TODOO : THIS ALL IS SET IN ENV NEED TO CHANGE
//            "acct1.AppId" => env('PAYPAL_APPLICATIONID')
            "mode" => "sandbox",
            "acct1.UserName" => "akashpai-facilitator_api1.globussoft.com",
            "acct1.Password" => "EF6QNKTYRETQBMYE",
            "acct1.Signature" => "Aaa2LqZCnxPUve1-f0FrCKM5YDMyA-ufQ1ROfJle147cxpNUX4GnG7ws",
            "acct1.AppId" => "APP-80W284485P519543T"
        );

        $adaptivePaymentsService = new AdaptivePaymentsService($sdkConfig);
        $payResponse = $adaptivePaymentsService->Pay($payRequest);
//        dd($payResponse);
//        if (strtoupper($payResponse->responseEnvelope->ack) == 'SUCCESS') {
//            echo 'SUCCESS';
//        } else {
//            echo 'ERROR';
//        }
        $paykey = $payResponse->payKey;
        echo $paykey;
        //CASE 1 END HERE

        $setPaymentOptionsRequest = new SetPaymentOptionsRequest($requestEnvelope);

        $email = "akashpai-facilitator@globussoft.com";

        $item = new InvoiceItem(); // FOR INVOICE ITEMS
        $item->name = "Test product 1";
        $item->identifier = 1; //TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $item->price = "8.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $item->itemPrice = "8.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $item->itemCount = 1;//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $invoiceItems[] = $item;

        $item = new InvoiceItem(); // FOR INVOICE ITEMS
        $item->name = "Test product 2";
        $item->identifier = 2; //TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $item->price = "2.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $item->itemPrice = "2.00";//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $item->itemCount = 2;//TODOO: THIS ONE FOR TEST PURPOSE ONLY//
        $invoiceItems[] = $item;

//        dd($invoiceItems);

        $receiverOptions = new ReceiverOptions();
        $setPaymentOptionsRequest->receiverOptions[] = $receiverOptions;
        $receiverOptions->invoiceData = new InvoiceData(); // FOR GETTING INVOICE DATA
//        $receiverOptions->invoiceData->totalTax = 1;
//        $receiverOptions->invoiceData->totalShipping = $totalShipping['totalShipping'];

        $receiverOptions->invoiceData->item = $invoiceItems;
//dd($receiverOptions);

        $receiverId = new ReceiverIdentifier(); // FOR RECIEVER IDENTIFICATION AND IDENTIFIERS
        $receiverId->email = $email;
        $receiverOptions->receiver = $receiverId;

        $setPaymentOptionsRequest->payKey = $paykey;

        $service = new AdaptivePaymentsService($sdkConfig);
//dd($setPaymentOptionsRequest);
        try {
            // wrap API method calls on the service object with a try catch
            $response = $service->SetPaymentOptions($setPaymentOptionsRequest);

//            dd($response);
//            return redirect("https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay?paykey=$paykey");
            return redirect("https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=$paykey");

        } catch (Exception $ex) {
//                require_once 'Common/Error.php';
            echo "error";
            exit;
        }


    }

    /**
     * @param Request $request
     * @desc Test success response for paypal
     * @author Akash M. Pai
     */
    public function testPaypalSuccess(Request $request)
    {
        echo "success";
        dd($request);
    }

    /**
     * @param Request $request
     * @desc Test cancel response for paypal
     * @author Akash M. Pai
     */
    public function testPaypalCancel(Request $request)
    {
        echo "cancelled";
        dd($request);
    }

    /**
     * @param Request $request
     * @desc Test ipn response for paypal
     * @author Akash M. Pai
     */
    public function testPaypalIPN(Request $request)
    {
        echo "notify";
        dd($request);
    }


    /**
     * @desc Test paypal express
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function testPaypalExpress()
    {
//        $config1 = array(
//            // values: 'sandbox' for testing
//            //		   'live' for production
//
//            'log.LogEnabled' => true,
//            'log.FileName' => '../PayPal.log',
//            'log.LogLevel' => 'FINE'
//
//            // These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
//            // "http.ConnectionTimeOut" => "5000",
//            // "http.Retry" => "2",
//        );

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






