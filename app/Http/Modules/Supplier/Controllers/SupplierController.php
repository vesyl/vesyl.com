<?php

namespace FlashSale\Http\Modules\Supplier\Controllers;

use FlashSale\Http\Modules\Supplier\Models\Languages;
use Illuminate\Http\Request;

use FlashSale\Http\Requests;
use FlashSale\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Image;
use Validator;
use Input;
use Redirect;
use File;
use Illuminate\Support\Facades\Storage;

use FlashSale\Http\Modules\Supplier\Models\User;
use FlashSale\Http\Modules\Supplier\Models\Usersmeta;
use Illuminate\Support\Facades\Session;
use FlashSale\Http\Modules\Supplier\Models\ProductCategory;
use FlashSale\Http\Modules\Supplier\Models\Location;
use FlashSale\Http\Modules\Supplier\Models\Shop;
use FlashSale\Http\Modules\Supplier\Models\ShopMetadata;
use Datatables;

use SendGrid\Email;
use SendGrid\Mail;

require public_path() . '/../vendor/autoload.php';

/**
 * Class SupplierController
 * @package FlashSale\Http\Modules\Supplier\Controllers
 * @author Dinanath Thakur <dinanaththakur@globussoft.in>
 */
class SupplierController extends Controller
{


    private $imageWidth = 1024;//TO BE USED FOR IMAGE RESIZING
    private $imageHeight = 1024;//TO BE USED FOR IMAGE RESIZING


    /**
     * Dashboard action
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Redirect
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function dashboard()
    {
        if (!Session::has('fs_supplier')) {
//            print_r('dashboard');
//            dd(Session::all());
            return redirect('/supplier/login');
        }
//        dd('session has');

        return view("Supplier/Views/supplier/dashboard");

    }

    /**
     * Login action
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|Redirect
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function login(Request $request)
    {


        $urlmain = $_SERVER['HTTP_HOST'];
        $domain = env("WEB_URL");
        $shopname = "";

        $shopurl = explode("." . $domain, $urlmain);
        $shopname = $shopurl[0];




//        dd(Session::has('fs_supplier'));
        if (Session::has('fs_supplier')) {
            return redirect('/supplier/dashboard');
        }

        if ($request->isMethod('post')) {



//           DB::statement("create table alokkk.users SELECT * from vesyl.users");
//            $this->cloneDatabase("vesyl","hhhh");
//            dd("here");

            $remember = $request['remember'] == 'on' ? true : false;

            $emailOrUsername = $request->input('emailOrUsername');
            $password = $request->input('password');

            $this->validate($request, [
                'emailOrUsername' => 'required',
                'password' => 'required',
            ], ['emailOrUsername.required' => 'Please enter email address or username',
                    'password.required' => 'Please enter a password']
            );


            $field = 'username';
            if (strpos($emailOrUsername, '@')) {
                $field = 'email';
            }
            //todo check shopname and subdomain name and allow login
            if (Auth::attempt([$field => $emailOrUsername, 'password' => $password], $remember)) {


                $objModelUsers = User::getInstance();
                $userDetails = $objModelUsers->getUserById(Auth::id());

                if ($userDetails->role == 3) {
                    if ($userDetails->status == 1) {

                        if ($userDetails->for_shop_name == $shopname) {



                            Session::put('fs_supplier', $userDetails['original']);
                            return redirect('/supplier/dashboard');
                        } else {
                            return redirect('/supplier/login');
                        }
                    }
                    if ($userDetails->status == 0) {

                        $where['users.id'] = Auth::id();
                        $uesrData = $objModelUsers->getUserDetailsWhere($where);
                        if (!$uesrData) {
//                            dd(Session::all());
                            Session::put('fs_supplier', $userDetails['original']);
                            return redirect('/supplier/supplierdetails');
                        } else {
                            return view("Supplier/Views/supplier/login")->withErrors([
                                'errMsg' => 'This account is pending for Admin approval.',
                            ]);
                        }
                    } else {
                        return view("Supplier/Views/supplier/login")->withErrors([
                            'errMsg' => 'This account is reatricted from logging in.',
                        ]);
                    }
                } else {
                    return view("Supplier/Views/supplier/login")->withErrors([
                        'errMsg' => 'Invalid credentials.',
                    ]);
                }
            } else {
                return view("Supplier/Views/supplier/login")->withErrors([
                    'errMsg' => 'Invalid credentials.',
                ]);
            }
        }
        return view("Supplier/Views/supplier/login");

    }

    /**
     * Forgot action
     */


