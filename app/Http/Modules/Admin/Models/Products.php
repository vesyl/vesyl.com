<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Products
 * @package FlashSale\Http\Modules\Admin\Models
 * @author Dinanath Thakur <dinanaththakur@globussoft.in>
 */
class Products extends Model
{

    private static $_instance = null;

    protected $table = 'products';

    /**
     * Get instance/object of this class
     * @return Products|null
     * @since 17-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Products();
        return self::$_instance;
    }

    /**
     * Add new product
     * @param $data
     * @return string
     * @throws Exception
     * @since 17-02-2016, modified 06-01-2017 AMP,
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function addNewProduct($data)
    {
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)->insertGetId($data);
                return json_encode(array('code' => 200, 'message' => 'Product added successfully.', 'data' => $result));
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
        }
    }

    public function getAllProducts($where, $selectedColumns = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', 'users.id', '=', 'products.added_by')
                ->leftJoin('shops', 'shops.shop_id', '=', 'products.for_shop_id')
                ->join('product_categories', 'product_categories.category_id', '=', 'products.category_id')
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->select($selectedColumns)
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }

    }

    public function updateProductWhere()
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
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getAllFilterProducts($wheres, $where = null, $order = null, $count = null, $offset = null, $selectedColumn = ['*'])
    {

        if (func_get_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($wheres['rawQuery'], isset($wheres['bindParams']) ? $wheres['bindParams'] : array())
                ->whereRaw($where)
                ->join('users', 'users.id', '=', 'products.added_by')
                ->leftJoin('shops', 'shops.shop_id', '=', 'products.for_shop_id')
                ->join('product_categories', 'product_categories.category_id', '=', 'products.category_id')
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
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

    public function getProductNameById($where, $selectedColumn)
    {

        try {
            $result = DB::table($this->table)
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->toSql();
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }

    }


    public function getAllSupplierProducts($where, $selectedColumn)
    {

        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumn)
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

    /**
     * @param $where
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getProductWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->select($selectedColumns)
                ->first();
            $returnData['code'] = 200;
            $returnData['message'] = 'Product data.';
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    public function updateProductsWhere()
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
                    $returnData['message'] = 'Changes saved successfully.';
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

    public function getAllProductsWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->select($selectedColumns)
                ->get();
            $returnData['code'] = 200;
            $returnData['message'] = 'Product data.';
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    /**
     * @param $wheres
     * @param null $where
     * @param null $order
     * @param null $count
     * @param null $offset
     * @param array $whereForJoin
     * @param array $selectedColumn
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllProductsWithLimitWhere($wheres, $where = null, $order = null, $count = null, $offset = null, $whereForJoin = ['column' => 1, 'condition' => 1, 'value' => 1], $selectedColumn = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_get_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($wheres['rawQuery'], isset($wheres['bindParams']) ? $wheres['bindParams'] : array())
//                ->whereRaw($where)
                ->join('users', 'users.id', '=', 'products.added_by')
                ->leftJoin('shops', 'shops.shop_id', '=', 'products.for_shop_id')
                ->leftJoin('product_images', function ($join) use ($whereForJoin) {
                    $join->on('product_images.for_product_id', '=', 'products.product_id');
                    $join->where($whereForJoin['column'], $whereForJoin['condition'], $whereForJoin['value']);
                })
                ->select($selectedColumn)
                ->orderBy($order)
                ->skip($offset)
                ->take($count)
                ->get();
            $returnData['code'] = 100;
            $returnData['data'] = $result;
            $returnData['message'] = 'No such products found';
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'Product data.';
            }
        }
        return json_encode($returnData);
    }

    public function getAllProductsCountWhere($wheres, $where = null, $order = null, $whereForJoin = ['column' => '1', 'condition' => '1', 'value' => '1'], $selectedColumn = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_get_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($wheres['rawQuery'], isset($wheres['bindParams']) ? $wheres['bindParams'] : array())
//                ->whereRaw($where)
                ->join('users', 'users.id', '=', 'products.added_by')
                ->leftJoin('shops', 'shops.shop_id', '=', 'products.for_shop_id')
                ->leftJoin('product_images', function ($join) use ($whereForJoin) {
                    $join->on('product_images.for_product_id', '=', 'products.product_id');
                    $join->where($whereForJoin['column'], $whereForJoin['condition'], $whereForJoin['value']);
                })
                ->select($selectedColumn)
                ->count();
            $returnData['code'] = 100;
            $returnData['data'] = $result;
            $returnData['message'] = 'No such products found';
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'Product data.';
            }
        }
        return json_encode($returnData);
    }


    public function getTotalOrders()
    {
        $data = DB::table('orders')
            ->select('order_id')
            ->count('order_id');

        return json_encode($data);

    }

    public function getOrderProducts($where)
    {

        $result = DB::table('orders')
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->join('products', 'products.product_id', '=', 'orders.product_id')
            ->select(['products.product_name', 'orders.quantity', 'products.list_price',
                'orders.*', DB::raw('count(`orders`.`product_id`) as orders_sum'),
                DB::raw('SUBSTRING_INDEX(orders.product_id,\'-\',1) as product_id_new')])
            ->groupBy('products.product_name')
            ->ORDERBY('orders.updated_at', 'desc')->take(10)
            ->get();
        return $result;

    }


    public function getProductDetails()
    {


        try {
            $result = DB::table('products')
                ->select(['products.added_date', DB::raw(' SUM(case when month(updated_at) = 01 then 1 else 0 end) as jan,
                                      SUM(case when month(updated_at) = 02 then 1 else 0 end) as feb,
                                       SUM(case when month(updated_at) = 03 then 1 else 0 end) as mar,
                                       SUM(case when month(updated_at) = 04 then 1 else 0 end) as apr,
                                       SUM(case when month(updated_at) = 05 then 1 else 0 end) as may,
                                       SUM(case when month(updated_at) = 06 then 1 else 0 end) as jun,
                                       SUM(case when month(updated_at) = 07 then 1 else 0 end) as jul,
                                       SUM(case when month(updated_at) = 08 then 1 else 0 end) as aug,
                                       SUM(case when month(updated_at) = 09 then 1 else 0 end) as sep,
                                       SUM(case when month(updated_at) =10 then 1 else 0 end) as oct,
                                       SUM(case when month(updated_at) = 11 then 1 else 0 end) as nov,
                                    SUM(case when month(updated_at) = 12 then 1 else 0 end) as december ')])
                ->ORDERBY('products.added_date')
                ->get();
            return $result;
//dd($result);
        } catch (QueryException $e) {
            echo $e;

        }

    }

    public function getOrdersDetails()
    {

        try {
            $data = DB::table('orders')
                ->select(['orders.added_date', DB::raw(' SUM(case when month(updated_at) = 01 then 1 else 0 end) as jan,
                                      SUM(case when month(updated_at) = 02 then 1 else 0 end) as feb,
                                       SUM(case when month(updated_at) = 03 then 1 else 0 end) as mar,
                                       SUM(case when month(updated_at) = 04 then 1 else 0 end) as apr,
                                       SUM(case when month(updated_at) = 05 then 1 else 0 end) as may,
                                       SUM(case when month(updated_at) = 06 then 1 else 0 end) as jun,
                                       SUM(case when month(updated_at) = 07 then 1 else 0 end) as jul,
                                       SUM(case when month(updated_at) = 08 then 1 else 0 end) as aug,
                                       SUM(case when month(updated_at) = 09 then 1 else 0 end) as sep,
                                       SUM(case when month(updated_at) =10 then 1 else 0 end) as oct,
                                       SUM(case when month(updated_at) = 11 then 1 else 0 end) as nov,
                                    SUM(case when month(updated_at) = 12 then 1 else 0 end) as december ')])
                ->ORDERBY('orders.added_date')
                ->get();
            return $data;
//            dd($data);
        } catch (QueryException $e) {
            echo $e;

        }
    }

    public function getRevenueDetails($where1)
    {


        $data = DB::table('orders')
            ->whereRaw($where1['rawQuery'], isset($where1['bindParams']) ? $where1['bindParams'] : array())
            ->select('order_status')
            ->count('order_status');

        return json_encode($data);


    }

    public function getRevenueOrdersDetails($where2)
    {


        $data = DB::table('orders')
            ->whereRaw($where2['rawQuery'], isset($where2['bindParams']) ? $where2['bindParams'] : array())
            ->select('order_status')
            ->count('order_status');
        return json_encode($data);


    }
}
