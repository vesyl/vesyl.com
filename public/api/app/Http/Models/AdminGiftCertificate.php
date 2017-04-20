<?php
namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * AdminGiftCertificate model
 * Class AdminGiftCertificate
 * @package  FlashSaleApi\Http\Models
 */
class AdminGiftCertificate extends Model
{

    private static $_instance = null;

    protected $table = 'admin_gift_certificate';
    protected $fillable = ['gift_id', 'gift_amount', 'gift_name', 'gift_code', 'gc_img_src', 'gc_added_date', 'status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new AdminGiftCertificate();
        return self::$_instance;
    }

    public function addGiftCertificate()
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


    public function getAllAdminGiftCertificate($selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->select()
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

    public function updateGiftCertificate()
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

    public function getAllAdminGiftCertificateById($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteGiftWhere($where)
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getAdminGiftCertificateById($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}