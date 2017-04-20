<?php

namespace FlashSale\Http\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Class Transactions
 * @package FlashSale\Http\Modules\Payment\Models
 * @since 02-03-2017
 * @author Akash M. Pai <akashpai@globussoft.in>
 */
class Transactions extends Model
{

    private static $_instance = null;

    protected $table = 'transactions';
    protected $fillable = ['tx_pmethod_id', 'tx_type', 'tx_unique_code', 'tx_code', 'payment_mode', 'payment_details', 'user_details', 'shipping_addr', 'billing_addr', 'discount_by', 'discount_type', 'discount_value', 'tx_amount', 'walletbal_used', 'rewardpts_used', 'tx_date', 'tx_status', 'updated_at', 'created_at'];

    /**
     * Get instance/object of this class
     * @return Transactions|null
     * @since 29-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Transactions();
        return self::$_instance;
    }

    /**
     * Add new transaction data
     * @return string|int
     * @throws Exception
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function addNewTransaction($data)
    {
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)->insertGetId($data);
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
    public function getTransactionWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'Transaction details.';
            } else {
                $returnData['code'] = 100;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
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
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
            $returnData['message'] = 'No transaction.';
            $returnData['code'] = 100;
            if (!empty($result)) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All transactions.';
                $returnData['data'] = $result;
            }
        }
        return json_encode($returnData);
    }

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function updateTransactionWhere()
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $updatedResult = $this->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                if ($updatedResult) {
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Changes saved.';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'Nothing to update.';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function deleteTransactionWhere()
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
                if ($result) {
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Transaction/s deleted.';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'Could not delete. Try again later.';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }


}
