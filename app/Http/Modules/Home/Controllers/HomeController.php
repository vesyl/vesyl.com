<?php

namespace FlashSale\Http\Modules\Home\Controllers;

use FlashSale\Http\Modules\Home\Models\Pages;
use FlashSale\Http\Modules\Supplier\Models\Shop;
//use FlashSaleApi\Http\Models\Campaigns;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Hash;
use DB;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Curl\CurlRequestHandler;

//use Illuminate\Support\Facades\URL;


class HomeController extends Controller
{
//    public function __call(){
//
//    }


    /**
     * @Get Campaign Details
     * Get User Locale
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {


        $url = $_SERVER['HTTP_HOST'];

        $objCurl = CurlRequestHandler::getInstance();
//        dd("Asd");
//        dd(Session::get("domainname"));
        $url = Session::get("domainname") . env("API_URL") . '/' . "flashsale-details";

        $mytoken = env("API_TOKEN");
        $user_id = '';
        if (Session::has('fs_customer')) {
            $user_id = Session::get('fs_customer')['id'];
        }
        $data = array('api_token' => $mytoken, 'id' => $user_id);

        $curlResponse = $objCurl->curlUsingPost($url, $data);
//        dd($curlResponse);

//        print_a($curlResponse);
        $bannerData = ['code' => 300];
        if ($curlResponse->code == 200) {
            return view('Home/Views/home', ['flashsaledetails' => $curlResponse->data, 'locale' => \Session::get('user_locale'), 'bannerData' => $bannerData]);
        }

        return view("Home/Views/home", ['locale' => \Session::get('user_locale'), 'bannerData' => $bannerData]);
    }

    public function homeAjaxHandler(Request $request)
    {
        $urlmain = $_SERVER['HTTP_HOST'];
        $domain = env("site_domain");
        $method = $request->input('method');
        $api_url = Session::get("domainname") . env("API_URL");
        $API_TOKEN = env('API_TOKEN');
        $shopname = null;

        $shopobject = Shop::getInstance();

        if ($urlmain != $domain) {

            $shopurl = explode("." . $domain, $urlmain);
            $shopname = $shopurl[0];

//            $checkShop  = $shopobject->checkShop($shopname);
//            $decodeCheckShop = json_decode($checkShop,true);
//
//
//            if($decodeCheckShop["code"]!=200)
//            {
//                return redirect($urlmain);
//            }

        }
        $objCurlHandler = CurlRequestHandler::getInstance();
        if ($method) {
            switch ($method) {
                case "user_signup":
                    $data['first_name'] = trim($request->input('fname'));
                    $data['last_name'] = trim($request->input('lname'));
                    $data['username'] = trim($request->input('uname'));
                    $data['email'] = trim($request->input('email'));
                    $data['gender'] = $request->input('optradio');
                    $data['phone'] = $request->input('contact_no');
                    $data['date_of_birth'] = $request->input('date_of_birth');
                    $data['for_shop_name'] = $shopname;
                    $data['api_token'] = $API_TOKEN;
                    $url = $api_url . "/signup";

                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//                    echo "<pre>";print_r($curlResponse);die;
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse->code);
                        die();
                    } else {
                        echo json_encode($curlResponse);
                        die();
                    }
                    break;
                case "user_login":
                    $data['username'] = trim($request->input('uname'));
                    $data['password'] = trim($request->input('password'));
                    $data['api_token'] = $API_TOKEN;
                    $url = $api_url . "/login";
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

                    $finalResponse = [];

                    if ($curlResponse->code == 200) {
                        $sessionName = 'fs_user';
                        Session::put($sessionName, $curlResponse->data);
//                        var_dump($_COOKIE['cart_cookie_name']);
                        if (isset($_COOKIE['cart_cookie_name']) && !empty(json_decode($_COOKIE['cart_cookie_name']))) {
                            $cartCookie = json_decode($_COOKIE['cart_cookie_name']);//todo error here Ahole Vini again
                            foreach ($cartCookie as $key => $value) {
                                $combinationId = implode("#", array_filter(array_unique(explode("@", $value->combination_id))));
                                $productId = $value->prodId;
                                $cartBind[$key]['quantity'] = $value->quantity;
                                $cartBind[$key]['product_id'] = $productId . '-' . $value->combination_id;

                            }
                            $user_id = Session::get('fs_user')['id'];
                            $finalDataToInsert = array_map(function ($values) use ($user_id) {
                                $temp['quantity'] = $values['quantity'];
                                $temp['product_id'] = $values['product_id'];
                                $temp['for_user_id'] = $user_id;

                                return $temp;

                            }, $cartBind);
                            $url = Session::get("domainname") . env("API_URL") . "/order-ajax-handler";
                            $mytoken = env("API_TOKEN");
                            $data = array('api_token' => $mytoken, 'method' => 'insert-order', 'id' => $user_id, 'mainCartData' => json_encode($finalDataToInsert));
                            $curlResponseForCart = $objCurlHandler->curlUsingPost($url, $data);

                            if ($curlResponseForCart->code == 200) {
                                setcookie('cart_cookie_name', null, -1, '/');
                                echo json_encode($curlResponseForCart && $curlResponse->code);
                            } else {
                                echo json_encode($curlResponse->code);
                            }
                        } else {
                            echo json_encode($curlResponse->code);
                        }
                    } else {
                        echo json_encode($curlResponse);
                    }
                    break;
                case "forgotpw":
                    $fpwemail = trim($request->input('fpwemail'));
                    $data['api_token'] = $API_TOKEN;
                    $data['fpwemail'] = $fpwemail;
                    $data['method'] = "EnterEmailId";
                    $url = $api_url . "/forgot-password";
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse);
                    } else {
                        echo json_encode($curlResponse);
                    }

                    break;
                case "verifyResetCode":
                    $fpwemail = trim($request->input('fpwemail'));
                    $resetcode = trim($request->input('resetcode'));
                    $data['api_token'] = $API_TOKEN;
                    $data['fpwemail'] = $fpwemail;
                    $data['resetcode'] = $resetcode;
                    $data['method'] = "verifyResetCode";
                    $url = $api_url . "/forgot-password";
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse);
                    } else {
                        echo json_encode($curlResponse);
                    }

                    break;
                case "resetPassword":
                    $fpwemail = trim($request->input('fpwemail'));
                    $resetcode = trim($request->input('reset_code'));
                    $password = trim($request->input('password'));
                    $re_password = trim($request->input('re_password'));
                    $data['api_token'] = $API_TOKEN;
                    $data['fpwemail'] = $fpwemail;
                    $data['resetcode'] = $resetcode;
                    $data['password'] = $password;
                    $data['re_password'] = $re_password;
                    $data['method'] = "resetPassword";
                    $url = $api_url . "/forgot-password";
                    $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                    if ($curlResponse->code == 200) {
                        echo json_encode($curlResponse);
                    } else {
                        echo json_encode($curlResponse);
                    }

                    break;
                default:
                    break;
            }
        } else {
            echo 0;
            die();
        }
    }

    public function logout()
    {
        Session::forget('fs_user');
        return redirect('/');
    }

    /**
     * Changes the current language and returns to previous page
     * @return Redirect
     */
    public function changeLang(Request $request, $locale = null)
    {
//        Session::put('locale', $locale);
//        return Redirect::to(URL::previous());
    }


