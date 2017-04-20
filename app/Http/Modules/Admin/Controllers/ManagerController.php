<?php
namespace FlashSale\Http\Modules\Admin\Controllers;

use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
use PDO;
use Input;
//use Yajra\Datatables\Datatables;
use Datatables;
use FlashSale\Http\Modules\Admin\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use stdClass;
//use Mandrill;
use Illuminate\Support\Facades\Hash;
use FlashSale\Http\Modules\Admin\Models\Permissions;
use FlashSale\Http\Modules\Admin\Models\PermissionUserRelation;
use FlashSale\Http\Modules\Admin\Models\MailTemplate;
use SendGrid\Email;
use SendGrid\Mail;

require_once public_path() . '/../vendor/autoload.php';


class ManagerController extends Controller
{


    /**
     * Add New Manager Action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function addNewManager(Request $request)
    {


        $response = new stdClass();

        $ObjPermissions = Permissions::getInstance();
        $ObjPermissionUserRelation = PermissionUserRelation::getInstance();
        if ($request->isMethod('GET')) {
            $where = ['rawQuery' => 'permission_id  NOT IN (1)'];
            //echo"<pre>";print_r($where);die("cfh");
            $permissionDetails = $ObjPermissions->getAllPermissions($where);

            return view('Admin/Views/manager/addNewManager', ['permissionlist' => $permissionDetails]);
        } elseif ($request->isMethod('POST')) {
            $postData = $request->all();

            $rules = array(
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'username' => 'required|max:255',
                'permitcheck' => 'required'
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            } else {

                $password = "";
                $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
                $max = count($characters) - 1;
                for ($i = 0; $i < 8; $i++) {
                    $rand = mt_rand(0, $max);
                    $password .= $characters[$rand];
                }
                $manager = User::create([
                    'name' => $postData['firstname'],
                    'last_name' => $postData['lastname'],
                    'email' => $postData['email'],
                    'password' => Hash::make($password),
                    'role' => '4',
                    'username' => $postData['username']
                ]);

                $resultdata = DB::getPdo()->lastInsertId($manager);

                $data['user_id'] = $resultdata;
                $permit = $postData['permitcheck'];
                $mainpermission = implode(",", $permit);
                //$mainpermission = 1;
                $data['permission_ids'] = $mainpermission;

                $userPermission = $ObjPermissionUserRelation->insertmanagerpermission($data);

                if ($manager && $userPermission) {

                    $objMailTemplate = MailTemplate::getInstance();
                    $temp_name = "manager_signup_success_mail";
                    $objMailTemplate = MailTemplate::getInstance();
                    $mailTempContent =$objMailTemplate->getTemplateByName($temp_name);
//                      $where = ['rawQuery' => 'temp_name = ?', 'bindParam' => $temp_name];

//                    $key = env('MANDRILL_KEY');
//                    $mandrill = new Mandrill($key);
//                    $async = false;
//                    $ip_pool = 'Main Pool';
//                    $message = array(
//                        'html' => $mailTempContent->temp_content,
//                        'subject' => "Registration Successful As Manager",
//                        'from_email' => "support@flashsale.com",
//                        'to' => array(
//                            array(
//                                'email' => $postData['email'],
//                                'type' => 'to'
//                            )
//                        ),
//                        'merge_vars' => array(
//                            array(
//                                "rcpt" => $postData['email'],
//                                'vars' => array(
//                                    array(
//                                        "name" => "firstname",
//                                        "content" => $postData['firstname']
//                                    ),
//                                    array(
//                                        "name" => "password",
//                                        "content" => $password
//                                    ),
//                                    array(
//                                        "name" => "username",
//                                        "content" => $postData['username']
//                                    ),
//                                    array(
//                                        "name" => "email",
//                                        "content" => $postData['email']
//                                    ),
//                                    array(
//                                        "name" => "url",
//                                        "content" => env('WEB_URL') . '/admin/login',
//                                    )
//                                )
//                            )
//                        ),
//                    );
                    /*
                    $mailrespons = $mandrill->messages->send($message, $async, $ip_pool);
                    if ($mailrespons[0]['status'] == "sent") {
                        return Redirect::back()->with(['status' => 'success', 'msg' => 'Mail sent successfully to  ' . $postData['firstname']]);
                    } else {
                        $objuser = new User();
                        $whereForUpdate = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$manager->id]
                        ];
                        $deleteUser = $objuser->deleteUserDetails($whereForUpdate);
                        return Redirect::back()->with(['status' => 'success', 'msg' => 'Failed To Send Mail.']);

                    }
                    */
                    $from = new \SendGrid\Email(null, "support@flashsale.com");
                    $subject = "Flashsale Newsletter";

                    $to = new  \SendGrid\Email(null, $postData['email']);
                    $content = new \SendGrid\Content("text/plain", $mailTempContent->temp_content);
                    $mail = new  \SendGrid\Mail($from, $subject, $to, $content);

                    $apiKey = env('SENDGRID_API_KEY');
                    $sg = new \SendGrid($apiKey);

