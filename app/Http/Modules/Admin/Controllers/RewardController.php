<?php

namespace FlashSale\Http\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use FlashSale\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use TijsVerkoyen\CssToInlineStyles\Exception;
use Yajra\Datatables\Datatables;
//use Illuminate\Support\Facades\Validator;
use Validator;
//DATABASE MODELS START
use FlashSale\Http\Modules\Admin\Models\RewardSettings;
use FlashSale\Http\Modules\Admin\Models\Products;
use FlashSale\Http\Modules\Admin\Models\Rewards;

//DATABASE MODELS END


class RewardController extends Controller
{

    public function rewardSettings(Request $request)
    {
        $objModelRewardSettings = RewardSettings::getInstance();
        $objModelProducts = Products::getInstance();

        if ($request->isMethod('post')) {
            $requestData = $request->all();
            $returnData = ['data' => null, 'message' => 'Nothing to update.', 'code' => 100];
            $rules = array(
                'value' => 'required',
                'status' => 'required'
            );
            $messages = array(
                'value.required' => 'No changes made.'
            );
            $validator = Validator::make($requestData, $rules, $messages);
            if ($validator->fails()) {
                $returnData['code'] = 100;
                $returnData['message'] = "Please correct the following errors.";
                return redirect()->action(
                    'Admin\Controllers\RewardController@rewardSettings'
                )->with($returnData)
                    ->withErrors($validator);
//                return Redirect::back()
//                    ->with(["status" => 'error', 'msg' => 'Please correct the following errors.'])
//                    ->withErrors($validator)
//                    ->withInput();
            } else {
//            dd($requestData);
                $valuesRS = $requestData['value'];
                $statusRS = $requestData['status'];
                $updated = true;
                DB::beginTransaction();
                for ($i = 1; $i <= 9; $i++) {
                    $whereForUpdateRS = ['rawQuery' => "rs_id = $i and rs_editable = 'E'"];
                    $dataForUpdateRS = array();
                    if (isset($valuesRS[$i])) {
                        $dataForUpdateRS['rs_points'] = $valuesRS[$i];
                    }
                    if ($i != 1) {
                        $dataForUpdateRS['rs_status'] = "I";
                        if (isset($statusRS[$i])) {
                            if ($statusRS[$i] == "on" || $i == 1) {
                                $dataForUpdateRS['rs_status'] = "A";
                            }
                        }
                    }
                    if (!empty($dataForUpdateRS)) {
                        $resultUpdateRS = json_decode($objModelRewardSettings->updateRSWhere($dataForUpdateRS, $whereForUpdateRS), true);
                        if ($resultUpdateRS['code'] != 200 && $resultUpdateRS['code'] != 100) {
                            $updated = false;
                            break;
                        }
                    }
                }
                if (!$updated) {
                    $returnData['code'] = 400;
                    $returnData['message'] = "Something went wrong. Please try again.";
                    DB::rollBack();
                } else {
                    $returnData['code'] = 200;
                    $returnData['message'] = "Changes saved successfully.";
                    DB::commit();
                }
                return redirect()->action(
                    'Admin\Controllers\RewardController@rewardSettings'
                )->with($returnData);
            }
        }

        $whereForRS = ['rawQuery' => 'rs_editable = "E"'];
        $rewardSettings = json_decode($objModelRewardSettings->getAllRSWhere($whereForRS), true);

//        $whereForRS = ['rawQuery' => '1'];
//        $resultProducts = json_decode($objModelProducts->getAllProductsWhere($whereForRS), true);

//        $objCurrencyModel = Application_Model_Currency::getInstance();
//        $currencies = $objCurrencyModel->getCurrencyList();
//        $currencies = array();
//        $USDdet = $objCurrencyModel->getCurrencyByCCode("USD");
//        array_push($currencies, $USDdet);
//        $EURdet = $objCurrencyModel->getCurrencyByCCode("EUR");
//        array_push($currencies, $EURdet);
//        $GBPdet = $objCurrencyModel->getCurrencyByCCode("GBP");
//        array_push($currencies, $GBPdet);
//        $this->view->currencies = $currencies;

        return view("Admin/Views/reward/rewardSettings", ['rewardSettings' => $rewardSettings['data']]);//, 'productsData' => $resultProducts['data']]);
    }

    public function rewardsLog(Request $request, $orderId)
    {
        $dataForView = array('code' => 400, 'data' => null, 'message' => 'Invalid order id.');

        if (is_int((int)$orderId)) {

            $objModelOrder = Order::getInstance();
            $whereForOrder['rawQuery'] = "orders.order_id = ?";
            $whereForOrder['bindParams'] = [$orderId];
            $orderDetails = json_decode($objModelOrder->getOrderWhere($whereForOrder), true);
            $dataForView = $orderDetails;
//            dd($dataForView);
        }
        return view("Admin/Views/order/orderDetails", ['dataForView' => $dataForView]);
    }

    public function rewardSettingsAjaxHandler(Request $request)
    {
        $dataToReturn = ['data' => null, 'message' => "Invalid request", 'code' => 400];
        if ($request->isMethod('post')) {
            $requestData = $request->all();
            $method = $requestData['method'];
            $objModelProducts = Products::getInstance();
            switch ($method) {
                case "updateRewardPoints":
                    $rules = ['productId' => 'required|integer', 'rewardPoints' => 'required|integer'];
                    $validator = Validator::make($requestData, $rules);
                    if ($validator->fails()) {
                        $dataToReturn['code'] = 100;
                        $dataToReturn['message'] = "Please provide all necessary parameters and in valid format.";
                        $dataToReturn['data'] = null;
                    } else {
                        $dataForUpdProd = ['reward_points' => $requestData['rewardPoints']];
                        $whereForUpdProd = ['rawQuery' => 'product_id = ?', 'bindParams' => [$requestData['productId']]];
                        $resUpdatedProd = json_decode($objModelProducts->updateProductsWhere($dataForUpdProd, $whereForUpdProd), true);
                        $dataToReturn = $resUpdatedProd;
                    }
                    break;

                default:

                    break;

            }

        }
        return json_encode($dataToReturn);

    }


}