    public static function forgotpassword(Request $data)
    {
        if ($data->isMethod('post')) {
            $email1 = $data->input('email');
            $ss = $resetcode = mt_rand(1000000, 9999999);
            $user_model = User::getInstance();
            $password = $resetcode;
            $data = ['reset_code' => $password];
            $where = ['rawQuery' => 'email=?', 'bindParams' => [$email1]];
            $result = $user_model->updateUserWhere1($data, $where);

            $from = new \SendGrid\Email(null, "support@flashsale.com");
            $subject = "Forgot password!";
            $to = new \SendGrid\Email(null, $email1);
            $content = new \SendGrid\Content("text/html", "<!doctype html>
               <html>
               <p>$resetcode </p>
                </html>");
            $mail = new \SendGrid\Mail($from, $subject, $to, $content);

            $apiKey = env('SENDGRID_API_KEY');
            $sg = new \SendGrid($apiKey);

            $response = $sg->client->mail()->send()->post($mail);
            if ($response->statusCode() == 202) {
                echo json_encode(['status' => 200]);
            }
        }

    }

    public function submit(Request $request)
    {

        if ($request->isMethod('post')) {
            $user_model = User::getInstance();
            $userDetails = $user_model->getDetails($request->email, $request->password);
            // $userDetails ==ewtuening null u dooo now ok
            //THIS IS TO GET THE MODEL OBJECT
            if ($userDetails) {
                return 1;
            } else {
                return 0;

            }
        }
    }

    public function changepassword(Request $request)
    {
        $user_model = User::getInstance();

        if ($request->isMethod('post')) {
//            dd($request);
            $email = $request->get('email');
            $resetcode = $request->get('password');
            $userDetails = $user_model->getDetails($email, $resetcode);

            if ($request->get('newpassword') == $request->get('ConfirmPassword')) {
                $password = Hash::make($request->get('newpassword'));
                $data = ['password' => $password];
                $where = ['bindParams' => [$email]];
                $result = $user_model->updateUserInfo($data, $where);
                return 1;
            } else {
                return 0;

            }


        }

    }

    /**
     * Register action
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|Redirect
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function register(Request $request)
    {

        $supplier = "";
//        if (Session::has('fs_supplier')) {
//            return redirect('/supplier/dashboard');
//        }

        if ($request->isMethod('post')) {


            $rules = array(
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'shop_name' => 'required|max:255',
                'username' => 'required|max:255',
                'password' => 'required',
                'password_confirm' => 'required|same:password',
                'terms_and_policy' => 'accepted'
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                try {
                    $password = bcrypt($request['password']);

                    $supplier = User::create([
                        'name' => $request['first_name'],
                        'last_name' => $request['last_name'],
                        'for_shop_name' => $request['shop_name'],
                        'email' => $request['email'],
                        'password' => $password,
                        'role' => '3',
                        'username' => $request['username'],
                        'profilepic' => '/assets/images/avatar-placeholder.jpg'
                    ]);


                    $createdb = createDB($request['shop_name']);


                    $shop_id = $supplier->id;
                    $shop_name = $request['shop_name'];
                    $this->insertIntoSupplierShops($shop_id, $shop_name);
                    $this->insertIntoSupplierUsers($request['first_name'],$request['last_name'],$request['shop_name'],$request['email'],$password,$request['username']);


//                    $this->insertIntoSupplier($tempdb, $data);
                    //todo trim spaces remove special chars small letters
//                           $updateshop = $shopobject->insertUserId($data);


                    if ($supplier) {

                        $url = "http://localvesyl.com/supplier/register";
//                        Auth::login($supplier);
                        $objModelUsers = User::getInstance();
                        $objModelShop = Shop::getInstance();


//                        $userDetails = $objModelUsers->getUserById(Auth::id());
//
//                        $where['users.id'] = Auth::id();

                        $shopDetails = array();
                        $shopDetails["user_id"] = $supplier->id;
                        $shopDetails["shop_name"] = $request['shop_name'];


                        $userData = $objModelShop->addShopData($shopDetails);

                        $decodeUserData = json_decode($userData, true);

                        if ($decodeUserData["code"] == 200) {
                            $url = 'http://' . $request['shop_name'] . '.localvesyl.com/supplier/login';
                            return redirect($url);

                        }
                        return redirect($url);


                        //todo redirect to subdomain.vesyl.com/supplier/login
//                        Session::put('fs_supplier', $userDetails['original']);
//                        return redirect()->intended('supplier/supplierdetails');
                    } else {
                        return view("Supplier/Views/supplier/register")->withErrors([
                            'registerErrMsg' => 'Something went wrong, please try again.',
                        ]);
                    }
                } catch (\Exception $ex) {
                    dd($ex->getMessage());
                    return redirect()->back()->with('exception', 'An exception occurred, please reload the page and try again.');
                }

            }
        }
        return view("Supplier/Views/supplier/register");
    }


    /**
     * Logout action
     * @return Redirect
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function logout()
    {
        Session::forget('fs_supplier');
        return redirect('/supplier/login');
    }

    /**
     * Profile page action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \FlashSale\Http\Modules\Supplier\Models\Exception
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function profile(Request $request)
    {
        $objModelUser = User::getInstance();
        $objModelUsersmeta = Usersmeta::getInstance();
        $objLocationModel = Location::getInstance();

        $where['users.id'] = Session::get('fs_supplier')['id'];
        $uesrDetails = $objModelUser->getUserDetailsWhere($where);
        $whereforCountry = ['rawQuery' => 'is_visible =? and location_type =?', 'bindParams' => [0, 0]];
        $allCountry = $objLocationModel->getAllLocationsWhere($whereforCountry);
        $whereforstate = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 1]];
        $allstates = $objLocationModel->getAllLocationsWhere($whereforstate);
        $whereforcity = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 2]];
        $allcities = $objLocationModel->getAllLocationsWhere($whereforcity);
//        dd($allCountry);
        $userId = Session::get('fs_supplier')['id'];

        $where1 = [
            'rawQuery' => 'usersmeta.user_id=?',
            'bindParams' => [$userId]
        ];
        $select = array('usersmeta.*',
            'lt1.location_id as CountryId', 'lt1.name as CountryName',
            'lt2.location_id as StateId', 'lt2.name as StateName',
            'lt3.location_id as CityId', 'lt3.name as CityName');
        $userdata = $objModelUsersmeta->getUsersMDWhere($where1, $select);

//        $updatedMetaDataResult = $objModelUsersmeta->getUsersMetaDetailsWhere1($where1,$select);
        if ($uesrDetails) {
            return view('Supplier/Views/supplier/profile', ['uesrDetails' => $uesrDetails, 'usernames' => $userdata, 'country' => $allCountry, 'state' => $allstates, 'city' => $allcities]);
        } else {
            return redirect()->intended('supplier/supplierdetails');
        }
    }

    /**
     * Supplier details action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \FlashSale\Http\Modules\Supplier\Models\Exception
     * @since 12-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function supplierDetails(Request $request)
    {

        if (!Session::has('fs_supplier')) {
            return redirect('/supplier/login');
        }
        $objModelUser = User::getInstance();
        $objLocationModel = Location::getInstance();


        $where['users.id'] = Session::get('fs_supplier')['id'];
        $uesrDetails = $objModelUser->getUserDetailsWhere($where);
        if (isset($uesrDetails->user_id)) {
            return redirect()->intended('supplier/dashboard');
        }

        //NOT YET COMPLETE, NEED COUNTRY DETAILS
        if ($request->isMethod('post')) {
            $rules = array(
                'addressline1' => 'required|max:255',
                'addressline2' => 'required|max:255',
                'city' => 'required|max:255',
                'state' => 'required|max:255',
                'country' => 'required|max:255',
                'zipcode' => 'required',
                'phone' => 'required|regex:/^\+?[^a-zA-Z]{5,}$/|unique:usersmeta,phone' //Regex format: +359878XXX, +359 878 XXX, +359 87 8X XX, +(359) 878 XXX, 0878-XX-XX-XX.
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                try {
                    $supplierDetails = Usersmeta::create([
                            'user_id' => Session::get('fs_supplier')['id'],
                            'addressline1' => $request->input('addressline1'),
                            'addressline2' => $request->input('addressline2'),
                            'city' => $request->input('city'),
                            'state' => $request->input('state'),
                            'country' => $request->input('country'),//NEED COUNTRY DETAILS, FROM DATABASE
                            'zipcode' => $request->input('zipcode'),
                            'phone' => $request->input('phone')
                        ]
                    );
                    if ($supplierDetails) {

                        //return view("Supplier/Views/supplier/supplierDetails", ['success_msg' => "Registration Successful, Waiting for Admin approval."]);
                        return redirect('/supplier/dashboard');
                        // return redirect()->intended('supplier/dashboard');
//                        echo '<pre>';
//                        print_r($supplierDetails);
                    } else {
                        return redirect()->back()->with('error', 'Something went wrong, please try again.')->withInput();
                    }
                } catch (\Exception $ex) {
                    return redirect()->back()->with('error', 'An exception occurred, please reload the page and try again.')->withInput();
                }

            }
        }

        if (!$request->isMethod('post')) //this behaves as get
        {
//            print_r('supplier');
//            dd(Session::all());

            $where = ['rawQuery' => 'is_visible =? and location_type =?', 'bindParams' => [0, 0]];
            $select = array('location_id', 'name', 'location_type');
            $countries = $objLocationModel->getAllLocationsWhere($where, $select);


            $whereforstate = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 1]];
            $allstates = $objLocationModel->getAllLocationsWhere($whereforstate);


            $whereforcity = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 2]];
            $allcities = $objLocationModel->getAllLocationsWhere($whereforcity);

            return view("Supplier/Views/supplier/supplierDetails", ['countries' => $countries, 'state' => $allstates, 'city' => $allcities]);
        }
    }

    /**
     * Ajax handler
     * @param Request $request
     * @throws \FlashSale\Http\Modules\Supplier\Models\Exception
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function ajaxHandler(Request $request)
    {
        $objModelUser = User::getInstance();
        $objModelUsersmeta = Usersmeta::getInstance();


        $userId = Session::get('fs_supplier')['id'];

        $where['user_id'] = $userId;
        $usersMetaDetails = $objModelUsersmeta->getUsersMetaDetailsWhere($where);

        $field = $request->input('name');
        if ($field) {
            $formEditableMethod = explode('/', $field);
            $method = $formEditableMethod[0];
        } else {
            $method = $request->input('method');
        }
        $objLocationModel = Location::getInstance();
        $objshopModal = Shop::getInstance();
        $objshopMetadataModal = ShopMetadata::getInstance();

        switch ($method) {
            case 'checkContactNumber':
                $validator = Validator::make($request->all(), ['contact_number' => 'required|max:10|unique:usersmeta,phone,' . $usersMetaDetails->id]);
                if ($validator->fails()) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                break;


            case 'updateProfileInfo': //NOT YET COMPLETE, NEED COUNTRY DETAILS
                $rules = array(
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'city' => 'required',
                    'state' => 'required',
                    'zipcode' => 'required',
                    'country' => 'required',
                    'contact_number' => 'required|max:10|unique:usersmeta,phone,' . $usersMetaDetails->id
                );
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    echo json_encode(array('status' => 'error', 'message' => $validator->messages()->all()));
                } else {


                    $userdetails = Session::get('fs_supplier');
                    $userdetails['name'] = $request->input('first_name');
                    $userdetails['last_name'] = $request->input('last_name');
                    Session::put('fs_supplier', $userdetails);


                    $whereForUpdate['id'] = $userId;
                    $updateData['name'] = $request->input('first_name');
                    $updateData['last_name'] = $request->input('last_name');

                    $updatedResult = $objModelUser->updateUserWhere($updateData, $whereForUpdate);

                    $updateMetaData['addressline1'] = $request->input('address_line_1');
                    $updateMetaData['addressline2'] = $request->input('address_line_2');
                    $updateMetaData['city'] = $request->input('city');
                    $updateMetaData['state'] = $request->input('state');
                    $updateMetaData['country'] = $request->input('country');//COUNTRY DETAILS IN DATABASE
                    $updateMetaData['zipcode'] = $request->input('zipcode');
                    $updateMetaData['phone'] = $request->input('contact_number');
                    $whereForUpdateMetaData['id'] = $usersMetaDetails->id;
                    $updatedMetaDataResult = $objModelUsersmeta->updateUsersMetaDetailsWhere($updateMetaData, $whereForUpdateMetaData);
                    if ($updatedResult || $updatedMetaDataResult) {
                        echo json_encode(array('status' => 'success', 'message' => 'Successfully updated profile data.'));
                    } else {
                        echo json_encode(array('status' => 'success', 'message' => 'Save changes succesfully.'));
                    }
                }

                break;
            case 'updateAvatar':
                if (Input::hasFile('file')) {
                    $validator = Validator::make($request->all(), ['file' => 'image']);
                    if ($validator->fails()) {
                        echo json_encode(array('status' => 2, 'message' => $validator->messages()->all()));
                    } else {
                        $filePath = uploadImageToStoragePath(Input::file('file'), 'useravatar', 'useravatar_' . $userId . '_' . time() . ".jpg");
                        if ($filePath) {
                            $updateData['profilepic'] = $filePath;
                            $whereForUpdate['id'] = $userId;
                            $updatedResult = $objModelUser->updateUserWhere($updateData, $whereForUpdate);
                            if ($updatedResult) {
                                if (!strpos(Session::get('fs_supplier')['profilepic'], 'placeholder')) {
                                    deleteImageFromStoragePath(Session::get('fs_supplier')['profilepic']);
                                }
                                Session::put('fs_supplier.profilepic', $filePath);
                                echo json_encode(array('status' => 1, 'message' => 'Successfully updated profile image . '));
                            } else {
                                echo json_encode(array('status' => 0, 'message' => 'Something went wrong, please reload the page and try again.'));
                            }
                        } else {
                            echo json_encode(array('status' => 0, 'message' => 'Something went wrong, please reload the page and try again.'));
                        }
                    }
                } else {
                    echo json_encode(array('status' => 2, 'message' => 'Please select file first.'));
                }
                break;

            case 'updatePassword':


                Validator::extend('passwordCheck', function ($attribute, $value, $parameters) {
                    return Hash::check($value, Auth::user()->getAuthPassword());
                }, 'Your current password is incorrect');

                $passwordRules = array(
                    'current_password' => 'required | passwordCheck',
                    'new_password' => 'required',
                    'confirm_password' => 'required | same:new_password'
                );

                $passwordValidator = Validator::make($request->all(), $passwordRules);
                if ($passwordValidator->fails()) {
                    echo json_encode(array('status' => 'error', 'message' => $passwordValidator->messages()->all(), 'error' => 198));
                } else {
                    $user = Auth::user();
                    $user->password = Hash::make($request->input('new_password'));
                    $user->save();
                    echo json_encode(array('status' => 'success', 'message' => 'Your password has been successfully updated . '));
                }
                break;
            case 'getState':
                $countryId = $request->input('countryId');
                $where = ['rawQuery' => 'is_visible =? and location_type =? and parent_id =?', 'bindParams' => [0, 1, $countryId]];
                $allstates = $objLocationModel->getAllLocationsWhere($where);
                echo json_encode($allstates);
                break;
            case 'getCity':
                $stateId = $request->input('stateId');
                $where = ['rawQuery' => 'is_visible =? and location_type =? and parent_id =?', 'bindParams' => [0, 2, $stateId]];
                $allcities = $objLocationModel->getAllLocationsWhere($where);
                echo json_encode($allcities);
                break;
            case 'available_Shop':
                $where = ['rawQuery' => 'shop_status <> ?', 'bindParams' => [4]];
                $available_Shops = $objshopModal->getAvailableShopDetails($where);
                return Datatables::of($available_Shops)
                    ->addColumn('action', function ($available_Shops) {
                        return '<span class="tooltips" title="Edit Shop Details." data-placement="top"> <a href="/supplier/editShop/' . $available_Shops->shop_id . '" class="btn btn-sm grey-cascade">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            </span> &nbsp;&nbsp;
                                            ';
                    })
                    ->addColumn('status', function ($available_Shops) {
                        $button = '<td style="text-align: center">';
//                        $button .= '<button class="btn ' . (($available_Shops->shop_status == "1" || $available_Shops->shop_status == "2") ? (($available_Shops->shop_status == "1") ? "btn-success" : "btn-danger") : "btn-default") . ' customer-status"  data-id="' . $available_Shops->shop_id . '"' . (($available_Shops->shop_status == "0" || $available_Shops->shop_status == "3") ? "disabled" : "") . '>' . (($available_Shops->shop_status == "1" || $available_Shops->shop_status == "2") ? (($available_Shops->shop_status == "1") ? "Active" : "Inactive") : (($available_Shops->shop_status == "0") ? "Pending" : "Rejected")) . ' </button>';
                        $button .= '<button class="btn ' . (($available_Shops->shop_status == "1" || $available_Shops->shop_status == "2") ? (($available_Shops->shop_status == "1") ? "btn-success" : "btn-danger") : "btn-default") . ' customer-status"  data_supplier_id="' . $available_Shops->user_id . '" data_shopMeta_id="' . $available_Shops->shop_metadata_id . '"  data-id="' . $available_Shops->shop_id . '"' . (($available_Shops->shop_status == "0" || $available_Shops->shop_status == "3") ? "disabled" : "") . '>' . (($available_Shops->shop_status == "1" || $available_Shops->shop_status == "2") ? (($available_Shops->shop_status == "1") ? "Active" : "Inactive") : (($available_Shops->shop_status == "0") ? "Pending" : "Rejected")) . ' </button>';
                        $button .= '</td>';
                        return $button;
                    })
                    ->removeColumn('shop_status')
                    ->removeColumn('user_id')
                    ->removeColumn('shop_metadata_id')
                    ->make();
                break;
            case 'changeShopStatus':
                $Shop_Id = $request->input('shopId');
                $Status = $request->input('status');
                $data1['shop_metadata_status'] = $Status;
                $where1 = ['rawQuery' => 'shop_metadata_id =? ', 'bindParams' => [$Shop_Id]];
                $updateResult = $objshopMetadataModal->updateShopMetadataWhere($data1, $where1);
                if ($updateResult) {

                    echo json_encode(['status' => 'success', 'msg' => 'Status has been changed.']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                }
                break;
            case 'updateStoreDetails':
                $field = $request->input('name');

                $field = explode('/', $field);
                $fieldName = $field[1];
                $store_metadata_id = $request->input('pk');
                $value = $request->input('value');
                $shopFlag = true;
                $data = array(
                    $fieldName => $value
                );
//                echo "<pre>";print_r($store_metadata_id);die;
                if ($fieldName == 'shop_type' && $value == '0') {//change shop_type to main
                    $merchantId = $field[2];
                    $shopId = $field[3];
                    $whereforShop = ['rawQuery' => 'shop_id =?', 'bindParams' => [$shopId]];
                    $data1 = array(
                        'shop_type' => 1
                    );
                    $updateStoreType = $objshopMetadataModal->updateShopMetadataWhere($data1, $whereforShop);
                }
                if ($fieldName == 'shop_type' && $value == '1') {//change shop_type to secondary
                    $merchantId = $field[2];
                    $shopId = $field[3];
                    $whereforShop = ['rawQuery' => 'shop_id =? and shop_metadata_id != ?', 'bindParams' => [$shopId, $store_metadata_id]];
                    $merchantStoreDetails = $objshopMetadataModal->getAllshopsMetadataWhere($whereforShop);
                    if (!empty($merchantStoreDetails)) {
                        $dataforstype = array(//Make other shop main
                            'shop_type' => 0
                        );
                        $whereforShopt = ['rawQuery' => 'shop_id =? and shop_metadata_id = ?', 'bindParams' => [$merchantStoreDetails[0]->shop_id, $merchantStoreDetails[0]->shop_metadata_id]];
                        $merchantStoreDetails = $objshopMetadataModal->updateShopMetadataWhere($dataforstype, $whereforShopt);
                    } else {
                        $shopFlag = false;
                        echo json_encode("You cant change main shop to secondary");
                        break;
                    }

                }
                if ($shopFlag) {
                    $whereforShopMeta = ['rawQuery' => 'shop_metadata_id =?', 'bindParams' => [$store_metadata_id]];
                    $updateResult = $objshopMetadataModal->updateShopMetadataWhere($data, $whereforShopMeta);
                    if ($updateResult) {
                        echo json_encode($updateResult);
                    }
                }
                break;
            case 'updateShopBanner':
                $shop_id = $request->input('shop_id');
                $whereforShop = ['rawQuery' => 'shop_id =? ', 'bindParams' => [$shop_id]];
                $selectedColumns = array('shop_id', 'shop_banner');
                $shopDetails = $objshopModal->getAllshopsWhereOld($whereforShop, $selectedColumns);
                if (isset($_FILES["shop_banner"]["name"]) && !empty($_FILES["shop_banner"]["name"])) {
                    $bannerFilePath = uploadImageToStoragePath(Input::file('shop_banner'), 'shopbanner', 'shopbanner_' . $userId . '_' . time() . ".jpg");
                } else {
                    $bannerFilePath = uploadImageToStoragePath($_SERVER['DOCUMENT_ROOT'] . "/assets/images/no-image.png", 'shopbanner', 'shopbanner_' . $userId . '_' . time() . ".jpg");
                }
                $shopdata = array(
                    'shop_banner' => $bannerFilePath
                );
                $updateBanner = $objshopModal->updateShopWhere($shopdata, $whereforShop);
                if ($updateBanner) {
                    deleteImageFromStoragePath($shopDetails[0]->shop_banner);
                    echo json_encode($updateBanner);
                }
                break;
            case 'updateShopLogo':
                $shop_id = $request->input('shop_id');
                $whereforShop = ['rawQuery' => 'shop_id =? ', 'bindParams' => [$shop_id]];
                $selectedColumns = array('shop_id', 'shop_logo');
                $shopDetails = $objshopModal->getAllshopsWhereOld($whereforShop, $selectedColumns);
                if (isset($_FILES["shop_logo"]["name"]) && !empty($_FILES["shop_logo"]["name"])) {
                    $logoFilePath = uploadImageToStoragePath(Input::file('shop_logo'), 'shoplogo', 'shoplogo_' . $userId . '_' . time() . ".jpg");
                } else {
                    $logoFilePath = uploadImageToStoragePath($_SERVER['DOCUMENT_ROOT'] . "/assets/images/no-image.png", 'shoplogo', 'shoplogo_' . $userId . '_' . time() . ".jpg");
                }
                $shopdata = array(
                    'shop_logo' => $logoFilePath
                );
                $updatelogo = $objshopModal->updateShopWhere($shopdata, $whereforShop);
                if ($updatelogo) {
                    deleteImageFromStoragePath($shopDetails[0]->shop_logo);
                    echo json_encode($updatelogo);
                }
                break;
            case 'updateSellerShop':
                $field = $request->input('name');
                $fieldName = explode('/', $field);
                $fieldName = $fieldName[1];

                $shop_id = $request->input('pk');
                $value = $request->input('value');

                $data = array(
                    $fieldName => $value
                );
                $whereforShop = ['rawQuery' => 'shop_id =? ', 'bindParams' => [$shop_id]];
                $updateResult = $objshopModal->updateShopWhere($data, $whereforShop);
                if ($updateResult) {
                    echo json_encode($updateResult);
                }

                break;
            case 'updateShopStatus':
                $shopMetaId = $request->input('shopMetaId');
                $status = $request->input('value');
                $supplierId = $request->input('supplierId');
                $data = array(
                    'sm_status_set_by' => $supplierId,
                    'shop_metadata_status' => $status
                );
                $whereforShopMeta = ['rawQuery' => 'shop_metadata_id =? ', 'bindParams' => [$shopMetaId]];
                $updateResult = $objshopMetadataModal->updateShopMetadataWhere($data, $whereforShopMeta);
                if ($updateResult) {
                    echo json_encode($updateResult);
                }

                break;
            default:
                break;
        }
    }

    /**
     * Ajax handler to be used for pre-login request
     * @param Request $request
     * @since 15-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function userAjaxHandler(Request $request)
    {
        $objLocationModel = Location::getInstance();
        $method = $request->input('method');
        switch ($method) {
            case 'checkUserName':
                $validator = Validator::make($request->all(), ['username' => 'required | unique:users,username']);
                if ($validator->fails()) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                break;

            case 'checkEmail':
                $validator = Validator::make($request->all(), ['email' => 'required | unique:users,email']);
                if ($validator->fails()) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                break;
            case 'checkShop':
                $validator = Validator::make($request->all(), ['shop_name' => 'required | unique:users,for_shop_name']);
                if ($validator->fails()) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                break;


            case 'checkpassword':
                $validator = Validator::make($request->all(), ['password' => 'required | min:8']);
                if ($validator->fails()) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
                break;
            case 'getStates':
                $countryId = $request->input('countryId');
                $where = ['rawQuery' => 'is_visible =? and location_type =? and parent_id =?', 'bindParams' => [0, 1, $countryId]];
                $allstates = $objLocationModel->getAllLocationsWhere($where);

                echo json_encode($allstates);
                break;
            case 'getCitys':
                $stateId = $request->input('stateId');
                $where = ['rawQuery' => 'is_visible =? and location_type =? and parent_id =?', 'bindParams' => [0, 2, $stateId]];
                $allcities = $objLocationModel->getAllLocationsWhere($where);
                echo json_encode($allcities);
                break;
            default:
                break;
        }
    }

    /**
     * @param Request $request
     */
    public function getImages(Request $request)
    {
        if (Input::hasFile('file')) {
            $userId = Session::get('fs_supplier')['id'];
            $objModelUser = User::getInstance();
            $destinationPath = 'uploads / useravatar / ';
            $filename = $userId . '_' . time() . ".jpg";
            File::makeDirectory(storage_path($destinationPath), 0777, true, true);
            $filePath = ' / ' . $destinationPath . $filename;
//                        echo"<pre>";print_r($filePath);die("fch");


            $quality = $this->imageQuality(Input::file('file'));

            Image::make(Input::file('file'))->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path($destinationPath . $filename), $quality);
            $whereForUpdate['id'] = $userId;
            $updateData['profilepic'] = $filePath;

            $updatedResult = $objModelUser->updateUserWhere($updateData, $whereForUpdate);
            if ($updatedResult) {
                if (!strpos(Session::get('fs_supplier')['profilepic'], 'placeholder')) {
                    File::delete(storage_path() . Session::get('fs_supplier')['profilepic']);
                }
//                            $path = storage_path().$filePath ;
//                            echo"<pre>";print_r($path);die("fch");
                Session::put('fs_supplier . profilepic', $filePath);
            }
        }
    }

