<?php

namespace FlashSaleApi\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\QueryException;

class ProductTags extends Model

{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_tags';
    private static $_instance = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ptag_id', 'tag_name', 'added_by', 'added_date', 'tag_status', 'status_set_by'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new ProductTags();
        return self::$_instance;
    }

    public function getProductTagWhere()
    {

        if (func_num_args() > 0) {
            $tagId = func_get_arg(0);
            try {
                $result = DB::table("product_tags")
                    ->select()
                    ->whereIn('ptag_id', $tagId)
                    ->where('tag_status', 1)
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


}