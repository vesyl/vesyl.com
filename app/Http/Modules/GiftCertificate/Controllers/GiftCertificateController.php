<?php

namespace FlashSale\Http\Modules\GiftCertificate\Controllers;

use FlashSale\Http\Controllers\Controller;
use FlashSale\Http\Modules\Admin\Models\GiftCertificates;
use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GiftCertificateController extends Controller
{


    /**
     * Get all admin giftcertificate
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function getAdminGiftCertificates(Request $request)
    {

        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $url = Session::get("domainname") . env("API_URL") . '/' . "order/giftcertificate-list";
        $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id']];
        $giftcertificate = $objCurl->curlUsingPost($url, $data);
        return view('GiftCertificate.Views.giftcertificate.giftcertificate', ['giftcertificate' => $giftcertificate->data]);
    }


    /**
     * Gift certificate ajax handler
     * Giftcertificateuserinfo ,method
     * Checkforgiftcertificate
     * Check for already exixting gift
     * Reload balanace
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function giftcertificateAjaxHandler(Request $request)
    {

        $inputData = $request->input();
        $method = $inputData['method'];
        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        switch ($method) {
            case 'giftCertificateUserInfo':
                $optionsRadios = $inputData['optionsRadios'];
                $receiverEmail = $inputData['email'];
                $message = $inputData['message'];
                $url = Session::get("domainname") . env("API_URL") . '/' . "order/giftcertificate-handler";
                $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id'], 'admin-gift-id' => $optionsRadios, 'email' => $receiverEmail, 'message' => $message, 'method' => 'insert-gift-certificate'];
                $giftCertificateData = $objCurl->curlUsingPost($url, $data);
                echo json_encode($giftCertificateData);

                break;
            case 'checkForGiftCertificate':
                $Email = $inputData['email'];
                $url = Session::get("domainname") . env("API_URL") . '/' . "order/giftcertificate-handler";
                $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id'], 'email' => $Email, 'method' => 'check-validmail-id'];
                $checkforValidGift = $objCurl->curlUsingPost($url, $data);
                if ($checkforValidGift->code == '200') {
                    echo "true";
                } else {
                    echo "false";
                }
                break;
            case 'checkForGiftCode':
                $redeemCode = $inputData['redeemcode'];
                $url = Session::get("domainname") . env("API_URL") . '/' . "order/giftcertificate-handler";
                $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id'], 'redeem-code' => $redeemCode, 'method' => 'check-for-gift-code'];
                $Checkcode = $objCurl->curlUsingPost($url, $data);
                if ($Checkcode->code == '200') {
                    echo "true";
                } else {
                    echo "false";
                }

                break;
            case'checkForAlreadyexistingcode':
                $claimCode = $inputData['claimcode'];
                $url = Session::get("domainname") . env("API_URL") . '/' . "order/redeem-gift-certificate";
                $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id'], 'redeem-code' => $claimCode];
                $CheckExistingCode = $objCurl->curlUsingPost($url, $data);
                if ($CheckExistingCode->code == '200') {
                    echo 1;
                } else {
                    echo 0;
                }
                break;
            case 'reloadBalance':
                $url = Session::get("domainname") . env("API_URL") . '/' . "order/giftcertificate-handler";
                $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id'], 'method' => 'reload-balance'];
                $userBalance = $objCurl->curlUsingPost($url, $data);
                echo json_encode($userBalance->data['wallet']);
                break;
            default:
                break;
        }

    }

    /**
     * Redeem gift certificate action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function redeemGiftCertificate(Request $request)
    {

        $postData = $request->all();
        $objCurl = CurlRequestHandler::getInstance();
        $mytoken = env("API_TOKEN");
        $redeemCode = 'UmliYm9r';
        $url = Session::get("domainname") . env("API_URL") . '/' . "order/redeem-gift-certificate";
        $data = ['api_token' => $mytoken, 'id' => Session::get('fs_user')['id'], 'redeem-code' => $redeemCode];
        $giftcertificate = $objCurl->curlUsingPost($url, $data);
        return view('GiftCertificate.Views.giftcertificate.redeem-giftcertificate');
    }
}