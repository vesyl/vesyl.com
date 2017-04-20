<?php

namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class Products extends Model

{
    private static $_instance = null;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'for_shop_id', 'product_name', 'product_description', 'category_id', 'brand_id', 'for_gender', 'for_age_group_id', 'delivery_price_type', 'delivery_price', 'estimated_deliver_time', 'added_date', 'added_by', 'product_status', 'status_set_by', 'material_ids', 'pattern_ids', 'available_countries', 'page_title', 'meta_description', 'meta_keywords'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Products();
        return self::$_instance;
    }

    public function getProductDetailsWhere()
    {
        if (func_num_args() > 0) {
            $productID = func_get_arg(0);
            try {
                $result = DB::table("products")
                    ->select('products.product_id', 'product_name', 'product_description', 'brand_id', 'for_gender', 'for_age_group_id', 'delivery_price_type', 'delivery_price', 'estimated_delivery_time', 'product_status', 'material_ids', 'pattern_ids', 'tag_ids', 'available_countries', 'shop_name', 'shop_id', 'category_name', 'discount_type', 'discount_value', 'available_from', 'available_upto', 'campaign_name', 'campaign_type', 'price', 'sale_price', 'productmeta.sizing_id', 'productmeta.color_id', 'product_sizing.sizing_name', 'product_colors.color_name')
                    ->where('products.product_id', $productID)
                    ->where('products.product_status', 1)
                    ->leftjoin('shops', 'products.for_shop_id', '=', 'shops.shop_id')
                    ->orWhere('shops.shop_status', 1)
                    ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')
                    ->leftJoin('Campaigns', function ($join) {
                        $join->on('products.product_id', '=', 'Campaigns.for_product_ids');
                    })
                    ->where('products.product_id', 'LIKE', '%' . $productID . '%')
                    ->leftJoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                    ->orderBy('productmeta.product_id', 'asc')
                    ->leftJoin('product_sizing', function ($join) {
                        $join->on('productmeta.sizing_id', '=', 'product_sizing.sizing_id')
                            ->where('product_sizing.sizing_status', '=', 1);
                    })
                    ->leftJoin('product_colors', function ($join) {
                        $join->on('productmeta.color_id', '=', 'product_colors.color_id')
                            ->where('product_colors.color_status', '=', 1);

                    })
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
     * @return int
     */

    public function getProductWhere()
    {

        if (func_num_args() > 0) {
            $productId = func_get_arg(0);
            try {
                $result = DB::table("products")
                    ->select()
                    ->whereIn('product_id', $productId)
                    ->where('product_status', 1)
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

    public function getProductDetailsByCategoryIds($where, $selectedColumn = ['*'], $whereForFilter, $sortClause, $limit = 20, $offset = 20, $wherePriceRange, $whereFilterVariant)
    {
        try {
            $result = DB::table($this->table)
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->join('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->leftJoin('product_option_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variant_relation.product_id');
                })
                ->leftJoin('product_option_variants_combination', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variants_combination.product_id');
                })
                ->leftjoin('product_options', 'product_options.option_id', '=', 'product_option_variant_relation.option_id')
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->whereRaw($whereForFilter['rawQuery'], isset($whereForFilter['bindParams']) ? $whereForFilter['bindParams'] : array())
                ->whereRaw($wherePriceRange['rawQuery'], isset($wherePriceRange['bindParams']) ? $wherePriceRange['bindParams'] : array())
//                ->whereRaw($whereFilterVariant['rawQuery'], isset($whereFilterVariant['bindParams']) ? $whereFilterVariant['bindParams'] : array())
                ->groupBy('product_option_variant_relation.product_id')
                ->skip($offset)
                ->take($limit)
                ->orderBy(key($sortClause), current($sortClause))
//                ->toSql();
                ->get();

            return $result;
        } catch
        (QueryException $e) {
            echo $e;
        }

    }

    public function getProductDetailsByCategoryIdsCount($where, $selectedColumn = ['*'], $wherePriceRange)
    {
        try {
            $result = DB::table($this->table)
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->join('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->leftJoin('product_option_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variant_relation.product_id');
                })
                ->leftJoin('product_option_variants_combination', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variants_combination.product_id');
                })
                ->join('product_options', 'product_options.option_id', '=', 'product_option_variant_relation.option_id')
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->whereRaw($wherePriceRange['rawQuery'], isset($wherePriceRange['bindParams']) ? $wherePriceRange['bindParams'] : array())
                ->groupBy('product_option_variant_relation.product_id')
                ->get();

            return $result;
        } catch
        (QueryException $e) {
            echo $e;
        }

    }

    public function getProductAndImages($where, $selectedColumn = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->leftJoin('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->leftJoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->leftJoin('product_option_variant_relation as product_option_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variant_relation.product_id');
                })
                ->leftJoin('product_option_variants_combination', 'product_images.for_combination_id', '=', 'product_option_variants_combination.combination_id')
                ->leftJoin('product_feature_variant_relation as product_feature_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_feature_variant_relation.product_id');
                })
                ->leftJoin('product_feature_variants as product_feature_variants', function ($join) {
                    $join->on('product_feature_variant_relation.feature_id', '=', 'product_feature_variants.feature_id');
                })
                ->leftJoin('product_features as product_features', function ($join) {
                    $join->on('product_feature_variants.feature_id', '=', 'product_features.feature_id');
                })
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->groupBy('product_feature_variants.feature_id')
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }

    }


    public function getProducts($where, $whereForCategoryFilter, $whereForFilter, $sortClause, $limit, $offset, $wherePriceRange, $selectedColumn, $whereFilterVariant)
    {

        try {
            $result = DB::table($this->table)
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->join('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->leftJoin('product_option_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variant_relation.product_id');
                })
                ->leftJoin('product_option_variants_combination', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variants_combination.product_id');
                })
                ->leftjoin('product_option_variants', 'product_option_variants.option_id', '=', 'product_option_variant_relation.option_id')
                ->leftjoin('product_options', 'product_options.option_id', '=', 'product_option_variant_relation.option_id')
                ->leftJoin('filter_product_relation', 'filter_product_relation.product_id', '=', 'products.product_id')
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->whereRaw($whereForFilter['rawQuery'], isset($whereForFilter['bindParams']) ? $whereForFilter['bindParams'] : array())
                ->whereRaw($whereForCategoryFilter['rawQuery'], isset($whereForCategoryFilter['bindParams']) ? $whereForCategoryFilter['bindParams'] : array())
                ->whereRaw($wherePriceRange['rawQuery'], isset($wherePriceRange['bindParams']) ? $wherePriceRange['bindParams'] : array())
                ->whereRaw($whereFilterVariant['rawQuery'], isset($whereFilterVariant['bindParams']) ? $whereFilterVariant['bindParams'] : array())
                ->groupBy('product_option_variant_relation.product_id')
                ->groupBy('product_images.for_product_id')
                ->skip($offset)
                ->take($limit)
                ->orderBy(key($sortClause), current($sortClause))
                ->get();
            return $result;
        } catch
        (QueryException $e) {
            echo $e;
        }


    }


    public function getRelatedProducts($where, $selectedColumn = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->join('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->leftJoin('product_option_variant_relation as product_option_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variant_relation.product_id');
                })
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->groupBy('product_option_variant_relation.product_id')
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }

    }


    public function getProductsCount($where, $whereForCategoryFilter, $whereForFilter, $sortClause,$wherePriceRange,$whereFilterVariant, $selectedColumn)
    {

        try {
            $result = DB::table($this->table)
                ->join('product_images', 'product_images.for_product_id', '=', 'products.product_id')
                ->join('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->leftJoin('product_option_variant_relation', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variant_relation.product_id');
                })
                ->leftJoin('product_option_variants_combination', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variants_combination.product_id');
                })
                ->join('product_option_variants', 'product_option_variants.option_id', '=', 'product_option_variant_relation.option_id')
                ->join('product_options', 'product_options.option_id', '=', 'product_option_variant_relation.option_id')
                ->leftJoin('filter_product_relation', 'filter_product_relation.product_id', '=', 'products.product_id')
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->whereRaw($whereForCategoryFilter['rawQuery'], isset($whereForCategoryFilter['bindParams']) ? $whereForCategoryFilter['bindParams'] : array())
                ->whereRaw($wherePriceRange['rawQuery'], isset($wherePriceRange['bindParams']) ? $wherePriceRange['bindParams'] : array())
                ->whereRaw($whereForFilter['rawQuery'], isset($whereForFilter['bindParams']) ? $whereForFilter['bindParams'] : array())
                ->whereRaw($whereFilterVariant['rawQuery'], isset($whereFilterVariant['bindParams']) ? $whereFilterVariant['bindParams'] : array())
                ->groupBy('product_option_variant_relation.product_id')
                ->groupBy('product_images.for_product_id')
                ->orderBy(key($sortClause), current($sortClause))
                ->get();
            return $result;
        } catch
        (QueryException $e) {
            echo $e;
        }


    }


    public function getCartProductDetailsForNonLoggedIn($where, $selectColumn)
    {

        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectColumn)
                ->join('product_option_variants_combination as product_option_variants_combination', function ($join) {
                    $join->on('products.product_id', '=', 'product_option_variants_combination.product_id');
                })
                ->join('product_images as product_images', function ($join) {
                    $join->on('product_option_variants_combination.combination_id', '=', 'product_images.for_combination_id');
                })
//                ->toSql();
                ->get();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }


}

