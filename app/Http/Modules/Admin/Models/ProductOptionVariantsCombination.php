<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Class ProductOptionVariantCombination
 * @package FlashSale\Http\Modules\Admin\Models
 * @since 18-03-2016
 * @author Akash M. Pai <akashpai@globussoft.in>
 */
class ProductOptionVariantsCombination extends Model
{

    private static $_instance = null;

    protected $table = 'product_option_variants_combination';
    protected $fillable = ['product_id', 'variant_ids', 'quantity', 'exception_flag'];

    /**
     * Get instance/object of this class
     * @return ProductOptionVariantRelation|null
     * @since 29-02-2016
     * @author Dinanath Thakur <dinanaththakur@globussoft.in>
     */
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ProductOptionVariantsCombination();
        return self::$_instance;
    }

    /**
     * Add new option-variant combination data
     * @return string|int
     * @throws Exception
     * @since 18-03-2016, modified 06-01-2017,
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function addNewOptionVariantsCombination($data)
    {
        if (func_num_args() > 0) {
            try {
                $result = DB::table($this->table)->insert($data);
                return json_encode(array('code' => 200, 'message' => 'Option variant Combination/s added successfully.', 'data' => $result));
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
//                return $e->getMessage();
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
    public function getCombinationWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            $returnData['code'] = 200;
            $returnData['message'] = 'Combination data.';
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
    public function getAllCombinationsWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            //todo weird error, query working in phpmyadmin but not in laravel by any method start
            /*
            dd(DB::table($this->table)
                ->whereRaw("`product_option_variants_combination`.`product_id` = 35")
                ->leftjoin('product_option_variant_relation', function ($join) {
                    $join->on(DB::raw('(select REPLACE(SUBSTRING(SUBSTRING_INDEX(`product_option_variants_combination`.`variant_ids`, "_", "1"), LENGTH(SUBSTRING_INDEX(`product_option_variants_combination`.`variant_ids`, "_", 1-1)) + 1), "_", \',\'))'), 'LIKE', DB::raw('CONCAT("%",`product_option_variant_relation`.`variant_ids`,"%")'));
                    $join->where('product_option_variant_relation.product_id', '=', '35');
                })
                ->groupBy(DB::raw('(select `product_option_variants_combination`.`variant_ids`)'))
                ->select($selectedColumns)->toSql());
            */
            /*
            $result = DB::select('select product_option_variants_combination.*, REPLACE(GROUP_CONCAT(product_option_variant_relation.variant_data, ","), "],[", ",") as temp from `product_option_variants_combination` left join `product_option_variant_relation` on (select REPLACE(SUBSTRING(SUBSTRING_INDEX(product_option_variants_combination.variant_ids, "_", "1"), LENGTH(SUBSTRING_INDEX(product_option_variants_combination.variant_ids, "_", 1-1)) + 1), "_", \',\')) LIKE CONCAT("%",product_option_variant_relation.variant_ids,"%") and product_option_variant_relation.product_id = 35 where product_option_variants_combination.product_id = 35 GROUP BY (select product_option_variants_combination.variant_ids)');
            */
            /*
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('product_option_variant_relation', function ($join) {
                    $join->on(DB::raw('(select REPLACE(SUBSTRING(SUBSTRING_INDEX(`product_option_variants_combination`.`variant_ids`, "_", "1"), LENGTH(SUBSTRING_INDEX(`product_option_variants_combination`.`variant_ids`, "_", 1-1)) + 1), "_", \',\'))'), 'LIKE', DB::raw('CONCAT("%",`product_option_variant_relation`.`variant_ids`,"%")'));
                    $join->where('product_option_variant_relation.product_id', '=', '35');
                })
                ->groupBy(DB::raw('(select `product_option_variants_combination`.`variant_ids`)'))
                ->select($selectedColumns)
                ->get();
            */
            //weird error, query working in phpmyadmin but not in laravel by any method end

            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->leftjoin('product_option_variant_relation', function ($join) {
//                    $join->on(DB::raw('(select REPLACE(SUBSTRING(SUBSTRING_INDEX(product_option_variants_combination.variant_ids, "_", "1"), LENGTH(SUBSTRING_INDEX(product_option_variants_combination.variant_ids, "_", 1-1)) + 1), "_", \',\'))'), 'LIKE', DB::raw('CONCAT("%",product_option_variant_relation.variant_ids,"%")'));
//                })
//                ->leftjoin('product_option_variants', function ($join) {
//                    $join->on('variant_id', 'IN', DB::raw('(select REPLACE(SUBSTRING(SUBSTRING_INDEX(variant_ids, "_", "1"), LENGTH(SUBSTRING_INDEX(variant_ids, "_", 1-1)) + 1), "_", \',\'))'));
//                })
//                ->leftjoin('product_options', function ($join) {
//                    $join->on('product_option_variants.option_id', '=', 'product_options.option_id');
//                })
//                ->select(DB::raw('concat())')
                ->select($selectedColumns)
                ->get();
            $returnData['message'] = 'No combinations.';
            $returnData['code'] = 100;
            if (!empty($result)) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All combinations.';
                $returnData['data'] = $result;
            }
        }
        return json_encode($returnData);
    }

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function updateCombinationWhere()
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
    public function deleteCombinationWhere()
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
                    $returnData['message'] = 'Variant Combination/s deleted.';
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