                    $response = $sg->client->mail()->send()->post($mail);
                    if ($postData['email'] == "sent" || $response->_status_code == 202) {
                        return Redirect::back()->with(['status' => 'success', 'msg' => 'Mail sent successfully to  ' . $postData['firstname']]);
                    } else {
                        $objuser = new User();
                        $whereForUpdate = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$manager->id]
                        ];
                        $deleteUser = $objuser->deleteUserDetails($whereForUpdate);
                        return Redirect::back()->with(['status' => 'error', 'msg' => 'Failed To Send Mail.']);

                    }
                }
            }
        }
    }



    /**
     * Available Manager Action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function availableManager(Request $request)
    {

        return view('Admin/Views/manager/availableManager');

    }

    /**
     * Manager Ajax Handler
     * @param Request $request
     * @return mixed
     * @author: Vini Dubey<vinidubey@globussoft.in>
     */
    public function managerAjaxHandler(Request $request)
    {

        $inputData = $request->input();
        $method = $request->input('method');
        $ObjUser = User::getInstance();
        $mainId = Session::get('fs_admin')['id'];
        if ($method) {
            switch ($method) {
                case "availableManager":
                    $objuser = User::getInstance();
                    $objPermissionModel = Permissions::getInstance();
                    $objPermissionUserRelation = PermissionUserRelation::getInstance();
                    $where = ['rawQuery' => 'role = ?', 'bindParams' => [4]];
                    $available_customers = $objuser->getAvailableManagerDetails($where);
                    return Datatables::of($available_customers)
                        ->addColumn('action', function ($available_customers) {
                            return '<span class="tooltips" title="Edit Manger Details." data-placement="top"> <a href="/admin/edit-manager/' . $available_customers->id . '" class="btn btn-sm grey-cascade" style="margin-left: 10%;">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            </span> &nbsp;&nbsp;
                                            <span class="tooltips" title="Delete Manger Details." data-placement="top"> <a href="#" data-cid="' . $available_customers->id . '" class="btn btn-danger delete-manager" style="margin-left: 10%;">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </span>';
                        })
                        ->addColumn('status', function ($available_customers) use ($mainId) {

                            $button = '<td style="text-align: center">';
                            $button .= '<button class="btn ' . (($available_customers->status == 1) ? "btn-success" : "btn-danger") . ' manager-status" data-id="' . $available_customers->id . '"  data-set-by="' . $mainId . '">' . (($available_customers->status == 1) ? "Active" : "Inactve") . ' </button>';
                            $button .= '<td>';
                            return $button;
                        })
                    ->addColumn('permission', function ($available_customers) {

                        return '<td><a class=" btn default permission" data-id=' . $available_customers->id . ' data-target="#permissionmodal" data-toggle="modal">Permission </a></td>';

                    })
                    ->removeColumn('name')
                    ->removeColumn('updated_at')
                    ->make();
                break;
            case 'changeManagerStatus':
                $userId = $inputData['UserId'];
                $whereForUpdate = ['rawQuery' => 'id =?', 'bindParams' => [$userId]];
                $dataToUpdate['status'] = $inputData['status'];
                $dataToUpdate['status_set_by'] = $userId;
                $updateResult = $ObjUser->updateUserWhere($dataToUpdate, $whereForUpdate);

                if ($updateResult == 1) {
                    echo json_encode(['status' => 'success', 'msg' => 'Status has been changed.']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                }
                break;
                case 'updateManager':
                    $managerId = $request->ManagerId;
                    $status = $request->Status;
                    $user = DB::table('users')
                        ->where('id', $managerId)
                        ->update(['status' => $status]);

            case 'deleteManagerStatus':
                $objuser = User::getInstance();
                $where = array('rawQuery' => 'role = ?', 'bindParams' => [3]);
                $status = array('rawQuery' => 'status = ?', 'bindParams' => [4]);
                $deleted_supplier = $objuser->getDeletedUserDetails($where, $status);

                return Datatables::of($deleted_supplier)
                    ->addColumn('status', function ($deleted_manager) {
                        return '<td style="text-align: center">
                                            <button class="btn btn-primary customer-status"
                                                    data-id=' . $deleted_manager->id . '>Deleted
                                            </button>

                                    </td>';
                    })
                    ->removeColumn('name')
                    ->removeColumn('updated_at')
                    ->make();
                break;
                case 'pendingManager':
                $objuser = User::getInstance();
                $where = array('rawQuery' => 'role = ? and status = ?', 'bindParams' => [4, 0]);
                $pending_manager = $objuser->getPendingUserDetails($where);

                return Datatables::of($pending_manager)
                    ->addColumn('status', function ($pending_manager) {

                        return ' <td> <span class="dropdown" style="position:absolute; margin-top:-16px;">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
                                                    data-id = ' . $pending_manager->role . ' aria-expanded="true">Pending<span class="caret" style="margin-left:4px;"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                          <li><a data-id=' . $pending_manager->id . '  class="productapproved">Approved</a></li>
                                          <li><a data-id=' . $pending_manager->id . '  class="productrejected">Rejected</a></li>

                                       </ul>
                                         </span>

                                    </td > ';
                    })
                    ->removeColumn('role')
                    ->removeColumn('updated_at')
                    ->make();

                break;
            case 'getPermissionDetails':
                $objPermissionModel = Permissions::getInstance();
                $objPermissionUserRelation = PermissionUserRelation::getInstance();
                $userId = $request->input('UserId');
                $where = ['rawQuery' => 'user_id = ?', 'bindParams' => [$userId]];
                $selectedColumns = ['permission_user_relation.*'];
                $availPermissionRelation = $objPermissionUserRelation->getPermissiondetailsByUserId($where, $selectedColumns);

//                    foreach ($availPermissionRelation as $filtergroupkey => $filtergroupvalue) {
//                        $availPermissionRelation[$filtergroupkey]->filtergroup = array();
                if ($availPermissionRelation[0]->permission_ids != '') {
//                            $catfilterName = array_values(array_unique(explode(',', $filtergroupvalue->permission_ids)));
//                            $per = implode(",", $catfilterName);
                    $where = ['rawQuery' => 'permission_id IN(' . $availPermissionRelation[0]->permission_ids . ')'];
                    $getcategory = $objPermissionModel->getPermissionNameByIds($where);
                    foreach ($getcategory as $catkey => $catval) {
                        $availPermissionRelation[$catkey] = $catval;
                    }
                }
                $expo = explode(",", $availPermissionRelation[0]->permission_details);
//                    }
                echo json_encode($expo);
                break;
            case 'deleteStatus':
                $objPermissionUserRelation = PermissionUserRelation::getInstance();
                $userId = $inputData['UserId'];
                $where = ['rawQuery' => 'id = ?', 'bindParams' => [$userId]];
                $deleteStatus = $ObjUser->deleteUserDetails($where);
                if ($deleteStatus) {
                    $where = ['rawQuery' => 'user_id = ?', 'bindParams' => [$userId]];
                    $deleteUserPermission = $objPermissionUserRelation->deleteUserPermission($where);
                    if ($deleteUserPermission) {
                        echo json_encode(['status' => 'success', 'msg' => 'User Deleted']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again . ']);

                }
                break;
            default:
                break;
        }
    }

    }


    public function editManager(Request $request, $mid)
    {

        $ObjPermissions = Permissions::getInstance();
        $ObjUser = User::getInstance();
        $postData = $request->all();
        $ObjPermissionUserRelation = PermissionUserRelation::getInstance();
        if ($request->isMethod('GET')) {
            $where = ['rawQuery' => 'permission_id  NOT IN (1)'];  //ToDo // Permission id not to be fetched from query //
            $permissionDetails = $ObjPermissions->getAllPermissions($where);
            $wherepermission = ['rawQuery' => 'id = ?', 'bindParams' => [$mid]];
            $permissionInfo = $ObjUser->getUserInfoById($wherepermission);
            //   $catfilterName = array_unique(explode(',', $permissionInfo->permission_ids));
            $wherepermit = ['rawQuery' => 'permission_id IN(' . $permissionInfo->permission_ids . ')'];
            $Info = $ObjPermissions->getPermitDetail($wherepermit);
            foreach ($Info as $key => $value) {
                $permissionIds[$key] = $value->permission_id;
            }


            return view('Admin/Views/manager/editManager', ['permissionlist' => $permissionDetails, 'permissionInfo' => $permissionInfo, 'info' => $permissionIds]);

        } elseif ($request->isMethod('POST')) {

            $rules = array(
                'username' => 'unique:users,username.' . $mid . ',id',
            );

            $data['name'] = $postData['firstname'];
            $data['last_name'] = $postData['lastname'];
            $data['username'] = $postData['username'];
            $data['email'] = $postData['email'];
//            $where = ['rawQuery' => 'id = ?', 'bindParams' => [$mid]];
            //$where['id'] = $mid;
            $updateUser = $ObjUser->updateUserInfo($data, $mid);

            if ($updateUser) {
                $temp = array();
                $cat = $postData['permitcheck'];
                foreach ($cat as $catkey => $catval) {
                    $category[$catkey] = $catval;
                }

//            $FilterGroup = $ObjProductCategory->getCategoryInfoById($category);
                $where = ['rawQuery' => 'user_id = ?', 'bindParams' => [$mid]];
                $FilterGroup = $ObjPermissionUserRelation->getPermissionDetailsById($where);
                $catdata = $FilterGroup[0]->permission_ids;
                $cata = explode(",", $catdata);
                $categoryIds = implode(',', $category);
                array_push($cata, $categoryIds);
                //$catmain = implode(",", $cata);
                $data1['permission_ids'] = $categoryIds;
                $updatePermission = $ObjPermissionUserRelation->updatePermissionInfo($data1, $where);
            }
            if ($updateUser && $updatePermission) {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Successfully Edited ']);
            } else {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Some Error']);
            }
        }


    }


    public function pendingManager(Request $request)
    {


        return view('Admin/Views/manager/pendingManager');
    }


}