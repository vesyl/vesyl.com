<?php

namespace FlashSale\Http\Modules\Campaign\Controllers;


use Illuminate\Http\Request;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Curl\CurlRequestHandler;
use DB;

//DB models start
use FlashSale\Http\Modules\Campaign\Models\Campaign;
use FlashSale\Http\Modules\Campaign\Models\Product;
use FlashSale\Http\Modules\Campaign\Models\ProductCategory;

//DB models end

//test destroy session by start
use Symfony\Component\HttpFoundation\Session\Storage\Handler\WriteCheckSessionHandler;
use SessionHandlerInterface;

use File;

//test destroy session by end

class WholesaleController extends Controller
{

    public function wholesaleList(Request $request, $page)
    {
        //todo test destroy session by id start
        //test 1 start
//        echo Session::getId();
////        $objWriteCheckSessionHandler = new WriteCheckSessionHandler(new \SessionHandler());
////        try{
////            $objWriteCheckSessionHandler->destroy('70e1a227f0dd37d8f550cc11d1aec2da6fc821af');
////        } catch(\Exception $e){
////            dd($e->getMessage());
////        }
//        $obj = SessionHandlerInterface::class;
//        try {
//            $obj->destroy('70e1a227f0dd37d8f550cc11d1aec2da6fc821af'zz);
//        } catch (\Exception $e) {
//            dd($e->getMessage());
//        }
//        dd("done");
//        70e1a227f0dd37d8f550cc11d1aec2da6fc821af
        //test 1 end

        //test 2 start
//        $session_id_to_destroy = '6q0pj8aa5a2a096r2bvs669mv2';
//        echo Session::getId() ."<br>";
//// 1. commit session if it's started.
//        if (session_id()) {
//            session_commit();
//        }
//// 2. store current session id
//        session_start();
//        $current_session_id = session_id();
//        echo $current_session_id;
//        session_commit();
//// 3. hijack then destroy session specified.
//        session_id($session_id_to_destroy);
//        session_start();
//        session_destroy();
//        session_commit();
//// 4. restore current session id. If don't restore it, your current session will refer     to the session you just destroyed!
//        session_id($current_session_id);
//        session_start();
//        session_commit();
        //test2 end

        //test3 start WORKING
        //Store session id while login by using Session::getId()
        //for deleting while click inactive use below block
//        try {
//            File::get(storage_path() . "/framework/sessions/70e1a227f0dd37d8f550cc11d1aec2da6fc821af");
//            File::delete(storage_path() . "/framework/sessions/70e1a227f0dd37d8f550cc11d1aec2da6fc821af");
//            //remove session id of that user in db 'users' table
//        } catch (\Exception $e) {
//            $dataForReturn['message'] = "Could not change status of user";
//        }
//        dd("Ad");
        //test3 end

        //test destroy session by id end

        $objModelCampaigns = Campaign::getInstance();
        $whereForCampaigns = ['rawQuery' => 'campaign_type = "3"'];
        $resWholesaleList = json_decode($objModelCampaigns->getAllCampaignsWhere($whereForCampaigns), true);
//        dd($resWholesaleList['data']);
        return view('Campaign.Views.wholesale.wholesaleList', ['wholesaleList' => $resWholesaleList]);

    }

    public function wholesaleProductsList(Request $request, $wholesaleId)
    {
        $dataForView = ['code' => 400, 'message' => 'Invalid wholesale request.', 'data' => null];
        if (is_int((int)$wholesaleId) && !($wholesaleId <= 0)) {

            $objModelCampaign = Campaign::getInstance();
            $objModelProdCat = ProductCategory::getInstance();

            $whereForCampaign = ['rawQuery' => 'campaign_id = ?', 'bindParams' => [$wholesaleId]];
            $resWS = json_decode($objModelCampaign->getCampaignWhere($whereForCampaign), true);
            $dataForView['wholesaleDetails'] = $resWS;


            if ($resWS['code'] == 200) {

                $whereForCats = ['rawQuery' => 'category_status = "1" and (for_shop_id = 0)'];// or for_shop_id = ? todo
                $allCategories = json_decode($objModelProdCat->getAllCategoriesWhere($whereForCats), true);
                foreach ($allCategories['data'] as $key => $value) {
                    $resCatDispName = explode(".", $this->getCategoryDisplayName($value['category_id'], 0));
                    $allCategories['data'][$key]['display_name'] = $resCatDispName[0];
                    $allCategories['data'][$key]['cat_level'] = $resCatDispName[1];
                }
                $dataForView['allCategories'] = $allCategories;
//                dd($allCategories['data']);


                $objModelProducts = Product::getInstance();
                $whereForWSProds = ['rawQuery' => '1'];
                if ($request->isMethod('post')) {
                    $postData = $request->all();

                    if (isset($postData['categoryId'])) {

                    }
                    $whereForWSProds = ['rawQuery' => '1'];


                }
//                $resWSProds = json_decode($objModelProducts->getAllProductsWhere($whereForWSProds), true);
//                $dataForView['productsList'] = $resWSProds;
//                $dataForView['code'] = $resWSProds['code'];
            }
        }
        return view('Campaign.Views.wholesale.productsList', $dataForView);
    }


    private function getCategoryDisplayName($id, $level)
    {
        if ($id == 0) {
            return '.0';
        } else {
            $objModelProdCat = ProductCategory::getInstance();
            $whereForCat = ['rawQuery' => 'category_id = ?', 'bindParams' => [$id]];
            $parentCategory = json_decode($objModelProdCat->getCategoryWhere($whereForCat), true);
            if ($parentCategory['data']['parent_category_id'] != 0) {
                $temp = explode(".", $this->getCategoryDisplayName($parentCategory['data']['parent_category_id'], $level + 1));
                return $temp[0] . '&brvbar;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.' . ($temp[1] + 1);
            } else {
                return '.0';
            }
        }
    }

    /**todo function copied from VINI!!!! Need to modify. KARMA. Modify ALL services if time available. Gubald coding.
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productDetails(Request $request, $productId)
    {
//        $productId = $request->input('prodId');
        $objCurl = CurlRequestHandler::getInstance();
        $url = Session::get("domainname") . env("API_URL") . '/' . "product-popup";
        $mytoken = env("API_TOKEN");
        $user_id = '';
        if (Session::has('fs_customer')) {
            $user_id = Session::get('fs_customer')['id'];
        }
        $data = array('api_token' => $mytoken, 'id' => $user_id, 'product_id' => $productId);
        $curlResponse = $objCurl->curlUsingPost($url, $data);
//        dd($curlResponse->data[0]);
        if ($curlResponse->code == 200) {
            return view('Campaign.Views.wholesale.productDetails', ['productdetails' => $curlResponse->data]);
        }
    }


}