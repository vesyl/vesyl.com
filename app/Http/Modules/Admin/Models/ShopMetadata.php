<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopMetadata extends Model
{

    private static $_instance = null;

    protected $table = 'shops_metadata';
    protected $fillable = ['shop_id', 'shop_type', 'address_line_1', 'address_line_2', 'city', 'state', 'country', 'zipcode', 'added_date'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ShopMetadata();
        return self::$_instance;
    }

    /**
     * Add shopMetadata details
     * @param $data
     * @return int
     * @since 1-4-2016
     * @author Harshal Wagh
     */
    public function addShopMetadata($data)
    {
        $result = DB::table($this->table)
            ->insertGetId($data);
        return $result;
    }

    /**
     * Get all shop Metadata details
     * @param $where
     * @param array $selectedColumns
     * @return mixed
     * @since 1-4-2016
     * @author Harshal Wagh
     */
    public function getAllshopsMetadataWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->get();
        return $result;
    }

    /**
     * @param array : $where
     * @return int
     * @throws "Argument Not Passed"
     * @since 4-4-2016
     * @author Harshal Wagh
     * @uses
     */
    public function updateShopMetadataWhere()
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
    public function deleteShopDetails($where){
        {
            try {
                $sql = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
                return $sql;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

}
