<?php

namespace Flashsale\Http\Modules\Buyer\Controllers;

use FlashSale\Http\Controllers\Controller;
use FlashSale\Http\Modules\Buyer\Models\Location;
use FlashSale\Http\Modules\Buyer\Models\Notification;
use FlashSale\Http\Modules\Buyer\Models\SettingSection;
use FlashSale\Http\Modules\Buyer\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Validation;
use Redirect;
use DB;
use Input;
use FlashSale\Http\Modules\Buyer\Models\Usersmeta;
use FlashSale\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class BuyerController extends  Controller
{
    public function dashboard(){
            if(!Session::has('fs_buyer')){
                return redirect('/buyer/login');
            }
        return view("Buyer/Views/buyer/dashboard");
    }


    public function login(Request $request)
    {
        if (Session::has('fs_buyer')) {
            return redirect('/buyer/dashboard');
        }

        if ($request->isMethod('post')) {
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
            if (Auth::attempt([$field => $emailOrUsername, 'password' => $password], $remember)) {
                $objModelUsers = User::getInstance();
                $userDetails = $objModelUsers->getUserById(Auth::id());
                if ($userDetails->role == 2) {
                    if ($userDetails->status == 1) {

                        Session::put('fs_buyer', $userDetails['original']);
                        return redirect('/buyer/dashboard');
                    }
                    if ($userDetails->status == 0) {
                        $where['users.id'] = Auth::id();
                        $uesrData = $objModelUsers->getUserDetailsWhere($where);
                        if (!$uesrData) {
                            Session::put('fs_buyer', $userDetails['original']);
                            return redirect('/buyer/buyerdetails');
                        } else {
                            return view("Buyer/Views/buyer/login")->withErrors([
                                'errMsg' => 'This account is pending for Admin approval.',
                            ]);
                        }
                    } else {
                        return view("Buyer/Views/buyer/login")->withErrors([
                            'errMsg' => 'This account is restricted from logging in.',
                        ]);
                    }
                } else {
                    return view("Buyer/Views/buyer/login")->withErrors([
                        'errMsg' => 'Invalid credentials.',
                    ]);
                }
            } else {
                return view("Buyer/Views/buyer/login")->withErrors([
                    'errMsg' => 'Invalid credentials.',
                ]);
            }
        }
        return view("Buyer/Views/buyer/login");

    }

    public function register(Request $request)
    {

        if (Session::has('fs_buyer')) {
            return redirect('/buyer/dashboard');
        }

        if ($request->isMethod('post')) {


            $rules = array(
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
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
                    $buyer = User::create([
                        'name' => $request['first_name'],
                        'last_name' => $request['last_name'],
                        'email' => $request['email'],
                        'password' => bcrypt($request['password']),
                        'role' => '2',
                        'username' => $request['username'],
                        'profilepic' => '/assets/images/avatar-placeholder.jpg'
                    ]);

                    if ($buyer) {
                        Auth::login($buyer);
                        $objModelUsers = User::getInstance();
                        $userDetails = $objModelUsers->getUserById(Auth::id());
                        Session::put('fs_buyer', $userDetails['original']);
                        return redirect()->intended('buyer/buyerdetails');
                    } else {
                        return view("Buyer/Views/buyer/register")->withErrors([
                            'registerErrMsg' => 'Something went wrong, please try again.',
                        ]);
                    }
                } catch (\Exception $ex) {
                    return redirect()->back()->with('exception', 'An exception occurred, please reload the page and try again.');
                }

            }
        }
        return view("Buyer/Views/buyer/register");
    }

    public function buyerDetails(Request $request)
    {
        if (!Session::has('fs_buyer')) {
            return redirect('/buyer/login');
        }
        $objModelUser = User::getInstance();
        $objLocationModel=Location::getInstance();
        $where['users.id'] = Session::get('fs_buyer')['id'];
        $uesrDetails = $objModelUser->getUserDetailsWhere($where);
        if (isset($uesrDetails->user_id)) {
            return redirect()->intended('buyer/dashboard');
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
                    $buyerDetails = Usersmeta::create([
                            'user_id' => Session::get('fs_buyer')['id'],
                            'addressline1' => $request->input('addressline1'),
                            'addressline2' => $request->input('addressline2'),
                            'city' => $request->input('city'),
                            'state' => $request->input('state'),
                            'country' => $request->input('country'),//NEED COUNTRY DETAILS, FROM DATABASE
                            'zipcode' => $request->input('zipcode'),
                            'phone' => $request->input('phone')
                        ]
                    );

                    if ($buyerDetails) {
                        return redirect('/buyer/logout');;
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
            $where=['rawQuery' => 'is_visible =? and location_type =?', 'bindParams' => [0, 0]];
            $select=array('location_id','name','location_type');
            $countries=$objLocationModel->getAllLocationsWhere($where,$select);


            $whereforstate = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 1]];
            $allstates = $objLocationModel->getAllLocationsWhere($whereforstate);


            $whereforcity = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 2]];
            $allcities = $objLocationModel->getAllLocationsWhere($whereforcity);

            return view("Buyer/Views/buyer/buyerdetails",['countries'=>$countries,'state'=>$allstates,'city'=>$allcities]);
        }
    }

    public function userAjaxHandler(Request $request)
    {
        $inputData = $request->all();
        $objLocationModel=Location::getInstance();
        $ObjNotificationModel=Notification::getInstance();
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

            case "getbuyerNotification":

                $buyerId=Session::get('fs_buyer')['id'];
                $where=['rawQuery'=>'send_to =? AND notification_status=?','bindParams'=>[$buyerId,'U']];
                $Notification= $ObjNotificationModel->getbuyerNotification($where);
//                dd($Notification);
                $data = [];
                if ($Notification == 0) {
                    $data[0] = 0;
                } else {
                    $data[0] = count($Notification);
                }
                $data[1] = $Notification;
                echo json_encode($data);
                break;
                case "changenotificationstatus":
                    $notification = $inputData['NotificationId'];
                    $whereNotify = ['rawQuery' => 'notification_id = ?', 'bindParams' => [$notification]];
                    $changeStatus = ['notification_status' => 'S'];
                    $status = $ObjNotificationModel->updateNoftificationStatus($changeStatus, $whereNotify);
                    if ($status) {
                        echo json_encode(['status' => 'success', 'msg' => 'Notification Status Updated to Seen For User.']);
                    } else {
                        echo json_encode(['status' => 'error', 'msg' => 'Something went wrong, please reload the page and try again.']);
                    }
                    break;
                default:
                    break;
                        }
    }
    public function profile(Request $request)
    {
        $objModelUser = User::getInstance();
        $objModelUsersmeta =Usersmeta::getInstance();
        $objLocationModel = Location::getInstance();

        $where['users.id'] = Session::get('fs_buyer')['id'];
        $uesrDetails = $objModelUser->getUserDetailsWhere($where);
        $whereforCountry = ['rawQuery' => 'is_visible =? and location_type =?', 'bindParams' => [0, 0]];
        $allCountry = $objLocationModel->getAllLocationsWhere($whereforCountry);
        $whereforstate = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 1]];
        $allstates = $objLocationModel->getAllLocationsWhere($whereforstate);
        $whereforcity = ['rawQuery' => 'is_visible =? and location_type =? ', 'bindParams' => [0, 2]];
        $allcities = $objLocationModel->getAllLocationsWhere($whereforcity);
