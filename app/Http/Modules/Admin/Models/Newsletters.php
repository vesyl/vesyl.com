<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Newsletters model
 * Class Newsletters
 * @package FlashSale\Http\Modules\Admin\Models
 */
class Newsletters extends Model
{

    private static $_instance = null;

    protected $table = 'newsletters';
    protected $fillable = ['sub_email', 'sub_date', 'sub_status'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Newsletters();
        return self::$_instance;
    }



    public function getSubscriberDetail($selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->select()
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

    public function updateNewsletterStatus($dataToUpdate, $where)
    {

        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }



}