<?php

namespace FlashSaleApi\Http\Controllers\Order;


use FlashSaleApi\Http\Controllers\Controller;
use FlashSaleApi\Http\Models\AdminGiftCertificate;
use FlashSaleApi\Http\Models\GiftCertificates;
use FlashSaleApi\Http\Models\Orders;
use FlashSaleApi\Http\Models\User;
use Illuminate\Http\Request;

use DB;
use FlashSaleApi\Http\Models\Transactions;

//paypal start
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;

use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\Service\PayPalAPIInterfaceServiceService;

//paypal end

class GiftCertificateController extends Controller
{

    /**
     * Get all giftcertificate lists added by admin
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function giftCertificateList(Request $request)
    {
        $postData = $request->all();
        $response = new \stdClass();
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

            if ($authflag) {
                $objAdminGiftCertificateModel = AdminGiftCertificate::getInstance();
                $adminCertificateDetails = $objAdminGiftCertificateModel->getAllAdminGiftCertificate();
                if ($adminCertificateDetails) {
                    $response->code = 200;
                    $response->message = "Success";
                    $response->data = $adminCertificateDetails;
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
    }

    /**
     * Gift certificate handler
     * check-validmail-id method for checking whether mail id is valid for particular user or not
     * insert-gift-certificate method for inserting the gift records for users.
     * check-for-gift-code method for checking gift code
     * reload-balance methid for reload user actual balance
     * @param Request $request
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function giftcertificateHandler(Request $request)
    {
        $method = $request->input('method');
        $response = new \stdClass();
        $objUserModel = new User();
        $postData = $request->all();
        if ($method != "") {
            switch ($method) {
                case 'check-validmail-id':
                    if ($postData) {
                        $userId = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }
                        $emailId = '';
                        if (isset($postData['email'])) {
                            $emailId = $postData['email'];
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
                            $where = ['rawQuery' => 'email = ?', 'bindParams' => [$emailId]];
                            $checkcat = $objUserModel->getUsercredsWhere($where);
                            if ($checkcat) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $checkcat;
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

                case 'insert-gift-certificate':
                    $postData = $request->all();
                    $response = new \stdClass();
                    $objUserModel = new User();
                    if ($postData) {
                        $userId = '';
                        $adminGiftId = '';
                        $email = '';
                        $message = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }
                        if (isset($postData['admin-gift-id'])) {
                            $adminGiftId = $postData['admin-gift-id'];
                        }
                        if (isset($postData['email'])) {
                            $email = $postData['email'];
                        }
                        if (isset($postData['message'])) {
                            $message = $postData['message'];
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
                        if ($authflag) {
                            $objAdminGiftCertificateModel = AdminGiftCertificate::getInstance();
                            $whereAdminGiftCertificate = ['rawQuery' => 'gift_id = ?', 'bindParams' => [$adminGiftId]];
                            $adminGiftCertificate = $objAdminGiftCertificateModel->getAdminGiftCertificateById($whereAdminGiftCertificate);
                            $whereEmail = [
                                'rawQuery' => 'email =?',
                                'bindParams' => [$email]
                            ];
                            $usersEmail = $objUserModel->getUsercredsWhere($whereEmail);
                            $dataArray = array('gift_id' => $adminGiftCertificate->gift_id,
                                'gift_amount' => $adminGiftCertificate->gift_amount,
                                'gift_name' => $adminGiftCertificate->gift_name,
                                'gift_code' => $adminGiftCertificate->gift_code,
                                'gift_message' => $message,
                                'gift_by' => $userId,
                                'gift_for' => $usersEmail->id);
                            $objGiftModel = GiftCertificates::getInstance();
                            $insertUserGiftCertificate = $objGiftModel->insertToGiftCertificate($dataArray);
                            if ($insertUserGiftCertificate) {
                                $dataArray['gc_id'] = $insertUserGiftCertificate;
                                $resOrderGC = $this->buyGiftCert($dataArray);

                                $response->code = $resOrderGC['code'];
                                $response->message = $resOrderGC['message'];//"Success";
                                $response->data = $resOrderGC['data'];
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

                case'check-for-gift-code':
                    if ($postData) {
                        $userId = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }
                        $redeemCode = '';
                        if (isset($postData['redeem-code'])) {
                            $redeemCode = $postData['redeem-code'];
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
                        if ($authflag) {
                            $objGiftCertificateModel = GiftCertificates::getInstance();
                            $where = ['rawQuery' => 'gift_for = ? AND gift_code = ?', 'bindParams' => [$userId, $redeemCode]];
                            $checkcat = $objGiftCertificateModel->checkForGiftCode($where);
                            if ($checkcat) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $checkcat;
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

                case 'reload-balance':
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
                        if ($authflag) {
                            $whereForloginToken = $whereForUpdate = [
                                'rawQuery' => 'id =?',
                                'bindParams' => [$userId]
                            ];
                            $userWalletDetail = $objUserModel->getUsercredsWhere($whereForloginToken);
                            if ($userWalletDetail) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $userWalletDetail;
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

                default:
                    break;

            }
        }

    }

    public function redeemGiftCertificate(Request $request)
    {

        $ObjGiftCertificateModel = GiftCertificates::getInstance();
        $postData = $request->all();
        $response = new \stdClass();
        $objUserModel = new User();
        if ($postData) {
            $userId = '';
            if (isset($postData['id'])) {
                $userId = $postData['id'];
            }
            if (isset($postData['redeem-code'])) {
                $redeemCode = $postData['redeem-code'];
            }
            $mytoken = '';
            $authflag = false;
            if (isset($postData['api_token'])) {
                $mytoken = $postData['api_token'];

                if ($mytoken == env("API_TOKEN")) {
                    $authflag = true;
                } else {
                    if ($userId != '') {
                        $whereForloginToken = [
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
            if ($authflag) {
                $whereCode = ['rawQuery' => 'gift_for = ? AND gift_code = ? AND gift_status = ? AND redeem_status = ?', 'bindParams' => [$userId, $redeemCode, 'S', 'N']];
                $Checkcode = $ObjGiftCertificateModel->checkForGiftCode($whereCode);
                if ($Checkcode) {
                    $whereUserId = ['rawQuery' => 'gift_certificate_id = ? AND gift_code = ?', 'bindParams' => [$Checkcode->gift_certificate_id, $redeemCode]];
                    $dataRedeem = array(
                        'redeem_status' => 'U',
                    );
                    $giftRedeemUpdate = $ObjGiftCertificateModel->updateRedeemStatus($dataRedeem, $whereUserId);
                    $whereUser = [
                        'rawQuery' => 'id =?',
                        'bindParams' => [$userId]
                    ];
                    $UserWallet = $objUserModel->getUsercredsWhere($whereUser);
                    $wallet = $UserWallet->wallet;
                    $AmountTotal = $Checkcode->gift_amount + $wallet;
                    $whereWallet = ['rawQuery' => 'id = ?', 'bindParams' => [$Checkcode->gift_for]];
                    $walletData = ['wallet' => $AmountTotal];
                    $UserData = $objUserModel->UpdateUserDetailsbyId($whereWallet, $walletData);
                    if ($UserData) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $UserData;
                    } else {
                        $response->code = 400;
                        $response->message = "No Details found.";
                        $response->data = null;
                    }
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

    private function buyGiftCert($giftcertDetails = array())
    {
        $returnObj = array('data' => null, 'message' => "Method not allowed.");
//        $giftcertDetails = [
//            "gift_id" => 3,
//            "gift_amount" => 23.0,
//            "gift_name" => "Ribbok",
//            "gift_code" => "UmliYm9r",
//            "gift_message" => "Test message",
//            "gift_by" => "81",
//            "gift_for" => 74,
//            "gc_id" => 11
//        ];

        if (!empty($giftcertDetails)) {
            $objModelOrders = Orders::getInstance();
            DB::beginTransaction();

            $dataForOrderInsert = ['for_user_id' => $giftcertDetails['gift_by'],
                'order_type' => 'G',
                'product_id' => $giftcertDetails['gc_id'],
                'product_details' => json_encode($giftcertDetails),
                'unit_price' => $giftcertDetails['gift_amount'],
                'quantity' => 1,
                'final_price' => $giftcertDetails['gift_amount'],
                'added_date' => time(),
                'order_status' => 'P'
            ];
            $resOrderInsert = json_decode($objModelOrders->insertOrder($dataForOrderInsert), true);
//            dd($resOrderInsert);
            if ($resOrderInsert['code'] == 200) {
                $currencyCode = "USD";//todo get default currency of user instead and also check if the currency is allowed by the specified payment method
                $dataForTransaction = array('tx_pmethod_id' => '1',//TODO [hardcoded] instead get id from payment_method table
                    'tx_type' => 'P',
                    'tx_unique_code' => time() . "-" . $giftcertDetails['gift_by'] . "-G-" . $resOrderInsert['data'],
//                            'payment_mode' => '',//updated in paypal response
                    'payment_details' => json_encode($giftcertDetails, true),
                    'user_details' => json_encode($giftcertDetails, true),
//                'shipping_addr' => json_encode($txShippingAddr, true),
//                'billing_addr' => json_encode($txBillingAddr, true),
                    //TODO below 3 lines to be updated ONLY if discount provided by paypal or payu etc coupon-codes/gift-certificates
                    'discount_by' => 0,
                    'discount_type' => null,
                    'discount_value' => 0,
                    'tx_amount' => $giftcertDetails['gift_amount'],
                    'walletbal_used' => 0,
                    'rewardpts_used' => 0,
                    'tx_date' => time(),
                    'tx_status' => 'P',
                );
                $objModelTransactions = Transactions::getInstance();
                $insertedTxResponse = json_decode($objModelTransactions->addTransaction($dataForTransaction), true);
                if ($insertedTxResponse['code'] == 200) {
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

//                $address = new AddressType();
//                $address->CityName = $finalCartProductDetails[0]->lc_city;
//                $address->Name = $finalCartProductDetails[0]->name;
//                $address->Street1 = $finalCartProductDetails[0]->addressline1;
//                $address->StateOrProvince = $finalCartProductDetails[0]->ls_state;
//                $address->PostalCode = $finalCartProductDetails[0]->zipcode;
//                $address->Country = $finalCartProductDetails[0]->ln_country;
//                $address->Phone = $finalCartProductDetails[0]->phone;//TODO phone country code
//                $shippingTotal = new BasicAmountType($currencyCode, 0);
//                                                    dd($shippingTotal);
                    $paymentDetails = new PaymentDetailsType();
                    $itemTotalValue = 0;
                    $taxTotalValue = 0;
                    $productArrayCount = 0;
                    $itemAmount = new BasicAmountType($currencyCode, round($giftcertDetails['gift_amount'], 2));//UNIT PRICE
                    $itemTotalValue = round($giftcertDetails['gift_amount'], 2);//round($fCPDVal->finalUnitPrice * $fCPDVal->quantity, 2);
//                                                $taxTotalValue += 3.00 * $fCPDVal['quantity'];
                    $itemDetails = new PaymentDetailsItemType();
                    $itemDetails->Name = $giftcertDetails['gift_name'];
                    $itemDetails->Amount = $giftcertDetails['gift_amount'];
                    $itemDetails->Quantity = 1;
//                $itemDetails->ItemCategory = 'Physical';
//                                                $itemDetails->Tax = new BasicAmountType($currencyCode, 3.00);//$_REQUEST['itemSalesTax'][$i]
//                                                        echo "price-----" . $fCPDVal->finalUnitPrice . "----quantity-----" . $fCPDVal->quantity . "----total-----" . $fCPDVal->finalUnitPrice * $fCPDVal->quantity . "<br>";
                    $paymentDetails->PaymentDetailsItem[0] = $itemDetails;

                    $orderTotalValue = $itemTotalValue;// + $shippingTotal->value;// + 0.01;//+ $taxTotalValue;// + + $handlingTotal->value + $insuranceTotal->value
//                                                    echo $orderTotalValue;
//                                                    echo "<br>";
//                $paymentDetails->ShipToAddress = $address;
                    $paymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
//                                        $paymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
                    $paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);
//                $paymentDetails->ShippingTotal = $shippingTotal;
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
                        // wrap API method calls on the service object with a try catch
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
                    if (isset($setECResponse)) {

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
            } else {
                $returnObj = [
                    'data' => null,
                    'message' => $resOrderInsert['message'],
                    'code' => 400
                ];
                $returnCode = 400;
            }
        }
        return $returnObj;
    }
}