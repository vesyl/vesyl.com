<?php
namespace FlashSale\Http\Modules\Admin\Controllers;

use FlashSale\Http\Modules\Admin\Models\MailTemplate;
use FlashSale\Http\Modules\Admin\Models\Usersmeta;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use DB;
use PDO;
use Input;
use Datatables;
use FlashSale\Http\Modules\Admin\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use stdClass;
use Illuminate\Support\Facades\Hash;
use SendGrid\Email;
use SendGrid\Mail;
require_once public_path().'/../vendor/autoload.php';
class CustomerController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function availableCustomer(Request $request)
    {

        return view("Admin/Views/customer/availableCustomer");
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function customerAjaxHandler(Request $request)
    {
        $inputData = $request->input();
        $method = $request->input('method');
        $ObjUser = User::getInstance();
        $ObjUsermeta = Usersmeta::getInstance();
        $mainId = Session::get('fs_admin')['id'];
        if ($method) {
            switch ($method) {
                case "availableCustomer":
                    $objuser = User::getInstance();
                    $available_customers = $objuser->getAvailableCustomerDetails();
                    return Datatables::of($available_customers)
                        ->addColumn('action', function ($available_customers) {
                            return '<span class="tooltips" title="Edit User Details." data-placement="top"> <a href="/admin/edit-customer/' . $available_customers->id . '" class="btn btn-sm grey-cascade" style="margin-left: 10%;">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            </span> &nbsp;&nbsp;
                                            <span class="tooltips" title="Delete User Details." data-placement="top"> <a href="#" data-cid="' . $available_customers->id . '" class="btn btn-danger delete-user" style="margin-left: 10%;">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </span>';
                        })
                        ->addColumn('status', function ($available_customers) use ($mainId) {

                            $button = '<td style="text-align: center">';
                            $button .= '<button class="btn ' . (($available_customers->status == 1) ? "btn-success" : "btn-danger") . ' customer-status" data-id="' . $available_customers->id . '"  data-set-by="' . $mainId . '">' . (($available_customers->status == 1) ? "Active" : "Inactve") . ' </button>';
                            $button .= '<td>';
                            return $button;
                        })
                        ->removeColumn('name')
                        ->removeColumn('updated_at')
                        ->make();
                    break;
                case 'changeCustomerStatus':
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
                case 'deleteUserStatus':
                    $userId = $inputData['UserId'];
                    $where = ['rawQuery' => 'id = ?', 'bindParams' => [$userId]];
                    $dataToUpdate['status'] = 4;
                    $dataToUpdate['status_set_by'] =$userId;
                    $updateResult=$ObjUser->updateUserWhere($dataToUpdate,$where);
//                    $deleteStatus = $ObjUser->deleteUserDetails($where,$dataToUpdate);
                    if ($updateResult) {
                        $where = ['rawQuery' => 'user_id = ?', 'bindParams' => [$userId]];
                        $deleteCustomer = $ObjUsermeta->deleteCustomerDetails($where);
                        if ($deleteCustomer) {
                            echo json_encode(['status' => 'success', 'msg' => 'User Deleted']);
                        }
                        } else {
                            echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);

                        }

                    break;
                case 'pendingCustomer':
                    $objuser = User::getInstance();
                    $where = array('rawQuery' => 'role = ? and status = ?', 'bindParams' => [1, 0]);
                    $pending_customers = $objuser->getPendingUserDetails($where);

                    return Datatables::of($pending_customers)
                        ->addColumn('status', function ($pending_customers) {

                            return ' <td> <span class="dropdown" style="position:absolute; margin-top:-16px;">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
                                                    data-id = ' . $pending_customers->role . ' aria-expanded="true">Pending<span class="caret" style="margin-left:4px;"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                          <li><a data-id=' . $pending_customers->id . '  class="productapproved">Approved</a></li>
                                          <li><a data-id=' . $pending_customers->id . '  class="productrejected">Rejected</a></li>

                                       </ul>
                                         </span>

                                    </td > ';
                        })
                        ->removeColumn('role')
                        ->removeColumn('updated_at')
                        ->make();

                case 'updateCustomer':
                    $customerId = $request->CustomerId;
                    $status = $request->Status;
                    $user = DB::table('users')
                        ->where('id', $customerId)
                        ->update(['status' => $status]);

                case 'deletedCustomer':
                    $objuser = User::getInstance();
                    $where = array('rawQuery' => 'role = ?', 'bindParams' => [1]);
                    $status = array('rawQuery' => 'status = ?', 'bindParams' => [4]);
                    $deleted_customers = $objuser->getDeletedUserDetails($where, $status);

                    return Datatables::of($deleted_customers)
                        ->addColumn('status', function ($deleted_customers) {
                            return '<td style="text-align: center">
                                            <button class="btn btn-primary customer-status"
                                                    data-id=' . $deleted_customers->id . '>Deleted
                                            </button>

                                    </td>';
                        })
                        ->removeColumn('name')
                        ->removeColumn('updated_at')
                        ->make();
                    break;
                default :
                    break;
            }
        }
    }


    /**
     * Add New Customers Action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Vini Dubey
     */
    public function addNewCustomer(Request $request)
    {

        $response = new stdClass();
        if ($request->isMethod("POST")) {

            $postData = $request->all();
            $email = $request->input('email');
            $rules = array(
                'firstname' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
                'lastname' => 'required|regex:/^[A-Za-z  0-9]+$/|max:255',
                'username' => 'required|regex:/^[A-Za-z0-9._\s]+$/|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users'
            );
            $messages = [
                'firstname.regex' => 'The :attribute cannot contain special characters.',
                'lastname.regex' => 'The :attribute cannot contain special characters.',
                'username.regex' => 'The :attribute cannot contain special characters.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect('/admin/add-new-customer')
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
                $supplier = User::create([
                    'name' => $postData['firstname'],
                    'last_name' => $postData['lastname'],
                    'email' => $postData['email'],
                    'password' => Hash::make($password),
                    'role' => '1',
                    'status' => '0',
                    'username' => $postData['username']
                ]);


                if ($supplier) {
                    $temp_name = "signup_success_mail";
                    $objMailTemplate = MailTemplate::getInstance();
                    $mailTempContent = $objMailTemplate->getTemplateByName($temp_name);

//                    $key = env('MANDRILL_KEY');
//                    $mandrill = new Mandrill($key);
//                    $async = false;
//                    $ip_pool = 'Main Pool';
//                    $message = array(
//                        'html' => $mailTempContent->temp_content,
//                        'subject' => "Registration Successful",
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
//                                    )
//                                )
//                            )
//                        ),
//                    );
                    $from = new \SendGrid\Email(null, "support@flashsale.com");
                    $subject = "Registration Successful";
                        $to = new  \SendGrid\Email(null, $postData['email']);
                        $content = new \SendGrid\Content("text/html", "<!doctype html>
               <html>
               <p>$mailTempContent->temp_content </p>
                </html>");
                        $mail = new  \SendGrid\Mail($from, $subject, $to, $content);

                        $apiKey = env('SENDGRID_API_KEY');
                        $sg = new \SendGrid($apiKey);

                        $response = $sg->client->mail()->send()->post($mail);
                        if ($postData['email'] == "sent" || $response->_status_code==202) {
                            return Redirect::back()->with(['status' => 'success', 'msg' => 'Mail sent successfully to  ' . $postData['firstname']]);

                        } else {
                            $objuser = new User();
                            $whereForUpdate = [
                                'rawQuery' => 'id =?',
                                'bindParams' => [$supplier->id]
                            ];
                            $deleteUser = $objuser->deleteUserDetails($whereForUpdate);
                            return Redirect::back()->with(['status' => 'success', 'msg' => 'Failed To Send Mail.']);

                        }


                }

            }
        }
            return view('Admin/Views/customer/addNewCustomer');
        }


    /**
     * Edit Customer Action
     * @param Request $request
     * @param $uid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Vini Dubey
     */
    public function editCustomer(Request $request, $uid)
    {
        $postdata = $request->all();
        $ObjUser = User::getInstance();
        if ($request->isMethod("GET")) {
            $customerDetails = $ObjUser->getUserById($uid);
            return view('Admin/Views/customer/editCustomer', ['userdetail' => $customerDetails]);
        } else if ($request->isMethod("POST")) {


            $data['name'] = $postdata['firstname'];
            $data['last_name'] = $postdata['lastname'];
            $data['email'] = $postdata['email'];
            $data['username'] = $postdata['username'];

            $result = $ObjUser->updateUserInfo($data, $uid);
            if ($result) {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Details Suuccesfully Edited.']);
            } else {
                return Redirect::back()->with(['status' => 'success', 'msg' => 'Some Error Occured.']);
            }

        }


    }

    /**
     * Pending Customer Action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pendingCustomer(Request $request)
    {

        return view('Admin/Views/customer/pendingCustomer');

    }

    /**
     * Deleted Customer Action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deletedCustomer(Request $request)
    {

        return view('Admin/Views/customer/deletedCustomer');

    }

}