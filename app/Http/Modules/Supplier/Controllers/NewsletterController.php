<?php
namespace FlashSale\Http\Modules\Supplier\Controllers;

use FlashSale\Http\Modules\Supplier\Models\NewsletterLog;
use FlashSale\Http\Modules\Supplier\Models\Newsletters;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
//use Illuminate\Support\Facades\Mail;
use PDO;
use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use SendGrid\Content;
use SendGrid\Email;
require public_path().'/../vendor/autoload.php';

class NewsletterController extends Controller
{
    public function addNewsletter(Request $request)
    {

        $postData = $request->all();
        $objNewsletterLogModel = NewsletterLog::getInstance();
        if ($request->isMethod('post')) {

            $postData = $request->all();
            $rules = array(
                'subject' => 'required',
                'description' => 'required|min:30',
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $data = array(
                    'newsletter_log_subject' => $postData['subject'],
                    'content' => $postData['description']
                );
                $collection = $objNewsletterLogModel->addNewsletter($data);
                if ($collection) {
                    return Redirect::back()->with(['status' => 'success', 'msg' => 'Newsletter Added Successfully.']);
                } else {
                    return Redirect::back()->with(['status' => 'error', 'msg' => 'Some Error try again.']);
                }
            }
        }

        return view('Supplier/Views/newsletter/add-newsletter');
    }

    public function sendNewsletter(Request $request)
    {
        $objNewsletterModel = Newsletters::getInstance();
        $selectedColumns = ['newsletters.*'];
        $subscriberDetail = $objNewsletterModel->getSubscriberDetail();

        $objNewsletterlogModel = NewsletterLog::getInstance();
        $selectedColumns = ['newsletter_log.*'];
        $NewsletterDetail = $objNewsletterlogModel->getNewsletterDetail();

        return view('Supplier/Views/newsletter/send-newsletter', ['subscriberDetail' => $subscriberDetail, 'newsletterDetail' => $NewsletterDetail]);
    }

    public function subscriberDetails(Request $request)
    {
        $objNewsletterModel = Newsletters::getInstance();
        $selectedColumns = ['newsletters.*'];
        $subscriberDetail = $objNewsletterModel->getSubscriberDetail($selectedColumns);
        return view('Supplier/Views/newsletter/subscriber-details', ['subscriberDetail' => $subscriberDetail]);
    }

    public function newsletterAjaxHandler(Request $request)
    {


        $method = $request->input('method');
        $email = $request->input('emailobj');
        $msg = $request->input('contentofMail');
        $ObjNewslettersModel = Newsletters::getInstance();
        if ($method != "") {
            switch ($method) {
                case 'changeNewsletterStatus':
                    $newsletterId = $request->input('newsletterId');
                    $status = $request->input('newsletterStatus');
                    $wheregiftId = ['rawQuery' => 'news_id =?', 'bindParams' => [$newsletterId]];
                    $data['sub_status'] = $status;
                    $giftUpdate = $ObjNewslettersModel->updateNewsletterStatus($data, $wheregiftId);
                    $giftdata['status'] = $status;
                    $giftdata['update'] = $giftUpdate;
                    if ($giftdata) {
                        echo json_encode(['status' => 'success', 'msg' => 'Status has been changed.']);
                    } else {
                        echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }
                    break;
                case "sendnewsletter":

                    $from = new \SendGrid\Email(null, "support@flashsale.com");
                    $subject = "Flashsale Newsletter";
                    foreach ($email as $val) {

                        $to = new  \SendGrid\Email(null, $val);
                        $content = new \SendGrid\Content("text/plain", $msg);
                        $mail = new  \SendGrid\Mail($from, $subject, $to, $content);

                        $apiKey = env('SENDGRID_API_KEY');
                        $sg = new \SendGrid($apiKey);

                        $response = $sg->client->mail()->send()->post($mail);
                        if ($response->statusCode() == 202) {
                            echo json_encode(['status' => 200]);
                        }
                    }
            }
        }
    }
}