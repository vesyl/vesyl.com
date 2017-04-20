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

//DATABASE MODELS START
use FlashSale\Http\Modules\Admin\Models\Order;

//DATABASE MODELS END


class OrderController extends Controller
{

    public function manageOrders()
    {
        /*
        //TEST DATABASE TRANSACTION START
//        DB::beginTransaction();
        $data = array(
            'name' => 'FlashSale Admin',
            'username' => 'asdasdas',
            'email' => 'admin@flashsale.com',
            'password' => "ASd",
//                'added_date' => time(),
            'role' => "5",
            'status' => '1',
//            'asdsad' => ''
        );
        try {
            $res = DB::table('users')->insert($data);
            dd($res);
//            if ($resulta)
//            DB::commit();
        } catch (Exception $e) {
            dd($e->getMessage());
//            DB::rollBack();
        }
        dd("Asd");
        //TEST DATABASE TRANSACTION END
        */

        return view("Admin/Views/order/manageOrders");
    }

    public function orderDetails(Request $request, $orderId)
    {
        $dataForView = array('code' => 400, 'data' => null, 'message' => 'Invalid order id.');

        if (is_int((int)$orderId)) {

            $objModelOrder = Order::getInstance();
            $whereForOrder['rawQuery'] = "orders.order_id = ?";
            $whereForOrder['bindParams'] = [$orderId];
            $orderDetails = json_decode($objModelOrder->getOrderWhere($whereForOrder),true);
            $dataForView = $orderDetails;
//            dd($dataForView);
        }
        return view("Admin/Views/order/orderDetails", ['dataForView'=>$dataForView]);
    }

    public function orderAjaxHandler(Request $request)
    {
        $dataToReturn = ['data' => null, 'message' => "Invalid request", 'code' => 400];
        if ($request->isMethod('post')) {
            $requestData = $request->all();
            $method = $requestData['method'];
            switch ($method) {
                case "":

                    break;

                default:

                    break;

            }
            return json_encode($dataToReturn);
        } else {
            return json_encode($dataToReturn);
        }


    }

