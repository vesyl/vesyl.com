<?php

namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Currency model
 * Class Currency
 * @package FlashSaleApi\Http\Modules\Admin\Models
 * @author Dinanath Thakur <dinanaththakur@globussoft.in>
 */
class Orders extends Model
{

    private static $_instance = null;

    protected $table = 'orders';


    /**
     * Get instance/object of this class
     * @return Orders|null
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 13-07-2016
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Orders();
        return self::$_instance;
    }


    public function insertToOrder()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insert($data);
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }


    public function getcartOrder($where)
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select()
                ->get();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateToOrder()
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

    public function getCartProductDetails($where, $selectColumn)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->select($selectColumn)
                    ->join('products as products', function ($join) {
                        $join->on(DB::raw("SUBSTRING_INDEX(orders.product_id,'-',1)"), '=', 'products.product_id');
                    })
                    ->join('product_option_variants_combination as product_option_variants_combination', function ($join) {
                        $join->on('orders.product_id', '=', 'product_option_variants_combination.product_id');
                    })
                    ->join('product_images as product_images', function ($join) {
                        $join->on('product_option_variants_combination.combination_id', '=', 'product_images.for_combination_id');
                        $join->orWhere('product_images.for_product_id', '=', 'products.product_id');
                    })
                    ->leftJoin('product_option_variant_relation as product_option_variant_relation', function ($join) {
                        $join->on('product_option_variant_relation.product_id', '=', 'products.product_id');
                    })
                    ->leftjoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                    ->join('users', 'users.id', '=', 'products.added_by')
                    ->join('usersmeta', 'usersmeta.id', '=', 'users.id')
                    ->leftjoin('location as location', function ($join) {
                        $join->on('location.location_id', '=', 'usersmeta.country');
                    })
                    ->leftjoin('location as ls', function ($join) {
                        $join->on('ls.location_id', '=', 'usersmeta.state')
                            ->where('usersmeta.state', '!=', 0);
                    })
                    ->leftjoin('location as lc', function ($join) {
                        $join->on('lc.location_id', '=', 'usersmeta.city')
                            ->where('usersmeta.city', '!=', 0);
                    })
                    ->groupBy('product_images.for_combination_id')
                    ->groupBy('product_images.for_product_id')
//                ->toSql();
                    ->get();
                if ($result) {
                    $returnData['data'] = $result;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'User cart details.';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'No products in cart';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = $e->getMessage();//'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }


    /**
     * @param $where
     * @return int
     * Delete cart order
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 09-08-2016
     */
    public function deleteCartOrder($where)
    {
        $sql = DB::table('orders')
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->delete();
        if ($sql) {
            return $sql;
        } else {
            return 0;
        }
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
                $returnData['message'] = $e->getMessage();//'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }


    /**
     * @param $where
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getOrderWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->select($selectedColumns)
                    ->join('transactions', function ($join) {
                        $join->on('orders.tx_id', '=', 'transactions.transaction_id');
                    })
                    ->join('payment_methods', function ($join) {
                        $join->on('transactions.tx_pmethod_id', '=', 'payment_methods.payment_method_id');
                    })
//                    ->join('users', function ($join) {
//                        $join->on('orders.for_user_id', '=', 'users.id');
//                    })
                    ->first();
                if ($result) {
                    $returnData['data'] = $result;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Order details';
                } else {
                    $returnData['code'] = 400;
                    $returnData['message'] = 'No such order found';
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
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllOrdersWhereWithLimit($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->select($selectedColumns)
//                    ->groupBy('tx_id')
//                    ->join('transactions', function ($join) {
//                        $join->on('tx_id', '=', 'transaction_id');
//                    })
                    ->get();//TODO
                dd($result);
                if ($result) {
                    $returnData['data'] = $result;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Order details';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'No such order found';
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
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllOrdersWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->select($selectedColumns)
//                    ->groupBy('tx_id')
//                    ->join('transactions', function ($join) {
//                        $join->on('tx_id', '=', 'transaction_id');
//                    })
                    ->get();
                if ($result) {
                    $returnData['data'] = $result;
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Order details';
                } else {
                    $returnData['code'] = 100;
                    $returnData['message'] = 'No such orders found';
                }
            } catch (\Exception $e) {
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }

    public function insertOrder($data)
    {
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)->insertGetId($data, 'order_id');
                return json_encode(array('code' => 200, 'message' => 'Order placed successfully.', 'data' => $result));
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not place order. Please try again later', 'data' => $e->getMessage()));
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
        }

    }
}

?>