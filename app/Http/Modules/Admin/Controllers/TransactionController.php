<?php

namespace FlashSale\Http\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use FlashSale\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;

//DATABASE MODELS START
use FlashSale\Http\Modules\Admin\Models\Order;
use FlashSale\Http\Modules\Admin\Models\Transactions;

//DATABASE MODELS END


class TransactionController extends Controller
{

    public function transactionHistory()
    {
        return view("Admin/Views/transaction/transactionHistory");
    }

    public function transactionAjaxHandler(Request $request)
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

    public function transactionDatatablesHandler(Request $request)
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
                    $objModelTransactions = Transactions::getInstance();
                    switch ($tableName) {
                        case  "transactionstableadmin":

                            // NORMAL DATATABLE STARTS HERE//
                            $whereForTransaction = ['rawQuery' => '1'];
                            $allTransactionsData = json_decode($objModelTransactions->getAllTransactionsWhere($whereForTransaction), true);
                            //NORMAL DATATABLE ENDS HERE//

                            // FILTERING STARTS FROM HERE//
                            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter' && $_REQUEST['action'][0] != 'filter_cancel') {
                                $implodedWhere = ['whereRaw' => 1];
                                $iTotalRecords = $iDisplayLength = intval($_REQUEST['length']);
                                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                                $iDisplayStart = intval($_REQUEST['start']);
                                $sEcho = intval($_REQUEST['draw']);
                                $columns = array('transactions.transaction_id');//, 'orders.added_date', 'orders.order_type', 'orders.final_price', 'orders.order_status'
                                $sortingOrder = "";
                                if (isset($_REQUEST['order'])) {
                                    $sortingOrder = $columns[$_REQUEST['order'][0]['column']];
                                }
                                if ($implodedWhere['whereRaw'] != 1) {
                                    $whereForOrders = ['rawQuery' => '1'];
                                    $selectedColumn = ['*'];
                                    $allTransactionsData = json_decode($objModelTransactions->getAllTransactionsWhereWithLimit($whereForOrders, $implodedWhere, $sortingOrder, $iDisplayLength, $iDisplayStart, $selectedColumn), true);//TODO write function in model
                                }
                            }
                            // FILTERING ENDS//

//                            dd($allTransactionsData['data']);
                            $allOrders = new Collection();
//                            dd($allTransactionsData);
                            if ($allTransactionsData['code'] == 200) {
                                foreach ($allTransactionsData['data'] as $key => $val) {
                                    $transactionStatus = $val['tx_status'];
                                    $actionsDiv = "";
                                    $viewDiv = "<a class=\"btn btn-xs default btn-editable\" href=\"/admin/transaction-details/" . $val['transaction_id'] . "\"><i class=\"fa fa-search\"></i> View</a>";
                                    if ($transactionStatus == "P") { // TS=tx success [if not cod then show cancel button],
                                        $transactionStatus = "Pending";
                                    } else if ($transactionStatus == "S") { // TP=tx in process[if COD show cancel button],
                                        $transactionStatus = "Success";
                                    } else if ($transactionStatus == "F") { // TF=tx failed,
                                        $transactionStatus = "Failed";
                                    } else {
                                        $transactionStatus = "NA";
                                    }
                                    $allOrders->push([
                                        'transaction_id' => $val['transaction_id'],
                                        'tx_date' => date('d-F-Y', strtotime($val['tx_date'])),
                                        'product_details' => $val['payment_details'],
                                        'tx_amount' => "Paypal: " . $val['tx_amount'] . ($val['walletbal_used'] > 0 ? "<br> Wallet amount: <strong>" . $val['walletbal_used'] . "</strong>" : "") . ($val['rewardpts_used'] > 0 ? "<br> Reward points: <strong>" . $val['rewardpts_used'] . "</strong>" : ""),
                                        'status' => $transactionStatus,
                                        'action' => ""//$viewDiv,
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