    /**
     * Get Translated Language Values
     * @return mixed
     */
    public static function getTranslatedLanguage()
    {

        $api_url = Session::get("domainname") . env("API_URL");
        $API_TOKEN = env('API_TOKEN');
        $objCurlHandler = CurlRequestHandler::getInstance();
        $url = $api_url . "/language-translate";
        $data['user_id'] = Session::get('fs_user')['id'];
        $data['api_token'] = $API_TOKEN;
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        return $curlResponse->data;

    }

    /**
     * Get Categories For Header Menus
     * @return null
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public static function getCategoriesForMenu()
    {

        $api_url = Session::get("domainname") . env("API_URL");
        $API_TOKEN = env('API_TOKEN');
        $objCurlHandler = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . "/campaign/product-ajax-handler";
        $mytoken = env("API_TOKEN");
        $data = array('api_token' => $mytoken, 'method' => 'getCategoryForMenu');
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

//        dd($curlResponse);
        if ($curlResponse->code == 200) {
            return $curlResponse->data;
        } else {
            return null;
        }
    }

    /**
     * Get Campaigns For Header Menus
     * @return null
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public static function getCampaignsForMenu()
    {

        $objCurlHandler = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . "/flashsale-ajax-handler";
        $mytoken = env("API_TOKEN");
        $data = array('api_token' => $mytoken, 'method' => 'getCampaignsForMenu');
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        if ($curlResponse->code == 200) {
            return $curlResponse->data;
        } else {
            return null;
        }

    }

    /**
     * Get Cart Count
     * @return mixed
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public static function getCartCount()
    {

        $objCurlHandler = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . "/order-ajax-handler";
        $data = array('id' => Session::get('fs_user')['id'], 'api_token' => env('API_TOKEN'), 'method' => 'user-cart-details');
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//        return $curlResponse->data[0]['cartcount'];
        if ($curlResponse->code == 200) {
            return $curlResponse->data;
        } else {
            return null;
        }

    }

    public function pages(Request $request, $pagename = '')
    {

        if ($pagename != '') {
            $objExtrapagesModel = Pages::getInstance();
            $where = [
                'rawQuery' => 'pages_extra.page_title=?',
                'bindParams' => [$pagename]
            ];
            $selectedColumns = array('pages_extra.*');
            $pageDetail = $objExtrapagesModel->getAllPDwhere($where, $selectedColumns);
            $returnData['pageDetail'] = json_decode($pageDetail);
            if ($returnData['pageDetail']->code == 400) {
                return redirect()->action('Home\Controllers\HomeController@home');

            }
            $selectedColumns = array('pages_extra.page_title', 'pages_extra.page_content_url');
            $pageTitles = $objExtrapagesModel->getAllPTwhere($selectedColumns);
            $returnData['pageTitles'] = json_decode($pageTitles);
            $details = $returnData['pageTitles']->data;
            $page = $returnData['pageDetail']->data->page_content_url;
            $destinationPath = $page;
            $file = File::get(storage_path() . $destinationPath, true);
            //make page title unique in DB
            return view("Home/Views/pagesExtra", ['returnData' => $returnData, 'file' => $file]);
        } else {
            return redirect()->action('Home\Controllers\HomeController@home');
        }
    }

}