//        dd($allCountry);
        $userId = Session::get('fs_buyer')['id'];

        $where1 = [
            'rawQuery' => 'usersmeta.user_id=?',
            'bindParams' => [$userId]
        ];
        $select = array('usersmeta.*',
            'lt1.location_id as CountryId', 'lt1.name as CountryName',
            'lt2.location_id as StateId', 'lt2.name as StateName',
            'lt3.location_id as CityId', 'lt3.name as CityName');
        $userdata = $objModelUsersmeta->getUsersMDWhere($where1, $select);
        if ($uesrDetails) {
            return view('Buyer/Views/buyer/profile', ['uesrDetails' => $uesrDetails,'usernames'=>$userdata,'country'=> $allCountry,'state'=>$allstates ,'city'=>$allcities]);
        } else {
            return redirect()->intended('buyer/buyerdetails');
        }
    }

    public function logout()
    {
        Session::forget('fs_buyer');
        return redirect('/buyer/login');
    }
    public static function forgotpassword(Request $data)
    {
        if ($data->isMethod('post')) {
            $email1 = $data->input('email');
            $resetcode = mt_rand(1000000, 9999999);
            $user_model = User::getInstance();
            $password = $resetcode;
            $data = ['reset_code' => $password];
            $where = ['rawQuery' => 'email=?','bindParams' => [$email1]];
            $result = $user_model->updateUserWhere($data,$where);
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
            if($response->statusCode()==202)
            {
                echo json_encode(['status' => 200]);
            }
        }


    }
    public function ajaxHandler(Request $request)
    {
        $objModelUser = User::getInstance();
        $objModelUsersmeta = Usersmeta::getInstance();


        $userId = Session::get('fs_buyer')['id'];

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


                    $userdetails = Session::get('fs_buyer');
                    $userdetails['name']=$request->input('first_name');
                    $userdetails['last_name']=$request->input('last_name');
                    Session::put('fs_buyer', $userdetails);


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
                                if (!strpos(Session::get('fs_buyer')['profilepic'], 'placeholder')) {
                                    deleteImageFromStoragePath(Session::get('fs_buyer')['profilepic']);
                                }
                                Session::put('fs_buyer.profilepic', $filePath);
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
                    echo json_encode(array('status' => 'error', 'message' => $passwordValidator->messages()->all(),'error'=>198));
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



        }
    }

    public function submit(Request $request)
    {

        if ($request->isMethod('post')) {
            $user_model = User::getInstance();
            $userDetails = $user_model->getDetails($request->email,$request->password);

            if ($userDetails) {
              echo "Succesfully Updated ";
            } else {
                echo "Invalid Email ";

            }
        }
    }

    public function changepassword(Request $request){
        $user_model = User::getInstance();

        if ($request->isMethod('post')) {
            $email=$request->get('email');
            $resetcode=$request->get('password');
            $userDetails = $user_model->getDetails($email,$resetcode);
            if  ($request->get('newpassword') == $request->get('ConfirmPassword')){
                $password = Hash::make($request->get('newpassword'));
                $data = ['password' => $password];
                $where = ['bindParams' => [$email]];
                $result = $user_model->updateUserInfo($data,$where);
                return 1;
            } else {
                return 0;

            }
        }

    }
}