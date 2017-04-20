<?php

namespace FlashSale\Http\Modules\Home\Controllers;

use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

use FlashSale\Http\Modules\Admin\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Curl\CurlRequestHandler;
use Illuminate\Support\Facades\URL;


class NotificationController extends Controller
{

    /**
     * Notification handler
     * getUserNotification method for all user notification common notification to all users by admin
     * changenotificationstatus method for chnage status to seen notification by users
     * @param Request $request
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function notificationAjaxHandler(Request $request)
    {


        $inputData = $request->all();
        $method = $request->input('method');
        $api_url = Session::get("domainname") . env("API_URL");
        $API_TOKEN = env('API_TOKEN');
        $objCurlHandler = CurlRequestHandler::getInstance();
        $user_id = '';
        if (isset(Session::get('fs_user')['id'])) {
            $user_id = Session::get('fs_user')['id'];
        }
        if ($method != "") {
            switch ($method) {
                case 'getUserNotification':
                    $data = array('id' => $user_id, 'api_token' => $API_TOKEN, 'method' => 'get-user-notification');
                    $url = $api_url . '/user/notification-handler';
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                    echo json_encode($curlResponse->data);
                    break;
                case "changenotificationstatus":
                    $notification = $inputData['NotificationId'];
                    $data = array('id' => $user_id, 'api_token' => $API_TOKEN, 'NotificationId' => $notification, 'method' => 'change-notification-status');
                    $url = $api_url . '/user/notification-handler';
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                    print_a($curlResponse);
                    if ($curlResponse->code == 200) {
                        echo json_encode(['status' => 'success', 'msg' => 'Notification Status Updated to Seen For User.']);
                    } else {
                        echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }
                    break;
                default:
                    break;


            }
        }

    }

}