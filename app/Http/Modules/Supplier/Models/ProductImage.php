<?php

namespace FlashSale\Http\Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProductImage extends Model
{

    private static $_instance = null;

    protected $table = 'product_images';

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ProductImage();
        return self::$_instance;
    }

    public function addNewImage()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insert($data);
                return $result;
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
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function getAllImagesWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
            $returnData['code'] = 200;
            $returnData['message'] = 'All images.';
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }


}