    /**
     * Get image quality for compression
     * @param $image
     * @return int
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function imageQuality($image)
    {
        $imageSize = filesize($image) / (1024 * 1024);
        if ($imageSize < 0.5) return 70;
        elseif ($imageSize > 0.5 && $imageSize < 1) return 60;
        elseif ($imageSize > 1 && $imageSize < 2) return 50;
        elseif ($imageSize > 2 && $imageSize < 5) return 40;
        elseif ($imageSize > 5) return 30;
        else return 50;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addNewShop(Request $request)
    {

        $userId = Session::get('fs_supplier')['id'];
        $objCategoryModel = ProductCategory::getInstance();
        $objLocationModel = Location::getInstance();
        $objShopModel = Shop::getInstance();
        $objShopMetadataModel = ShopMetadata::getInstance();
        $whereforCategory = ['rawQuery' => 'category_status =? and parent_category_id =?', 'bindParams' => [1, 0]];
        $allCategories = $objCategoryModel->getAllCategoriesWhere($whereforCategory);

        $whereforCountry = ['rawQuery' => 'is_visible =? and location_type =?', 'bindParams' => [0, 0]];
        $allCountry = $objLocationModel->getAllLocationsWhere($whereforCountry);

        $whereforShop = ['rawQuery' => 'user_id =?', 'bindParams' => [$userId]];
        $allShop = $objShopModel->getAllshopsWhereOld($whereforShop);
        //echo "<pre>";print_r($allShop);die;
        /////////////////////////////Flag Set By admin side///////////////Todo- Flag Set By admin side
        $multiple_store_flag = 1; // Value 1 if flag is set
        $sub_store_flag = 1; // Value 1 if flag is set
        $parent_category_flag = 1; // Value 1 if flag is set
        /////////////////////////////////////////////////////////////////
        $flag['multiple_store_flag'] = $multiple_store_flag;
        $flag['sub_store_flag'] = $sub_store_flag;
        $flag['parent_category_flag'] = $parent_category_flag;
        $data['allCategories'] = $allCategories;
        $data['Country'] = $allCountry;
        $data['Shop'] = $allShop;

        if (!empty($allShop) && $multiple_store_flag != 1) {//Error msg if multiple shop not allowed and shopeady added
            return view("Supplier/Views/supplier/addNewShop", ['multiple_store_err' => "Shop already added, Can not add Multiple Shop"]);
        } else {
            $parentCategoryId = 0;
            if (isset($request['parent_category']) && !empty($request['parent_category'])) {
                $parentCategoryId = $request['parent_category'];
            }
            $parentShopId = "";
            if (isset($request['parent_shop']) && !empty($request['parent_shop'])) {
                $parentShopId = $request['parent_shop'];
            }

            if ($request->isMethod('post')) {

                if ($parentShopId == '') { //Sub store flag is not set
                    $rules = array(
                        'shop_name' => 'required'
                    );
                } else { //Sub store flag is set
                    $rules = array();
                }
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    try {
                        $addressLine1 = "";
                        if (isset($request['address_line_1'])) {
                            $addressLine1 = $request['address_line_1'];
                        }
                        $addressLine2 = "";
                        if (isset($request['address_line_2'])) {
                            $addressLine2 = $request['address_line_2'];
                        }
                        $country = "";
                        if (isset($request['country'])) {
                            $country = $request['country'];
                        }
                        $state = "";
                        if (isset($request['state'])) {
                            $state = $request['state'];
                        }
                        $city = "";
                        if (isset($request['city'])) {
                            $city = $request['city'];
                        }
                        $zipcode = "";
                        if (isset($request['zipcode'])) {
                            $zipcode = $request['zipcode'];
                        }

                        $shop_flag = 1;
                        if (isset($request['shop_flag'])) {
                            $shop_flag = $request['shop_flag'];
                        }
                        $show_shop = 2;
                        if (isset($request['show_shop'])) {
                            $show_shop = $request['show_shop'];
                        }

                        ////////////Upload Shop Banner Start///////////////////////
                        if (isset($_FILES["shop_banner"]["name"]) && !empty($_FILES["shop_banner"]["name"])) {
                            $bannerFilePath = uploadImageToStoragePath(Input::file('shop_banner'), 'shopbanner', 'shopbanner_' . $userId . '_' . time() . ".jpg");
                        } else {
                            $bannerFilePath = uploadImageToStoragePath($_SERVER['DOCUMENT_ROOT'] . "/assets/images/no-image.png", 'shopbanner', 'shopbanner_' . $userId . '_' . time() . ".jpg");
                        }

                        ////////////Upload Shop banner End///////////////////////

                        ////////////Upload Shop Logo Start///////////////////////
                        if (isset($_FILES["shop_logo"]["name"]) && !empty($_FILES["shop_logo"]["name"])) {
                            $logoFilePath = uploadImageToStoragePath(Input::file('shop_logo'), 'shoplogo', 'shoplogo_' . $userId . '_' . time() . ".jpg");
                        } else {
                            $logoFilePath = uploadImageToStoragePath($_SERVER['DOCUMENT_ROOT'] . "/assets/images/no-image.png", 'shoplogo', 'shoplogo_' . $userId . '_' . time() . ".jpg");
                        }

                        ////////////Upload Shop Logo End///////////////////////

                        if ($parentShopId == "") { //Sub store flag is not set
                            $shopdata = array(
                                'user_id' => $userId,
                                'shop_name' => $request['shop_name'],
                                'shop_banner' => $bannerFilePath,
                                'shop_logo' => $logoFilePath,
                                'parent_category_id' => $parentCategoryId,
                                'shop_flag' => $shop_flag,
                            );

                            $addShop = $objShopModel->addShop($shopdata);
                            $shop_id = $addShop;
                            $shopType = "0";
                        } else { //Sub store flag is set
                            $shopType = "1";
                            $shop_id = $parentShopId;
                        }

                        $shopMatadata = array(
                            'shop_id' => $shop_id,
                            'shop_type' => $shopType,
                            'address_line_1' => $addressLine1,
                            'address_line_2' => $addressLine2,
                            'city' => $city,
                            'state' => $state,
                            'country' => $country,
                            'zipcode' => $zipcode,
                            'added_date' => time(),
                            'show_shop_address' => $show_shop,
                            'shop_metadata_status' => 1
                        );

                        $addShop = $objShopMetadataModel->addShopMetadata($shopMatadata);
                        if ($addShop) {
                            if ($parentShopId == "") {
                                return redirect()->back()->with('shop_success_msg', 'Shop Added Successfully, Waiting for Admin Approval.');
                            } else {
                                return redirect()->back()->with('shop_success_msg', 'Shop Added Successfully.');
                            }
                        }
                    } catch (\Exception $ex) {
                        return redirect()->back()->with('exception', 'An exception occurred, please reload the page and try again.');
                    }

                }
            }
            return view("Supplier/Views/supplier/addNewShop", ['data' => $data], ['flag' => $flag]);
        }

    }

    public function shopList(Request $request)
    {

        return view("Supplier/Views/supplier/shopList");
    }

    public function editShop(Request $request, $shop_id)
    {
        $supplierId = Session::get('fs_supplier')['id'];

        if (is_numeric($shop_id)) {
            $objSupplierStore = Shop::getInstance();
            $objSupplierStoreMetaData = ShopMetadata::getInstance();
            $objLocationModel = Location::getInstance();
//            $objReview = Reviews::getInstance();//Need to be modified later for shop reviews
            $whereforShop = ['rawQuery' => 'user_id =? and shop_id = ?', 'bindParams' => [$supplierId, $shop_id]];
            $SupplierShopDetails = $objSupplierStore->getAllshopsWhereOld($whereforShop);
//            echo "<pre>";print_r($SupplierShopDetails);die;
            $SupplierData = array();
            $reviewData = array();
            if ($SupplierShopDetails) {
//                $this->view->merchantStoreDetails = $merchantStoreDetails;

                $shopId = $SupplierShopDetails[0]->shop_id;
                $whereforShopMeta = ['rawQuery' => 'shop_id =?', 'bindParams' => [$shopId]];
                $ShopMetaData = $objSupplierStoreMetaData->getAllshopsMetadataWhere($whereforShopMeta);

                $whereforcountry = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 0]];
                $allcountry = $objLocationModel->getAllLocationsWhere($whereforcountry);

                $whereforstate = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 1]];
                $allstates = $objLocationModel->getAllLocationsWhere($whereforstate);

                $whereforcity = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 2]];
                $allcities = $objLocationModel->getAllLocationsWhere($whereforcity);
//                $this->view->storeMetaData = $storeMetaData;
                $SupplierData['supplierId'] = $supplierId;
                $SupplierData['SupplierShopDetails'] = $SupplierShopDetails[0];
                $SupplierData['ShopMetaData'] = $ShopMetaData;
                $SupplierData['country'] = $allcountry;
                $SupplierData['state'] = $allstates;
                $SupplierData['city'] = $allcities;
//                echo "<pre>";print_r($SupplierData);die;

//                $reviews = $objReview->getStoreReviews($shopId);
                $reviews = "";
//                $this->view->reviews = $reviews;
                $r1 = $r2 = $r3 = $r4 = $r5 = 0;
                $rating = array($r5, $r4, $r3, $r2, $r1);
                $total = 0;
                $overAllRating = 0;
                if ($reviews != '') {
                    foreach ($reviews as $key => $value) {
                        if ($value['review_rating'] == 1)
                            $r1++;
                        elseif ($value['review_rating'] == 2)
                            $r2++;
                        elseif ($value['review_rating'] == 3)
                            $r3++;
                        elseif ($value['review_rating'] == 4)
                            $r4++;
                        elseif ($value['review_rating'] == 5)
                            $r5++;
                    }

                    $rating = array($r5, $r4, $r3, $r2, $r1);
                    $total = $r1 + $r2 + $r3 + $r4 + $r5;
                    $overAllRating = (1 * $r1 + 2 * $r2 + 3 * $r3 + 4 * $r4 + 5 * $r5) / $total;
                    $overAllRating = round($overAllRating, 1);
                }
                $reviewData['rating'] = $rating;
                $reviewData['total'] = $total;
                $reviewData['overAllRating'] = $overAllRating;

            } else {
//                $this->view->ErrorMsg = "Page you are looking for does not exist";
            }
            return view("Supplier/Views/supplier/editShop", ['shopData' => $SupplierData], ['reviewData' => $reviewData]);
        } else {
//            $this->view->ErrorMsg = "Page you are looking for does not exist";
        }
        return view("Supplier/Views/supplier/editShop");
    }


    /**
     * Changes the current language and returns to previous page
     * @return Redirect
     */
    public function changeLang(Request $request, $locale = null)
    {

        Session::put('locale', $locale);
        return Redirect::to(URL::previous());
    }


