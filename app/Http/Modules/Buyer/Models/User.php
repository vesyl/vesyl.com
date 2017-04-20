<?php
namespace Flashsale\Http\Modules\Buyer\Models;


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


    public  function getUserById($userId)
    {
        $result= User::whereId($userId)->first();
        return $result;
    }
    public function getDetails($email, $code)
    {

        $result = User::where('email', $email)
            ->where('reset_code', $code)
            ->first();
        return $result;


    }
    public function getUserDetailsWhere()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->join('usersmeta', 'users.id', '=', 'usersmeta.user_id')
                ->where($where)
                ->first();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }
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
    public function updateUserInfo()
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
}