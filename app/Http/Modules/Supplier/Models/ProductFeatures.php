<?php

namespace FlashSale\Http\Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductFeatures extends Model
{

    private static $_instance = null;

    protected $table = 'product_features';
    protected $fillable = ['feature_name', 'shop_id', 'group_flag', 'feature_type', 'parent_id', 'full_description', 'display_on_product', 'display_on_catalog', 'status'];
    
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ProductFeatures();
        return self::$_instance;
    }

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function addFeature()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data, 'feature_id');
                return json_encode(array('code' => 200, 'message' => 'Feature added successfully.', 'data' => $result));
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


    public function getAllFeaturesWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try{
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
            } catch(Exception $e){
                $returnData['code'] = 400;
                $returnData['message'] = 'Something went wrong. PLease try again.';
                $returnData['data'] = null;
            }
            $returnData['code'] = 200;
            $returnData['message'] = 'All features.';
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    /**
     * @param string $where one or more where conditions
     * @return array of result matching the where condition or null
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function getFeatureWhere($where, $selectedColumns = ['*'])
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
                $returnData['message'] = 'All features.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    public function updateFeatureWhere()
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
                $returnData['message'] = 'Something went wrong. Please reload the page and try again.';
            }
        }
        return json_encode($returnData);
    }

    /**
     * @return string
     * @author Pradeep N G
     */
    public function getAllFGsWithFsWhere()
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftJoin('product_features as pf', function ($join) {
                    $join->on('pf.feature_id', '=', 'product_features.feature_id');
                })
                ->select(DB::raw('product_features.*,
                    GROUP_CONCAT(product_features.feature_id ORDER BY product_features.feature_id) AS feature_ids'))
                ->groupBy('product_features.feature_id')
                ->get();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All features.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);

    }

    public function getAllFeatureWhere()
    {

        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->get();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * @param $where
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllFeaturesWithVariantsWhere($where, $selectedColumns = ['*'], $productId = 0)
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftJoin('product_feature_variants', 'product_features.feature_id', '=', 'product_feature_variants.feature_id')
                ->leftJoin('product_feature_variant_relation', function($join) use ($productId) {
                    $join->on('product_features.feature_id', '=', 'product_feature_variant_relation.feature_id');
                    $join->where('product_feature_variant_relation.product_id', '=', $productId);
                })
                ->select(DB::raw('product_features.*,
                    GROUP_CONCAT(DISTINCT(product_feature_variants.variant_id) ORDER BY product_feature_variants.feature_id) AS variant_ids,
                    GROUP_CONCAT(DISTINCT(product_feature_variants.variant_name) ORDER BY product_feature_variants.feature_id) AS variant_names,
                    product_feature_variant_relation.feature_id AS feature_id_relations,
                    product_feature_variant_relation.variant_ids AS var_id_relations,
                    product_feature_variant_relation.display_status,
                    GROUP_CONCAT(DISTINCT(product_feature_variants.description) ORDER BY product_feature_variants.feature_id) AS variant_descriptions'))
                ->groupBy('product_features.feature_id')
                ->get();

            //EXTRA: MYSQL UNION TEST QUERY START--------------------
//                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->select(['feature_id'])
//                ->union(DB::table('product_feature_variants')->select(['feature_id'])->whereRaw("(SELECT feature_id from product_features WHERE feature_id = feature_id) = feature_id"))
////                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
////                ->union(DB::table('product_feature_variants')->select()->whereRaw('product_feature.feature_id = product_feature_variants.feature_id')->get())
//                ->get();
            //EXTRA: MYSQL UNION TEST QUERY END--------------------

            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All features.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);

    }

}
