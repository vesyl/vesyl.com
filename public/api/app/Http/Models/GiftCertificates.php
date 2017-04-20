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
class GiftCertificates extends Model
{

    private static $_instance = null;

    protected $table = 'gift_certificates';
    protected $fillable = ['gift_id', 'gift_by', 'gift_for', 'gift_amount', 'gift_balance', 'gift_name', 'gift_message', 'added_date', 'gift_code', 'gift_code', 'redeem_status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new GiftCertificates();
        return self::$_instance;
    }

    public function insertToGiftCertificate()
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


    public function checkForGiftCode($where,$selectedColumns = ['*']){

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

    public function updateRedeemStatus()
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
}