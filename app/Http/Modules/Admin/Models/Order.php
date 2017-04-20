<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Order model
 * Class Order
 * @package FlashSale\Http\Modules\Admin\Models
 * @author Akash M. Pai <akashpai@globussoft.in>
 */
class Order extends Model
{

    private static $_instance = null;

    protected $table = 'orders';
    //protected $fillable = ['id_path', 'level'];

    /**
     * Get instance/object of this class
     * @return ProductCategory|null
     * @since 19-12-2015
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Order();
        return self::$_instance;
    }

    /**
     * @param string $where one or more where conditions
     * @return array of all results matching the where condition or null
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function getAllOrdersWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All features.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    /**
     * @param string $where one or more where conditions
     * @return array of result matching the where condition or null
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function getOrderWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('transactions', 'orders.tx_id', '=', 'transactions.transaction_id')
                ->join('users', 'users.id', '=', 'orders.for_user_id')
                ->select($selectedColumns)
                ->first();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = "Order # " . $result->order_id;
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No such order found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function updateOrderWhere()
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $updatedResult = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                if ($updatedResult) {
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Changes saved successfully.';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'Nothing to update';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }


    public function getAllOrdersWhereWithLimit($where, $implodedWhere, $sortingOrder, $iDisplayLength, $iDisplayStart, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $selectedResult = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->whereRaw($implodedWhere['rawQuery'], isset($implodedWhere['bindParams']) ? $implodedWhere['bindParams'] : array())
//                    ->join('transactions', 'orders.tx_id', '=', 'transactions.transaction_id')
//                    ->join('users', 'users.user_id', '=', 'orders.for_user_id')
                    ->select($selectedColumns)
                    ->orderBy($sortingOrder)
                    ->skip($iDisplayStart)
                    ->take($iDisplayLength)
                    ->groupBy('transactions.transaction_id')
                    ->get();
                if ($selectedResult) {
                    $returnData['data'] = $selectedResult;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Filtered and sorted orders.';
                } else {
                    $returnData['code'] = 400;
                    $returnData['message'] = 'No orders with these filters';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }
}
