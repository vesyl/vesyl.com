<?php
namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Notification model
 * Class Notification
 * @package FlashSaleApi\Http\\Models
 */
class Notification extends Model
{

    private static $_instance = null;

    protected $table = 'notification';
    protected $fillable = ['notification_id', 'send_to', 'send_by', 'type_to', 'type_by', 'message', 'description', 'send_date', 'notification_status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Notification();
        return self::$_instance;
    }


    public function AddNotification()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getNotificationDetail($selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->select($selectedColumns)
                ->leftJoin('users as user', 'user.id', '=', 'notification.send_to')
                ->leftJoin('users as merchant', function ($join) {
                    $join->on('merchant.id', '=', 'notification.send_by')
                        ->where('notification.send_by', '!=', '0');
                })
                ->orderBy('notification.notification_id')
                ->get();

        } catch (QueryException $e) {
            echo $e;
        }
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function updateNoftificationStatus($dataToUpdate, $where)
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function deletenotificationDetail($where)
    {
        $sql = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->delete();
        if ($sql) {
            return $sql;
        } else {
            return 0;
        }
    }

    public function getuserNotificationDetail($where,$selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not passed', 'data' => null);
        if(func_num_args()>0){
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->count('notification_id');
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All Notifications  Settings.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found';
            }
            $returnData['data'] = $result;
            return $result;
        }
        return json_encode($returnData);
    }


}