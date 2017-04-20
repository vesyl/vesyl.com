<?php

namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param string $where one or more where conditions
     * @return array of all results matching the where condition or null
     * @author Akash M. Pai <akashpai@globussoft.com>
     */
    public function getAllFeaturesWhere($where, $selectedColumns = ['*'])
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
                $returnData['message'] = 'All features.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
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

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
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

    /*
     * SAMPLE FUNCTION FOR WHERE BEFORE USING WHERERAW
     */
    public function sampleFunctionOld()
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->where($where['column'], $where['condition'], $where['value'])
                ->get();
            $returnData['data'] = $result;
            $returnData['code'] = 200;
            $returnData['message'] = 'All features.';
        }
        return json_encode($returnData);
    }

    /*
     * MULTIPLE WHERE SAMPLE FUNCTION
     */
    public function sampleFunction()
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $instance = $this;
            $result = DB::table($this->table)
                ->whereNested(function ($query) use ($where, $instance) {
                    $instance->generateWhere($where, $query);
                })
                ->get();
            $returnData['data'] = $result;
            $returnData['code'] = 200;
            $returnData['message'] = 'All features.';
        }
        return json_encode($returnData);
    }

    private function generateWhere($where, $query)
    {
        if (isset($where) && !empty($where)) {
            if (isset($where['and']) && !empty($where['and'])) {
                foreach ($where['and'] as $keyWhereAnd => $valueWhereAnd) {
                    switch ($valueWhereAnd['condition']) {
                        case "":

                            break;

                        default:
                            $query->where($valueWhereAnd['column'], $valueWhereAnd['condition'], $valueWhereAnd['value']);
                            break;
                    }
                }
            }
            if (isset($where['or']) && !empty($where['or'])) {
                foreach ($where['or'] as $keyWhereOr => $valueWhereOr) {
                    switch ($valueWhereOr['condition']) {
                        case "":

                            break;

                        default:
                            $query->orWhere($valueWhereOr['column'], $valueWhereOr['condition'], $valueWhereOr['value']);
                            break;
                    }
                }
            }
            return $query;
        } else {
            return null;
        }
    }


    public function getProductFeatureWhere()
    {

        if (func_num_args() > 0) {

            $filtergroupname = func_get_arg(0);
            try {
                $result = DB::table("product_features")
//                ->where('available_from', '<',time()) //Need to modify the available date less than current date//
                    ->where('feature_name', $filtergroupname)
                    ->get();

            } catch (QueryException $e) {
                echo $e;
            }
            if ($result) {
                return $result;
            } else {
                return 0;
            }
        }

    }

    public function addProductfilterWhere()
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table('product_features')->insert($data);
                return $result;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
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


    public function getFeatureDetailsById()
    {

        if (func_num_args() > 0) {
            $featureId = func_get_arg(0);
            try {
                $result = DB::table("product_features")
                    ->where('feature_id', $featureId)
                    ->get();

            } catch (QueryException $e) {
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

    /**
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
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

    //TODELETE NOT USED
    /**
     * @param $where
     * @param array $selectedColumns
     * @return string
     * @author Akash M. Pai <akashpai@globussoft.in>
     */
    public function getAllFeaturesWithFVRelationWhere($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->leftJoin('product_feature_variant_relation as pfvr', function ($join) {
//                    $join->on('pfvr.feature_id', '=', 'product_features.feature_id');
//                })
//                ->leftJoin('product_feature_variants as pfv', function ($join) {
//                    $join->on('product_features.feature_id', '=', 'pfv.feature_id');
//                })
//                ->leftjoin('product_feature_variant_relation', 'product_feature_variant_relation.feature_id', '=', 'product_features.feature_id')
                ->leftJoin('product_feature_variants', 'product_features.feature_id', '=', 'product_feature_variants.feature_id')
//                ->leftjoin('product_feature_variant_relation as fvr', 'fvr.feature_id', '=', 'product_feature_variant_relation.feature_id')
                ->select(DB::raw('product_features.*,
                    GROUP_CONCAT(DISTINCT(product_feature_variants.variant_id) ORDER BY product_feature_variants.feature_id) AS all_variant_ids,
                    GROUP_CONCAT(DISTINCT(product_feature_variants.variant_name) ORDER BY product_feature_variants.feature_id) AS variant_names,
                    GROUP_CONCAT(product_feature_variants.description ORDER BY product_feature_variants.feature_id) AS variant_descriptions'))
                //,product_feature_variant_relation.variant_ids AS variant_ids'))//product_feature_variant_relation.variant_ids,
                ->groupBy('product_features.feature_id')
//                ->distinct('product_features.feature_id')
//                ->distinct('product_feature_variants.variant_id')
                ->get();
            $returnData['code'] = 200;
            $returnData['message'] = 'All features.';
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

}
