<?php

namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductOptionVariants extends Model

{
    private static $_instance = null;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_option_variants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['variant_id', 'option_id', 'variant_name', 'price_modifier', 'price_modifier_type', 'weight_modifier', 'weight_modifier_type', 'added_by', 'updated_at', 'created_at', 'status'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ProductOptionVariants();
        return self::$_instance;
    }

    public function getOptionVariantsInfo($where, $selectedColumn = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->join('product_options', 'product_options.option_id', '=', 'product_option_variants.option_id')
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->get();
            return $result;
        } catch
        (QueryException $e) {
            echo $e;
        }
    }

    public function getOptionVariantDetailsForPopup($where, $selectedColumn = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->leftJoin('product_option_variant_relation as product_option_variant_relation', function ($join) use ($where) {
                    $join->on(DB::raw("(find_in_set(product_option_variants.variant_id, product_option_variant_relation.variant_ids)>0 AND product_option_variant_relation.product_id=" . $where['bindParams'][0] . ")"), DB::raw(''), DB::raw(''));
                })
                ->leftJoin('product_option_variants_combination as product_option_variants_combination', function ($join) use ($where) {
                    $join->on(DB::raw("(find_in_set(product_option_variants.variant_id, REPLACE(product_option_variants_combination.variant_ids,'_',','))>0 AND product_option_variant_relation.product_id=" . $where['bindParams'][0] . ")"), DB::raw(''), DB::raw(''));
                })
                ->leftJoin('product_images', 'product_images.for_combination_id', '=', 'product_option_variants_combination.combination_id')
                ->join('products', 'products.product_id', '=', 'product_option_variants_combination.product_id')
                ->leftjoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
//                ->leftJoin('users as users', function ($join) use ($where) {
//                    $join->on('users.id','=','');
//                })//DOUBT HERE
//                ->leftjoin('productmeta', 'productmeta.product_id', '=', 'products.product_id')
                ->select($selectedColumn)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->first();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }

    }
}