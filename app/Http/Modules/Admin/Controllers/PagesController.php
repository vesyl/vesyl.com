<?php


namespace FlashSale\Http\Modules\Admin\Controllers;
use FlashSale\Http\Controllers\Controller;
use FlashSale\Http\Modules\Admin\Models\Extrapages;
use FlashSale\Http\Modules\Admin\Models\User;
use FlashSale\Http\Modules\Admin\Models\Usersmeta;
use Illuminate\Http\Request;
use FlashSale\Http\Requests;

use Datatables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PagesController extends Controller
{
    public function pagelist(Request $request){


            $objExtrapagesModel = Extrapages::getInstance();
            $selectedColumns = ['pages_extra.*'];
            $pageDetail = $objExtrapagesModel->getAllPDwhere($selectedColumns);
        $returnData['pageDetail']=json_decode($pageDetail);

            return view('Admin/Views/pagesextra/pagelist', ['returnData' => $returnData]);
        }


    public function addnewpage(Request $request ){

            $postData = $request->all();
        $Weburl=env('WEB_URL');
        $objExtrapagesModel = Extrapages::getInstance();
//        dd($objExtrapagesModel);
            if ($request->isMethod('post')) {

                $postData = $request->all();
                $rules = array(
                    'subject' => 'required',
                    'url'=>'required',
                    'description' => 'required|min:30',
                );
//                dd($rules);
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
                    //todo use
                } else {
//                    dd($request->all());
                    $subject= ucfirst($postData['subject']);
                    $data = array(
                        'page_title' => $subject,
                        'page_content_url'=>$postData['url'],
                        'page' => $postData['description'],
                        'page_status'=>$postData['status']
                    );//todo use File library of laravel
                    $val=$data['page_content_url'];
                    $description=$data['page'];
                    if (!File::exists(storage_path()."/pages")) File::makeDirectory(storage_path()."/pages", 0777, true, true);
                    File::put(storage_path(). "/pages/$val.txt", $description);
                    $insertData['page_name']=$val;
                    $insertData['page_title']=$data['page_title'];
                    $insertData['page_content_url']= "/pages/$val";
                    $insertData['page_status']= $data['page_status'];

                    $collection = $objExtrapagesModel->getAllANwhere($insertData);
                    if ($collection) {
                        return Redirect::back()->with(['status' => 'success', 'msg' => 'Newsletter Added Successfully.']);
                    } else {
                        return Redirect::back()->with(['status' => 'error', 'msg' => 'Some Error try again.']);
                    }
                }
            }


        return view("Admin/Views/pagesextra/addnewpage",['weburl'=>$Weburl]);
    }




    public function pageajaxhandler(Request $request)
    {
        $ObjUser = User::getInstance();
        $ObjUsermeta = Usersmeta::getInstance();
        $method = $request->input('method');
        $objExtrapagesModel = Extrapages::getInstance();
//        $mainId = Session::get('fs_admin')['id'];
        if ($method != "") {
            switch ($method) {
                case "availablePages":
                    $ObjUser = User::getInstance();
                    $available_pages = $ObjUser->getAvailableUserDetails();
                    return Datatables::of($available_pages)
                        ->addColumn('action', function ($available_pages) {
                            return '<span class="tooltips" title="Edit User Details." data-placement="top"> <a href="/admin/edit-pageslist/' . $available_pages->page_id . '" class="btn btn-sm grey-cascade" style="margin-left: 10%;">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            </span> &nbsp;&nbsp;
                                            <span class="tooltips" title="Delete User Details." data-placement="top"> <a href="#" data-cid="' . $available_pages->page_id . '" class="btn btn-danger delete-user" style="margin-left: 10%;">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </span>';
                        })
//                        ->addColumn('status', function ($available_pages) use ($mainId) {
//
//                            $button = '<td style="text-align: center">';
//                            $button .= '<button class="btn ' . (($available_pages->status == 1) ? "btn-success" : "btn-danger") . ' customer-status" data-id="' . $available_pages->id . '" data-set-by="' . $mainId . '">' . (($available_pages->status == 1) ? "Active" : "Inactve") . ' </button>';
//                            $button .= '</td>';
//                            return $button;
//                        })
                        ->removeColumn('page_name')
                        ->make();
                    break;
                case 'changePageStatus':
                    $pageId = $request->input('pageId');
                    $status = $request->input('pagestatus');
                    $where = ['rawQuery' => 'page_id =?', 'bindParams' => [$pageId]];
                    $data['page_status'] = $status;
                    $Update = $objExtrapagesModel->getAllUPwhere($data, $where);
                    $data['status'] = $status;
                    $data['update'] = $Update;
                    if ($data) {
                        echo json_encode(['status' => 'success', 'msg' => 'Status has been changed.']);
                    } else {
                        echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }
                    break;

            }
        }
    }
    public function editPages(Request $request, $uid)
    {

        $ObjUser = User::getInstance();
        $objExtraPages= Extrapages::getInstance();
        if ($request->isMethod("GET")) {
            $where = ['rawQuery' => 'page_id =?', 'bindParams' => [$uid]];
            $pagesDetails = $objExtraPages->getAllPageDetailswhere($where);
            $pagesDetails=json_decode($pagesDetails);
            $pagesDetails=$pagesDetails->data[0];
            $pagecontent=$pagesDetails->page_content_url;
//            dd($pagecontent);
           $content= File::get(storage_path(). $pagecontent.'.txt');
            return view('Admin/Views/pagesextra/editPageslist', ['userdetail' => $pagesDetails, 'content'=>$content]);
        } else if ($request->isMethod("POST")) {
            $postdata = $request->all();
            $subject= ucfirst($postdata['subject']);
            $data = array(
                'page_title' => $subject,
                'page_content_url'=>$postdata['url'],
                'page' => $postdata['description'],
                'page_status'=>$postdata['status']
            );
            $val=$data['page_content_url'];
            $description=$data['page'];
            if (!File::exists(storage_path()."/pages")) File::makeDirectory(storage_path()."/pages", 0777, true, true);
            File::put(storage_path(). "$val.txt", $description);
            $collection = $objExtraPages->getAllEditupdatewhere();
            if ($collection) {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Details Suuccesfully Edited.']);
            } else {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Some Error Occured.']);
            }

        }
    }
}