    public static function getLanguageDetails()
    {

        $ObjLanguageModel = Languages::getInstance();
        $selectColumn = ['languages.*'];
        $langInfo = $ObjLanguageModel->getAllLanguages($selectColumn);
        return $langInfo;

    }

    public function insertIntoSupplierShops($shop_id, $shop_name)
    {
//        $link = mysqli_connect(env("DB_HOST"), env("DB_USERNAME"), env("DB_PASSWORD"), $shop_name);
//        mysqli_ping($link);
//        if ($link === false) {
//            die("ERROR: Could not connect. " . mysqli_connect_error());
//        }
//        $sql = "INSERT INTO shops (shop_name,user_id) VALUES ('$shop_name','$shop_id')";
//        if (mysqli_query($link, $sql)) {
//
//            echo "Records inserted successfully.";
//        } else {
//            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
//        }
//        mysqli_close($link);

        //query using statemrnt

        DB::statement("INSERT INTO $shop_name.shops (shop_name,user_id) VALUES ('$shop_name','$shop_id') ");



    }

    public function insertIntoSupplierUsers($fname,$lname,$shopname,$email,$password,$username)
    {
//        $link = mysqli_connect(env("DB_HOST"), env("DB_USERNAME"), env("DB_PASSWORD"), $shopname);
//        mysqli_ping($link);
//        if ($link === false) {
//            die("ERROR: Could not connect. " . mysqli_connect_error());
//        }
//        $sql = "INSERT INTO users (name,last_name,username,email,password,for_shop_name,role) VALUES ('$fname','$lname','$username','$email','$password','$shopname','3')";
//        if (mysqli_query($link, $sql)) {
//            mysqli_close($link);
//            return;
//        } else {
//            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
//            return;
//        }

        //query using statemrnt

        DB::statement("INSERT INTO $shopname.users (name,last_name,username,email,password,for_shop_name,role) VALUES ('$fname','$lname','$username','$email','$password','$shopname','3')");



    }

// usage


}
