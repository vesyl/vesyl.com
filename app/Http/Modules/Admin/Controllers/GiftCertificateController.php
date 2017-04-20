<?php
namespace FlashSale\Http\Modules\Admin\Controllers;

use FlashSale\Http\Modules\Admin\Models\AdminGiftCertificate;
use FlashSale\Http\Modules\Admin\Models\GiftCertificates;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
use PDO;
use Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class GiftCertificateController extends Controller
{

    /**
     * Add Gift Certificate
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 08-09-2016
     */
    public function addGiftCertificate(Request $request)
    {


        $objAdminGiftCertificateModel = AdminGiftCertificate::getInstance();
        $adminId = Session::get('fs_admin')['id'];
        if ($request->isMethod('post')) {
            $rules = array(
                'gift_code' => 'required|unique:admin_gift_certificate',
                'gift_name' => 'required|unique:admin_gift_certificate',
                'gift_certificate' => 'image|required',
                'gift_amount' => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $postData = $request->all();

            $data['gift_name'] = $postData['gift_name'];
            $data['gift_code'] = $postData['gift_code'];
            if (Input::hasFile('gift_certificate')) {
                $filePath = uploadImageToStoragePath(Input::file('gift_certificate'), 'giftCertificate', 'giftCertificate_' . $adminId . '_' . time() . ".jpg");
                $data['gc_img_src'] = $filePath;
            }
            $data['gift_amount'] = $postData['gift_amount'];
            $adminGiftCertificate = $objAdminGiftCertificateModel->addGiftCertificate($data);
//            print_a($adminGiftCertificate);
            if ($adminGiftCertificate) {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Gift Certificate Added Successfully.']);
            } else {
                return Redirect::back()->with(['status' => 'error', 'msg' => 'Some Error try again.']);
            }
        }

        return view('Admin/Views/giftcertificate/add-new-giftcertificate');

    }

    /**
     * Manage Admin Gift certificate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 08-09-2016
     */
    public function manageAdminGiftCertificate()
    {

        $objAdminGiftCertificateModel = AdminGiftCertificate::getInstance();
        $selectedColumns = ['admin_gift_certificate.*'];
        $adminGiftData = $objAdminGiftCertificateModel->getAllAdminGiftCertificate($selectedColumns);
        return view('Admin/Views/giftcertificate/manage-giftcertificate', ['adminGiftCertificate' => $adminGiftData]);

    }


    /**
     * Gift certificate Ajax handler for difftenr case basis
     * @param Request $request
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 08-09-2016
     */
    public function giftcertificateAjaxHandler(Request $request)
    {
        $method = $request->input('method');
        $ObjAdminGiftCertificateModel = AdminGiftCertificate::getInstance();
        if ($method != "") {
            switch ($method) {
                case 'changeGiftStatus':
                    $giftId = $request->input('giftId');
                    $wheregiftId = ['rawQuery' => 'gift_id =?', 'bindParams' => [$giftId]];
                    $giftStatus = $request->input('giftStatus');
                    $data['status'] = $giftStatus;
                    $giftUpdate = $ObjAdminGiftCertificateModel->updateGiftCertificate($data, $wheregiftId);
                    $giftdata['status'] = $giftStatus;
                    $giftdata['update'] = $giftUpdate;
                    if ($giftdata) {
                        echo json_encode(['status' => 'success', 'msg' => 'Status has been changed.']);
                    } else {
                        echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }
                    break;
                case 'deleteGiftCertificate':
                    $giftId = $request->input('giftId');
                    $whereForDeleteOption = ['rawQuery' => 'gift_id =?', 'bindParams' => [$giftId]];
                    $deleteOptionResult = $ObjAdminGiftCertificateModel->deleteGiftWhere($whereForDeleteOption);
                    if ($deleteOptionResult) {//TODO NOTIFICATION TO ALL SUPPLIER
                        echo json_encode(['status' => 'success', 'msg' => 'Selected Gift Certficate has been deleted.']);
                    } else {
                        echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }

                    break;
                default:
                    break;
            }
        }

    }

    /**
     * Edit gift certificate
     * @param Request $request
     * @param $giftid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 08-09-2016
     */
    public function editGiftCertificate(Request $request, $giftid)
    {

        $objAdminGiftCertificateModel = AdminGiftCertificate::getInstance();
        if ($request->isMethod('post')) {
            $rules = array(
                'gift_name' => 'unique:admin_gift_certificate,gift_name,' . $giftid . ',gift_id',
                'gift_certificate' => 'image',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            if (Input::hasFile('gift_certificate')) {
                $filePath = uploadImageToStoragePath(Input::file('gift_certificate'), 'giftCertificate', 'giftCertificate' . '_' . time() . ".jpg");
                $data['gc_img_src'] = $filePath;
            }
            $postData = $request->all();
            $data['gift_name'] = $postData['gift_name'];
            $data['gift_code'] = $postData['gift_code'];
            $data['gift_amount'] = $postData['gift_amount'];

            $where = ['rawQuery' => 'gift_id = ?', 'bindParams' => [$giftid]];
            $giftCertificateUpdate = $objAdminGiftCertificateModel->updateGiftCertificate($data, $where);

            if ($giftCertificateUpdate) {
                if (isset($filePath))
                    deleteImageFromStoragePath($postData['oldImage']);
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Updated Successfully.']);
            } else {
                return Redirect::back()->with(['status' => 'error', 'msg' => 'Some Error try again.']);
            }
        }

        $where = ['rawQuery' => 'gift_id = ?', 'bindParams' => [$giftid]];
        $selectedColumns = ['admin_gift_certificate.*'];
        $adminGiftDetails = $objAdminGiftCertificateModel->getAllAdminGiftCertificateById($where, $selectedColumns);
        return view('Admin/Views/giftcertificate/edit-giftcertificate', ['adminGiftDataById' => $adminGiftDetails]);

    }


    public function userGiftCertificateList(Request $request){

        $objUserGiftCertificateModel = GiftCertificates::getInstance();
        $selectedColumns = ['gift_certificates.*','gift_by.name as gift_by_name','gift_for.name as gift_for_name'];
        $giftCertificate = $objUserGiftCertificateModel->giftCertificateList($selectedColumns);
//        print_a($giftCertificate);
        return view('Admin/Views/giftcertificate/user-giftcertificate-list',['userGiftList' => $giftCertificate]);
    }
}