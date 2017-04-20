<?php

namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Shops model
 * Class Shops
 * @package FlashSaleApi\Http\Modules\Admin\Models
 */
class Shops extends Model
{

    private static $_instance = null;

    protected $table = 'shops';


    /**
     * Get instance/object of this class
     * @return Shops|null
     * @author: Vini Dubey<vinidubey@globussoft.in>
     * @since: 13-07-2016
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Shops();
        return self::$_instance;
    }


    public function getAllActiveShop($where = ['rawQuery' => 1], $selectedColum = ['*'], $offset = 0, $limit = 999999)
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColum)
                ->skip($offset)
                ->take($limit)
                ->get();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllShopsByShopId($where = ['rawQuery' => 1], $selectedColumn = ['*'], $offset = 0, $limit = 999999)
    {

        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('products','products.for_shop_id','=','shops.shop_id')
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->select($selectedColumn)
                ->skip($offset)
                ->take($limit)
//                ->toSql();
                ->get();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }
}