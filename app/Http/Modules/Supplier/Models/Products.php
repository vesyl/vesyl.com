<?php


namespace FlashSale\Http\Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Product-category model
 * Class ProductCategory
 * @package FlashSale\Http\Modules\Supplier\Models
 */
class Products extends Model
{

    private static $_instance = null;

    protected $table = 'products';

    /**
     * Get instance/object of this class
     * @return Products|null
     * @since 10-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.com>
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Products();
        return self::$_instance;
    }

    public function getProductNameById($where, $selectedColumn)
    {
        {
            try {
                $result = DB::table($this->table)
                    ->select($selectedColumn)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->toSql();
                    ->get();
            } catch
            (QueryException $e) {
                echo $e;
            }
            if ($result) {
                return $result;
            } else {
                return 0;
            }

        }
    }

    /**
     * @param $where
     * @param array $selectedColumns
     * @return mixed
     * @author Pradeep N G
     */
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

    /**
     * @param $wheres
     * @param null $where
     * @param null $order
     * @param null $count
     * @param null $offset
     * @param array $selectedColumn
     * @return mixed
     * @throws Exception
     * @author Pradeep N G
     */
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

    /**
     * @return int|string
     * @throws Exception
     * @author Pradeep N G
     */
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

    /**
     * @return string
     * @author Pradeep N G
     */
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


    /**
     * @return string
     * @throws Exception
     * @author Pradeep N G
     */
    public function addNewProduct()
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


    public function getProductNameByIdWhere($where)
    {
        {
            try {
                $result = DB::table($this->table)
                    ->select()
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->get();
            } catch
            (QueryException $e) {
                echo $e;
            }
            if ($result) {
                return $result;
            } else {
                return 0;
            }

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

}