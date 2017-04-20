<?php

namespace FlashSale\Http\Modules\Supplier\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use \Exception;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    private static $_instance = null;

    protected $table = 'users';
    protected $fillable = ['name', 'last_name', 'email', 'password', 'role', 'username', 'profilepic','for_shop_name'];
    protected $hidden = ['password', 'remember_token'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new User();
        return self::$_instance;
    }

    public function getUserWhere($email, $password)
    {
        $result = User::where('email', $email)
            ->where('password', bcrypt($password))
            ->first();
//        $result = User::all();
        return $result;
    }

    public function getUserDetailsWhere()
    {

        if (func_num_args() > 0) {
            $where = func_get_arg(0);

            $result = DB::table('users')
                ->join('usersmeta', 'usersmeta.user_id', '=', 'users.id')
                ->where($where)
                ->first();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * Update user data
     * @param array $data
     * @param array $where
     * @return mixed|int
     * @throws Exception
     * @since 09-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function updateUserWhere()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);

            try {
                $updatedResult = DB::table($this->table)
                    ->where($where)
                    ->update($data);
                return $updatedResult;

            } catch (\Exception $e) {
                return $e->getMessage();
            }

        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getUserById($userId)
    {

        $result = User::whereId($userId)->first();
        return $result;
    }

    /**
     * @param $email
     * @param $code
     * @return mixed
     * @author Pradeep N G
     */
    public function getDetails($email, $code)
    {
        $result = User::where('email', $email)
            ->where('reset_code', $code)
            ->first();
        return $result;
    }

    /**
     * @return int|string
     * @throws Exception
     * @author Pradeep N G
     */
    public function updateUserInfo()
    {
        $returnData=array('code'=>400,'message'=>'Argument Not passed','data'=>null);
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);

                $result = User::where('email', $where)
                    ->update($data);
                if ($result) {
                    $returnData['code'] = 200;
                    $returnData['message'] = 'All reward settings';
                } else {
                    $returnData['code'] = 400;
                    $returnData['message'] = 'No data found';
                }
                $returnData['data'] = $result;
            }
        return json_encode($returnData);
        //          } catch (\Exception $e) {
//                return $e->getMessage();
//            }
//            if ($result) {
//                return $result;
//            } else {
//                return 0;
//            }
//        } else {
//            throw new Exception('Argument Not Passed');
//        }
    }

    /**
     * @return string
     * @throws Exception
     * @author Pradeep N G
     */
    public function updateUserWhere1()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $result = User::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                return $result;

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getAdmindata()
    {
        $result = User::where('role', 5)
            ->first();
        return $result;
    }

//    public function getUserDetailsWhere1()
//    {
//
//        if (func_num_args() > 0) {
//            $where = func_get_arg(0);
//
//            $result = DB::table($this->table)
//                ->join('usersmeta', 'users.id', '=', 'usersmeta.user_id')
//                ->where($where)
//                ->first();
//            return $result;
//        } else {
//            throw new Exception('Argument Not Passed');
//        }
//    }
}
