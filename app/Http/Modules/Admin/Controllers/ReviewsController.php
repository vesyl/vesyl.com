<?php
namespace FlashSale\Http\Modules\Admin\Controllers;


use FlashSale\Http\Modules\Admin\Models\Reviews;

use Illuminate\Http\Request;
use Datatables;
use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Mail;
use PDO;
use getPdo;
use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use SendGrid\Content;
use SendGrid\Email;
class ReviewsController extends Controller
{

    public function reviews(Request $request)
    {
        $inputData = $request->all();
        $method = $request->input('method');
        $ObjReviews= Reviews::getInstance();
        if ($method != "") {
            switch ($method) {
                case "getproductpendingreviews":



                    $where = ['rawQuery' => 'review_type =? AND review_status = ?', 'bindParams' => ['P', 'P']];
                    $ReviewsDetail = $ObjReviews->getpendingDetails($where);
                    return Datatables::of($ReviewsDetail)
                        ->addColumn('status', function ($pending_details) {
                            return ' <span class="dropdown" style="position:absolute;">
                         <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" data-id = ' . $pending_details->review_status . '>Pending<span class="caret" style="margin-left:4px;"></span></button>
                                    <ul class="dropdown-menu">
                                          <li><a data-id=' . $pending_details->review_id . ' class="productapproved">Approved</a></li>
                                          <li><a data-id=' . $pending_details->review_id . ' class="productrejected">Rejected</a></li>

                                       </ul>
                                         </span>
                                      </div>

                                    </td > ';
                        })
                        ->addColumn('action', function ($ReviewsDetail) {
                            return '<button class="btn btn-danger deleteApproved" data-id='.$ReviewsDetail->review_id.'><i class="fa fa-trash" aria-hidden="true"></i></button >';
                        })
//
                        ->removeColumn('review_status')
                        ->make();
                    break;




                case "getupadetereviews":

                    $ReviewsId=$request->ReviewsId;
                    $ReviewStatus=$request->ReviewStatus;


                    $user = DB::table('reviews')->where('review_id', $ReviewsId)->update(['review_status' => $ReviewStatus]);

                case "getproductacceptedreviews":

                    $where = ['rawQuery' => 'review_type =? AND review_status = ?', 'bindParams' => ['P', 'A']];
                    $ReviewsDetail = $ObjReviews->getpendingDetails($where);
//                    dd($ReviewsDetail);
                    return Datatables::of($ReviewsDetail)
                        ->addColumn('status', function ($approved_details) {
                            return ' <td style = "text-align: center" >
                                            <div class="dropdown">
                              <button class="btn btn-success " data-id = '.$approved_details->review_status.'>Approved</button>

                                      </div>
                                    </td > ';
                        })
                        ->addColumn('action', function ($ReviewsDetail) {
                            return '<button class="btn btn-danger deleteApproved" data-id='.$ReviewsDetail->review_id.'><i class="fa fa-trash" aria-hidden="true"></i></button >';
                        })
//                        ->editColumn('id', 'ID: {{$id}}')
                        ->removeColumn('review_status')
                        ->make();
                    break;


                case "getproductrejectedreviews":
                    $where = ['rawQuery' => 'review_type =? AND review_status = ?', 'bindParams' => ['P', 'R']];
                    $ReviewsDetail = $ObjReviews->getpendingDetails($where);
//                    dd($ReviewsDetail);
                    return Datatables::of($ReviewsDetail)
                        ->addColumn('status', function ($rejected_details) {
                            return ' <td style = "text-align: center" >
                                             <div class="dropdown">
                              <button class="btn btn-danger" data-id = ' . $rejected_details->review_status . '>Rejected</button>

                                      </div>
                                    </td > ';
                        })
                        ->addColumn('action', function ($ReviewsDetail) {
                            return '<button class="btn btn-danger deleteApproved" data-id='.$ReviewsDetail->review_id.'><i class="fa fa-trash" aria-hidden="true"></i></button >';
                        })
//
                        ->removeColumn('review_status')
                        ->make();
                    break;

                case "getshoppendingreviews":
                    $where = ['rawQuery' => 'review_type =? AND review_status = ?', 'bindParams' => ['S', 'P']];
                    $ReviewsDetail = $ObjReviews->getpendingDetails($where);
                    return Datatables::of($ReviewsDetail)
                        ->addColumn('status', function ($pending_details) {
                            return ' <span class="dropdown" style="position:absolute;">
                         <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" data-id = ' . $pending_details->review_status . '>Pending<span class="caret" style="margin-left:4px;"></span></button>
                                    <ul class="dropdown-menu">
                                          <li><a data-id=' . $pending_details->review_id . ' class="productapproved">Approved</a></li>
                                          <li><a data-id=' . $pending_details->review_id . ' class="productrejected">Rejected</a></li>

                                       </ul>
                                         </span>
                                      </div>
                                    </td > ';
                        })
                        ->addColumn('action', function ($ReviewsDetail) {
                            return '<button class="btn btn-danger deleteApproved" data-id='.$ReviewsDetail->review_id.'><i class="fa fa-trash" aria-hidden="true"></i></button >';
                        })
//
                        ->removeColumn('review_status')
                        ->make();
                    break;


                case "getshopacceptedreviews":
                    $where = ['rawQuery' => 'review_type =? AND review_status = ?', 'bindParams' => ['S', 'A']];
                    $ReviewsDetail = $ObjReviews->getpendingDetails($where);
                    return Datatables::of($ReviewsDetail)
                    ->addColumn('status', function ($approved_details) {
                        return ' <td style = "text-align: center" >

                                             <div class="dropdown">
                              <button class="btn btn-success " data-id = ' . $approved_details->review_status . '>Approved</button>

                                      </div>
                                    </td > ';
                    })
                    ->addColumn('action', function ($ReviewsDetail) {
                        return '<button class="btn btn-danger deleteApproved" data-id='.$ReviewsDetail->review_id.'><i class="fa fa-trash" aria-hidden="true"></i></button >';
                    })

                    ->removeColumn('review_status')
                    ->make();
                    break;


                case "getshoprejectedreviews":
                    $where = ['rawQuery' => 'review_type =? AND review_status = ?', 'bindParams' => ['S', 'R']];
                    $ReviewsDetail = $ObjReviews->getpendingDetails($where);
                    return Datatables::of($ReviewsDetail)
                        ->addColumn('status', function ($rejected_details) {
                            return ' <td style = "text-align: center" >
                                               <div class="dropdown">
                              <button class="btn btn-danger" data-id = ' . $rejected_details->review_status . '>Rejected</button>

                                      </div>

                                    </td > ';
                        })
                        ->addColumn('action', function ($ReviewsDetail) {
                            return '<button class="btn btn-danger deleteApproved" data-id='.$ReviewsDetail->review_id.'><i class="fa fa-trash" aria-hidden="true"></i></button >';
                        })
//
                        ->removeColumn('review_status')
                        ->make();
                    break;

                case "deletemethod":

                    $ReviewsId = $inputData['ReviewsId'];

                    $where = ['rawQuery' => 'review_id = ?', 'bindParams' => [$ReviewsId]];
                    $ReviewsDetail=$ObjReviews->deleteUserDetails($where);

//                    return ' <td style = "text-align: center" >
//                     <button class="btn btn-danger "  data-id ='.$ReviewsDetail->review_id.' class="deleteapproved">
//                    <i class="fa fa-trash" aria-hidden="true"></i></button >
//                     </td > ';
                    break;
            }


        }
    }



    public function pendingreviews()
    {
        return view('Admin/Views/reviews/pendingreviews');
    }
    public function approvedreviews(Request $request)
    {
        return view('Admin/Views/reviews/approvedreviews');
    }
    public function rejectedreviews(Request $request)
    {
        return view('Admin/Views/reviews/rejectedreviews');
    }


}