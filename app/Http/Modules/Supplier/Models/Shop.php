<?php

namespace FlashSale\Http\Modules\Supplier\Models;

use FlashSale\Http\Modules\Admin\Models\Shops;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shop extends Model
{

    private static $_instance = null;

    protected $table = 'shops';
    protected $fillable = ['shop_id', 'user_id', 'shop_name', 'shop_banner', 'parent_category_id', 'status_set_by', 'shop_status'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Shop();
        return self::$_instance;
    }

    /**
     * Get all shop details
     * @param $where
     * @param array $selectedColumns
     * @return mixed
     * @since 28-1-2016
     * @author Harshal Wagh
     */
    public function getAllshopsWhereOld($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->get();
        return $result;
    }

    /**
     * Add shop details
     * @param $data
     * @return int
     * @since 28-1-2016
     * @author Harshal Wagh
     */
    public function addShop($data)
    {
        $result = DB::table($this->table)
            ->insertGetId($data);
        return $result;

    }

    /**
     * Get shop details for Datatable
     * @param $user_id
     * @return array
     * @since 29-1-2016
     * @author Harshal Wagh
     */
    public function getAvailableShopDetails($where)
    {

        try {
            $result = Shops::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('shops_metadata', 'shops.shop_id', '=', 'shops_metadata.shop_id')
                ->select('shops.shop_id', 'shop_name', 'shops_metadata.shop_metadata_status as shop_status', 'user_id', 'shops_metadata.shop_metadata_id')
                ->get();
            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }

    /**
     * @param array : $where
     * @return int
     * @throws "Argument Not Passed"
     * @since 29-1-2016
     * @author Harshal Wagh
     * @uses
     */
    public function updateShopWhere()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                return 1;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }


    /**
     * @param $where
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai
     */
    public function getShopWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'Shop details.';
            } else {
                $returnData['code'] = 400;
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
     * @author Akash M. Pai
     */
    public function getAllShopsWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'shops.user_id');
                    $join->whereIn('users.role', [3, 4, 5]);
                })
                ->select($selectedColumns)
                ->get();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All shops.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No shops available.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    public function insertUserId($data)
    {
        try {
            $result = DB::table($this->table)->insertGetId($data);
            return json_encode(array('code' => 200, 'message' => 'shop added successfully.', 'data' => $result));
        } catch (\Exception $e) {
            return json_encode(array('code' => 400, 'message' => 'Could not add shop. Please try again later', 'data' => $e));
        }


    }

    public function getShopName($data)
    {

        $result = DB::table($this->table)
            ->select("shop_name")
            ->where('user_id', $data)
            ->first();

        if (count($result) > 0) {
            return json_encode(array('code' => 200, 'message' => 'shop name fetched successfully.', 'data' => $result));
        } else {
            return json_encode(array('code' => 400, 'message' => 'no shops found'));
        }

    }

    public function checkShop($data)
    {
        $result = DB::table($this->table)
            ->select("shop_name")
            ->where('shop_name', $data)
            ->first();

        if (count($result) > 0) {
            return json_encode(array('code' => 200, 'message' => 'shop fetched', 'data' => $result));
        } else {
            return json_encode(array('code' => 400, 'message' => 'no shops found'));
        }

    }

    public function getShopDataWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All feature variants relations.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    public function addShopData($data)
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = $this->firstOrCreate($data);
                return json_encode(array('code' => 200, 'message' => 'shops data added successfully.', 'data' => $result));
//                return $result;
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
//                return $e->getMessage();
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
//            throw new Exception('Argument Not Passed');
        }
    }

}
