<?php
namespace FlashSale\Http\Modules\Admin\Controllers;

use FlashSale\Http\Modules\Admin\Models\AdminGiftCertificate;
use FlashSale\Http\Modules\Admin\Models\GiftCertificates;
use FlashSale\Http\Modules\Admin\Models\NewsletterLog;
use FlashSale\Http\Modules\Admin\Models\Newsletters;
use FlashSale\Http\Modules\Admin\Models\Notification;
use FlashSale\Http\Modules\Admin\Models\User;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Mail;
use PDO;
use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use SendGrid\Content;
use SendGrid\Email;

require  public_path().'/../vendor/autoload.php';

class NotificationController extends Controller
{

    /**
     * Users notification details
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function notificationDetails(Request $request)
    {

        $objNotificationModel = Notification::getInstance();
        $selectedColumn = ['notification.*', 'user.username as reciever', 'merchant.username as sender'];
        $notifyDetails = $objNotificationModel->getNotificationDetail($selectedColumn);
        return view('Admin/Views/notification/notification-details', ['notificationDetails' => $notifyDetails]);
    }

    /**
     * Send Notification to user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function sendUserNotification(Request $request)
    {

        $objUserModel = User::getInstance();
        $whereMerchant = ['rawQuery' => 'role = ?', 'bindParams' => [1]];
        $selectedColum = ['users.*'];
        $userDetail = $objUserModel->getUserDetails($whereMerchant, $selectedColum);
        return view('Admin/Views/notification/send-user-notification', ['userDetails' => $userDetail]);
    }

    /**
     * Send Notification to merchant
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function sendMerchantNotification(Request $request)
    {
        $objUserModel = User::getInstance();
        $whereMerchant = ['rawQuery' => 'role = ?', 'bindParams' => [3]];
        $selectedColum = ['users.*'];
        $merchantDetails = $objUserModel->getUserDetails($whereMerchant, $selectedColum);
        return view('Admin/Views/notification/send-merchant-notification', ['merchantDetails' => $merchantDetails]);
    }

    public function sendBuyerNotification(Request $request)
    {
        $objUserModel = User::getInstance();
        $whereMerchant = ['rawQuery' => 'role = ?', 'bindParams' => [2]];
        $selectedColum = ['users.*'];
        $buyerDetails = $objUserModel->getUserDetails($whereMerchant, $selectedColum);
        return view('Admin/Views/notification/send-buyer-notification', ['buyerDetails' => $buyerDetails]);
    }

    /**
     * Notification Ajax handler
     * @param Request $request
     * @return int
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function notificationAjaxHandler(Request $request)
    {

        $inputData = $request->all();
        $method = $request->input('method');
        $ObjNotificationModel = Notification::getInstance();
        if ($method != "") {
            switch ($method) {
                case'deletenotification':
                    $notificationId = $inputData['notificationId'];
                    $where = ['rawQuery' => 'notification_id = ?', 'bindParams' => $notificationId];
                    $notificationdel = $ObjNotificationModel->deletenotificationDetail($where);
                    if ($notificationdel) {
                        return $notificationdel;
                    } else {
                        echo "Error";
                    }
                    break;
                case 'sendUserNotification':
                    $message = $inputData['Message'];
                    $description = $inputData['Description'];
                    $userid = $inputData['UserID'];
                    if (isset($message) && isset($userid)) {
                        foreach ($userid as $uid) {
                            $status = true;
                            $data = array(
                                'send_to' => 0,
                                'type_to' => 'U',
                                'type_by' => 'A',
                                'send_by' => Session::get('fs_admin')['id'],
                                'message' => $message,
                                'description' => $description,
                                'notification_status' => 'U'
                            );
                            $status = $status && $ObjNotificationModel->AddNotification($data);
                        }
                        if ($status) {
                            echo 'Notification sent to selected subscribers.';
                        } else {
                            echo 'Sending failed PLease try again.';
                        }
                    }
                    break;
                case 'sendMerchantNotification':
                    $message = $inputData['Message'];
                    $description = $inputData['Description'];
                    $userid = $inputData['MerchantID'];
                    if (isset($message) && isset($userid)) {
                        foreach ($userid as $uid) {
                            $status = true;
                            $data = array(
                                'send_to' => $uid,
                                'type_to' => 'M',
                                'type_by' => 'A',
                                'send_by' => Session::get('fs_admin')['id'],
                                'message' => $message,
                                'description' => $description,
                                'notification_status' => 'U'
                            );
                            $status = $status && $ObjNotificationModel->AddNotification($data);
                        }
                        if ($status) {
                            echo 'Notification sent to selected subscribers.';
                        } else {
                            echo 'Sending failed PLease try again.';
                        }
                    }
                    break;
                case 'sendBuyerNotification':
                    $message = $inputData['Message'];
                    $description = $inputData['Description'];
                    $userid = $inputData['BuyerID'];
                    if (isset($message) && isset($userid)) {
                        foreach ($userid as $uid) {
                            $status = true;
                            $data = array(
                                'send_to' => $uid,
                                'type_to' => 'B',
                                'type_by' => 'A',
                                'send_by' => Session::get('fs_admin')['id'],
                                'message' => $message,
                                'description' => $description,
                                'notification_status' => 'U'
                            );
                            $status = $status && $ObjNotificationModel->AddNotification($data);
                        }
                        if ($status) {
                            echo 'Notification sent to selected subscribers.';
                        } else {
                            echo 'Sending failed PLease try again.';
                        }
                    }
                    break;
                case "getuserNotification":
                    $where = ['rawQuery' => 'send_by=? AND notification_status = ?', 'bindParams' => [0, 'U']];
                    $NotificationDetail = $ObjNotificationModel->getuserNotificationDetail($where);
                    $data = [];
                    if ($NotificationDetail == 0) {
                        $data[0] = 0;
                    } else {
                        $data[0] = count($NotificationDetail);
                    }
                    $data[1] = $NotificationDetail;
                    echo json_encode($data);
                    break;
                case "changenotificationstatus":
                    $notification = $inputData['NotificationId'];
                    $whereNotify = ['rawQuery' => 'notification_id = ?', 'bindParams' => [$notification]];
                    $changeStatus = ['notification_status' => 'S'];
                    $status = $ObjNotificationModel->updateNoftificationStatus($changeStatus, $whereNotify);
                    if ($status) {
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