    public function orderDatatablesHandler(Request $request)
    {

        //        P=pending [in cart],
        //        TS=tx success [if not cod then show cancel button],
        //        TP=tx in process[if COD show cancel button],
        //        TF=tx failed,
        //        S=shipping [show cancel button],
        //        UC=user cancel request [show dispute button],
        //        UCA=user cancel approved,
        //        MC=merchant cancel,
        //        D=delivered [show dispute button, show refund button],
        //        RR=refund request,
        //        RP=refund in process,
        //        RD=refund done [show dispute button]

        if ($request->isMethod('POST') || $request->isMethod('GET')) {

            $requestData = $request->all();
            if (isset($requestData['tablename']) && !empty($requestData['tablename'])) {
                $tableName = $requestData['tablename'];

                if ($request->isMethod('POST')) {
                    $objModelOrders = Order::getInstance();
                    switch ($tableName) {
                        case  "orderstableadmin":

                            // NORMAL DATATABLE STARTS HERE//
                            $whereForOrders = ['rawQuery' => 'order_status != ?', 'bindParams' => ['P']];
//                    $selectedColumn = ['products.*', 'users.username', 'users.role', 'shops.shop_name', 'product_categories.category_name', 'product_images.image_url'];
                            $allOrdersData = json_decode($objModelOrders->getAllOrdersWhere($whereForOrders), true);
                            //NORMAL DATATABLE ENDS//

                            // FILTERING STARTS FROM HERE//
                            $filteringQuery = '';
                            $filteringBindParams = array();
                            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter' && $_REQUEST['action'][0] != 'filter_cancel') {
                                if ($_REQUEST['order_id'] != '') {
                                    $filteringQuery[] = "(`orders`.`order_id` = ?)";
                                    array_push($filteringBindParams, $_REQUEST['order_id']);
                                }
                                if ($_REQUEST['date_from'] != '' && $_REQUEST['date_to'] != '') {
                                    $filteringQuery[] = "(`order`.`added_date` BETWEEN ? AND ?)";
                                    array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_from'])));
                                    array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_to'])));
                                } else if ($_REQUEST['date_from'] != '' && $_REQUEST['date_to'] == '') {
                                    $filteringQuery[] = "(`order`.`added_date` BETWEEN ? AND ?)";
                                    array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_from'])));//TODO check date fromat from view and in db
                                    array_push($filteringBindParams, strtotime(time()));
                                } else if ($_REQUEST['date_from'] == '' && $_REQUEST['date_to'] != '') {
                                    $filteringQuery[] = "(`order`.`added_date` BETWEEN ? AND ?)";
                                    array_push($filteringBindParams, strtotime(1000000000));
                                    array_push($filteringBindParams, strtotime(str_replace('-', ' ', $_REQUEST['date_to'])));
                                }
                                if ($_REQUEST['price_from'] != '' && $_REQUEST['price_to'] != '') {
                                    $filteringQuery[] = "(`order`.`final_price` BETWEEN ? AND ?)";
                                    array_push($filteringBindParams, intval($_REQUEST['price_from']));
                                    array_push($filteringBindParams, intval($_REQUEST['price_to']));
                                } else if ($_REQUEST['price_from'] != '' && $_REQUEST['price_to'] == '') {
                                    $filteringQuery[] = "(`order`.`final_price` BETWEEN ? AND ?)";
                                    array_push($filteringBindParams, intval($_REQUEST['price_from']));
                                    array_push($filteringBindParams, intval(100000000));
                                } else if ($_REQUEST['price_from'] == '' && $_REQUEST['price_to'] != '') {
                                    $filteringQuery[] = "(`order`.`final_price` BETWEEN ? AND ?)";
                                    array_push($filteringBindParams, intval(100000000));
                                    array_push($filteringBindParams, intval($_REQUEST['price_to']));
                                }
                                // Filter Implode //
                                $implodedWhere = ['whereRaw' => 1];
                                if (!empty($filteringQuery)) {
                                    $implodedWhere['whereRaw'] = implode(' AND ', array_map(function ($filterValues) {
                                        return $filterValues;
                                    }, $filteringQuery));
                                    $implodedWhere['bindParams'] = $filteringBindParams;
                                }
                                $iTotalRecords = $iDisplayLength = intval($_REQUEST['length']);
                                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                                $iDisplayStart = intval($_REQUEST['start']);
                                $sEcho = intval($_REQUEST['draw']);
                                $columns = array('orders.order_id');//, 'orders.added_date', 'orders.order_type', 'orders.final_price', 'orders.order_status'
                                $sortingOrder = "";
                                if (isset($_REQUEST['order'])) {
                                    $sortingOrder = $columns[$_REQUEST['order'][0]['column']];
                                }
                                if ($implodedWhere['whereRaw'] != 1) {
                                    $whereForOrders = ['rawQuery' => 'order_status != ?', 'bindParams' => ['P']];
                                    $selectedColumn = ['*'];
                                    $allOrdersData = json_decode($objModelOrders->getAllOrdersWhereWithLimit($whereForOrders, $implodedWhere, $sortingOrder, $iDisplayLength, $iDisplayStart, $selectedColumn), true);
                                }
                            }
                            // FILTERING ENDS//

//                            dd($allOrdersData['data']);
                            $allOrders = new Collection();
                            if ($allOrdersData['code'] == 200) {
                                foreach ($allOrdersData['data'] as $key => $val) {
                                    $orderStatus = $val['order_status'];
                                    $actionsDiv = "";
                                    $viewDiv = "<a class=\"btn btn-xs default btn-editable\" href=\"/admin/order-details/" . $val['order_id'] . "\"><i class=\"fa fa-search\"></i> View</a>";
                                    if ($orderStatus == "TS") { // TS=tx success [if not cod then show cancel button],
                                        $orderStatus = "Waiting for dispatch";
//                                    } else if ($orderStatus == "P") {
//                                        $orderStatus = "";
                                    } else if ($orderStatus == "TP") { // TP=tx in process[if COD show cancel button],
                                        $orderStatus = "Transaction pending";
                                    } else if ($orderStatus == "TF") { // TF=tx failed,
                                        $orderStatus = "Transaction failed";
                                    } else if ($orderStatus == "S") { // S=shipping [show cancel button],
                                        $orderStatus = "Shipping";
                                    } else if ($orderStatus == "UC") { // UC=user cancel request [show dispute button],
                                        $orderStatus = "Cancel request";
                                    } else if ($orderStatus == "UCA") { // UCA=user cancel approved,
                                        $orderStatus = "Cancelled";
                                    } else if ($orderStatus == "MC") { // MC=merchant cancel,
                                        $orderStatus = "Withheld by seller";
                                    } else if ($orderStatus == "D") { // D=delivered [show dispute button, show refund button],
                                        $orderStatus = "Delivered";
                                    } else if ($orderStatus == "RR") { // RR=refund request,
                                        $orderStatus = "Refund request";
                                    } else if ($orderStatus == "RP") { // RP=refund in process,
                                        $orderStatus = "Refund in process";
                                    } else if ($orderStatus == "RD") { // RD=refund done [show dispute button]
                                        $orderStatus = "Refund complete";
                                    } else {
                                        $orderStatus = "NA";
                                    }
                                    $allOrders->push([
                                        'order_id' => $val['order_id'],
                                        'order_date' => date('d-F-Y', strtotime($val['added_date'])),
                                        'product_details' => $val['product_details'],
                                        'order_amount' => $val['final_price'],
                                        'status' => $orderStatus,
                                        'action' => $viewDiv,
                                    ]);
                                }

                                return Datatables::of($allOrders)
//                                    ->addColumn('action', function ($allOrders) {
//                                        $action = '<div role="group" class="btn-group "> <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"> <i class="fa fa-cog"></i>&nbsp; <span class="caret"></span></button>';
//                                        $action .= '<ul role="menu" class="dropdown-menu">';
//                                        $action .= '<li><a href="/admin/edit-supplier/' . $allOrders['order_id'] . '"><i class="fa fa-pencil" data-id="{{$products->product_id}}"></i>&nbsp;Edit</a></li>';
//                                        $action .= '</ul>';
//                                        $action .= '</div>';
//                                        return $action;
//                                    })
//                                    ->addColumn('product_status', function ($allOrders) {
//                                        $button = '<td style="text-align: center">';
//                                        $button .= '<td class="center" style="text-align: center;">';
//                                        $button .= '<div class="form-group">';
//                                        $button .= '<select class="form-control" data-id="' . $allOrders['order_id'] . '" id="statuspending" style="width:90%; margin-left: 2%; background-color: orange">';
//                                        $button .= '<option value="0" selected style="background-color: whitesmoke">Pending</option>';
//                                        $button .= '<option value="1" style="background-color: whitesmoke">Approved</option>';
//                                        $button .= '<option value="3" style="background-color: whitesmoke">Rejected</option>';
//                                        $button .= '</select>';
//                                        $button .= '</div>';
//                                        $button .= '<td>';
//                                        return $button;
//
//                                    })
                                    ->make();
                            } else {
                                return Datatables::of($allOrders)
                                    ->make();
                            }
                            break;

                        default:
                            return Datatables::of(new Collection())
                                ->make();
                            break;
                    }
                } else {
                    dd("Invalid table request");
                }
            } else {
                dd("No such dataTable exists");
            }
        } else {
            dd("Invalid request");
        }
    }
}


