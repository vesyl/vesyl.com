<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Campaigns model
 * Class Campaigns
 * @package FlashSale\Http\Modules\Admin\Models
 */
class Campaigns extends Model
{

    private static $_instance = null;

    protected $table = 'campaigns';
    protected $fillable = ['campaign_id', 'campaign_name', 'by_user_id', 'for_shop_id', 'campaign_type', 'campaign_banner', 'discount_type', 'discount_value', 'available_from', 'available_upto','extended_end_time','for_category_ids', 'for_product_ids', 'campaign_status', 'status_set_by', 'extended_end_type'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Campaigns();
        return self::$_instance;
    }

    public function getAllFlashsaleDetails($where, $selectedColumns = ['*'])
    {

        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', 'users.id', '=', 'campaigns.by_user_id')
                ->select($selectedColumns)
//                ->toSql();
                ->get();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function updateFlashsaleStatus($dataToUpdate, $where)
    {

        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($dataToUpdate);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getAllFlashDetail($wheres,$where = null, $order = null, $count = null, $offset = null, $selectedColumn = ['*'])
    {

        if (func_get_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($wheres['rawQuery'], isset($wheres['bindParams']) ? $wheres['bindParams'] : array())
                ->whereRaw($where)
                ->join('users', 'users.id', '=', 'campaigns.by_user_id')
                ->select($selectedColumn)
                ->orderBy($order)
                ->skip($offset)
                ->take($count)
//                ->limit($count, $offset)
//                ->toSql();
                ->get();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }


    public function addFlashsale()
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


}