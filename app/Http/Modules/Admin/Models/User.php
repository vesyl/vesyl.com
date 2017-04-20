<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;
use \Exception;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    private static $_instance = null;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'username', 'last_name', 'role', 'status', 'campaign_mode'];
    protected $hidden = ['password', 'remember_token'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new User();
        return self::$_instance;
    }

    /**
     * @return int|string
     * @throws Exception
     * @author Pradeep N G
     */
    public function updateUserInfo2()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {

                $result = User::where('email', $where)
                    ->update($data);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * @return mixed
     * @author Pradeep N G
     */
    public function resettcode()
    {
        if (func_num_args() > 0) {
            $rescode = func_get_arg(0);
            $data['reset_code'] = $rescode;

            $result = $this->insert($data);

            return $result;

        }

    }

    /**
     * @param $id
     * @return mixed
     * @author Pradeep N G
     */
    public function chck_passord($id)
    {
        $result = User::where('id', $id)
            ->first();
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

    public function getUserWhere($email, $password)
    {
        $result = User::where('email', $email)
            ->where('password', bcrypt($password))
            ->first();
//        $result = User::all();
        return $result;
    }

    /**
     * @param $columnName
     * @param $condition
     * @param $coulumnValue
     * @return mixed
     */
    /*
     * TEST FUNCTION
     */
    public function getUserByColumnConditionAndValue($columnName, $condition, $coulumnValue)
    {
        $result = User::where($columnName, $condition, $coulumnValue)
            ->first();
        return $result;
    }

    /**
     * @param $userId
     * @return mixed
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function getUserById($userId)
    {
        $result = User::whereId($userId)->first();
        return $result;
    }

    public function temp()
    {

    }


    public function getAvailableUserDetails()
    {

        try {
            $result = User::where('role', 1)
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }


    public function getAvailableSupplierDetails($where, $selectedColumns = ['*'])
    {
        try {
            $result = User::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->join('location','location.location_type', '=', 'usersmeta.state')
//                ->join('usersmeta', function ($join) {
//                    $join->on('usersmeta.user_id', '=', 'users.id');
//                })
//                ->join('location', function ($join) {
//                    $join->on('location.location_id', '=', 'usersmeta.country');
//                })
//                ->whereRaw($status['rawQuery'], isset($status['bindParams']) ? $status['bindParams'] : array())
                ->select(['users.id', 'users.username', 'users.email', 'users.created_at', 'users.updated_at', 'users.status'])
//                ->select(['usersmeta.*','location.*'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }

    }

    /**
     * @param array : $where
     * @return int
     * @throws "Argument Not Passed"
     * @throws
     * @author Vini
     * @uses Authentication
     */
    public function updateUserWhere()
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


    /**
     * @param array : $where
     * @return int
     * @throws "Argument Not Passed"
     * @throws
     * @author Vini
     * @uses Delete User
     */
    public function deleteUserDetails($where)
    {
        $sql = User::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->delete();//Todo notify to vini for modification
        if ($sql) {
            return $sql;
        } else {
            return 0;
        }
    }

    public function updateUserInfo()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {

                $result = User::where('id', $where)
                    ->update($data);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getPendingUserDetails($where)//, $status)
    {

        try {
            $result = User::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->whereRaw($status['rawQuery'], isset($status['bindParams']) ? $status['bindParams'] : array())
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }

    }

    public function getDeletedUserDetails($where, $status)
    {

        try {
            $result = User::where('role', 1)
                ->whereIn('status',[4])
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }

    }

    public function getUserInfo($where)
    {

        try {
            $result = User::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select()
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

    public function getUserDetails($where, $selectedColumns = ['*'])
    {

        try {
            $result = User::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

    public function getAvailableManagerDetails($where)
    {

        try {
            $result = User::where('role', 4)
                ->whereIn('status',[1,2])
                ->join('permission_user_relation', 'users.id', '=', 'permission_user_relation.user_id')
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }


    }

    public function getUserInfoById($where, $selectedColumns = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('permission_user_relation', 'permission_user_relation.user_id', '=', 'users.id')
                ->select($selectedColumns)
                ->first();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();

        }

    }

    /**
     * @return mixed
     * @author Pradeep N G
     */
    public function deleteUser()
    {
        if (func_num_args() > 0) {
            $id = func_get_arg(0);
//        print_r($id);
//        die('model');
            $result = DB::table('reviews')
                ->where('id', $id)
                ->delete();
            return $result;
        }
    }
    public function getSupplierDetails()
    {

        try {
            $result = User::where('role', 3)
                ->whereIn('status',[1,2])
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function getAvailableCustomerDetails()
    {

        try {
            $result = User::where('role', 1)
                ->whereIn('status',[1,2])
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function getDeletedSupplierDetails($where, $status)
    {

        try {
            $result = User::where('role', 3)
                ->whereIn('status',[4])
                ->select(['id', 'username', 'email', 'created_at', 'updated_at', 'status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }

    }
}
