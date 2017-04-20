<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\QueryException;

Class Reviews extends Model
{
    private static $_instance = null;

    protected $table = 'reviews';
    protected $fillable = ['review_id', 'review_by', 'review_type', 'review_for', 'review_details', 'review_rating', 'review_status', 'review_status_setby'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Reviews();
        return self::$_instance;
    }

    public function getproductreviews($where, $selectedColumns = ['*'])
    {

        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->orderBy('review_id', 'desc')
//                ->toSql();
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

    public function getpendingDetails($where)
    {

        try {
            $result = Reviews::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', 'id', '=', 'reviews.review_by')
                ->join('products', 'product_id', '=', 'reviews.review_for')
//                ->join('users', 'id', '=', 'reviews.review_details')
                ->select(['review_id', 'users.username', 'products.product_name', 'review_rating', 'review_details', 'review_status'])
                ->get();

            return $result;

        } catch (\Exception $e) {
            return $e->getMessage();

        }

    }


    public function deleteUserDetails($where)

    {

        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }


}