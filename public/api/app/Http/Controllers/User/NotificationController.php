<?php
namespace FlashSaleApi\Http\Controllers\User;


use FlashSaleApi\Http\Controllers\Controller;
use FlashSaleApi\Http\Models\Notification;
use FlashSaleApi\Http\Models\User;
use Illuminate\Http\Request;

Class NotificationController extends Controller
{


    /**
     * Notification handler
     * @param Request $request
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function notificationHandler(Request $request)
    {

        $method = $request->input('method');
        $response = new \stdClass();
        $objUserModel = new User();
        $postData = $request->all();
        $ObjNotificationModel = Notification::getInstance();
        if ($method != "") {
            switch ($method) {
                case'get-user-notification':
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
                            $where = ['rawQuery' => 'send_by=? AND notification_status = ?', 'bindParams' => [$request->send_by, $request->notification_status]];
                            $NotificationDetail = $ObjNotificationModel->getuserNotificationDetail($where);
                            $data = [];
                            if ($NotificationDetail == 0) {
                                $data[0] = 0;
                            } else {
                                $data[0] = count($NotificationDetail);
                            }
                            $data[1] = $NotificationDetail;
                            if (isset($NotificationDetail)) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $NotificationDetail;
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
                case 'change-notification-status':
                    if ($postData) {
                        $userId = '';
                        if (isset($postData['id'])) {
                            $userId = $postData['id'];
                        }
                        $notification = '';
                        if (isset($postData['NotificationId'])) {
                            $notification = $postData['NotificationId'];
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
                            $whereNotify = ['rawQuery' => 'notification_id = ?', 'bindParams' => [$notification]];
                            $changeStatus = ['notification_status' => 'S'];
                            $status = $ObjNotificationModel->updateNoftificationStatus($changeStatus, $whereNotify);

                            if ($status) {
                                $response->code = 200;
                                $response->message = "Success";
                                $response->data = $status;
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

}