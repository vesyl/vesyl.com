<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Transactions model
 * Class Order
 * @package FlashSale\Http\Modules\Admin\Models
 * @author Akash M. Pai <akashpai@globussoft.in>
 */
class Transactions extends Model
{

    private static $_instance = null;

    protected $table = 'transactions';
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
            self::$_instance = new Transactions();
        return self::$_instance;
    }

    /**
     * @return json string
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function addTransaction()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data, 'transaction_id');
                return json_encode(array('code' => 200, 'message' => 'Transaction added successfully.', 'data' => $result));
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
        }
    }

    /**
     * @param $where
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllTransactionsWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->select($selectedColumns)
                    ->get();
                if ($result) {
                    $returnData['data'] = $result;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Transactions';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'No such transactions found';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }

    /**
     * @param $where
     * @param $offset
     * @param $limit
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllTransactionsWhereWithLimit($where, $offset, $limit, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $offset = func_get_arg(1);
            $limit = func_get_arg(2);
            $selectedColumns = func_get_arg(3);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->select($selectedColumns)
                    ->skip($offset)
                    ->take($limit)
                    ->get();
                if ($result) {
                    $returnData['data'] = $result;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Transactions';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'No such transactions found';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }

}
