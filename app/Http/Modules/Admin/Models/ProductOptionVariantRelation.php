<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Class ProductOptionVariantRelation
 * @package FlashSale\Http\Modules\Admin\Models
 * @since 29-02-2016
 * @author Dinanath Thakur <dinanaththakur@globussoft.in>
 */
class ProductOptionVariantRelation extends Model
{

    private static $_instance = null;

    protected $table = 'product_option_variant_relation';


    /**
     * Get instance/object of this class
     * @return ProductOptionVariantRelation|null
     * @since 29-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ProductOptionVariantRelation();
        return self::$_instance;
    }

    /**
     * Add new option-variant relation data
     * @return string|int
     * @throws Exception
     * @since 29-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public function addNewOptionVariantRelation()
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

    public function getAllOptVarRelsWhere($where, $selectedColumns = ['*'])
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
                $returnData['message'] = 'All option variant relations.';
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
    public function getOptVarRelsWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('product_options', function ($join) {
                    $join->on('product_options.option_id', '=', 'product_option_variant_relation.option_id');
                })
                ->select($selectedColumns)
                ->first();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'Option variant relation details.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    /**
     * @param $data
     * @param $where
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function updateOVRWhere($data, $where)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
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
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function deleteOVRWhere($where)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
                if ($result) {
                    $returnData['code'] = 200;
                    $returnData['message'] = 'Variant Relation/s deleted.';
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

    /**
     * @param $data
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function insertOptVarRels($data)
    {
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)->insert($data);
                return json_encode(array('code' => 200, 'message' => 'Option variant relation/s added successfully.', 'data' => $result));
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
//                return $e->getMessage();
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
        }
    }

}
