<?php
namespace FlashSale\Http\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use \Exception;

/**
 * Campaigns model
 * Class Banners
 * @package FlashSale\Http\Modules\Admin\Models
 */
class Banners extends Model
{

    private static $_instance = null;

    protected $table = 'banners';
    protected $fillable = ['*'];


    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new banners();
        return self::$_instance;
    }

    public function addBanners($data)
    {
        try {
            $result = DB::table($this->table)->insertGetId($data);
            return json_encode(array('code' => 200, 'message' => 'Image added successfully.', 'data' => $result));
        } catch (\Exception $e) {
            return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
        }

    }

    public function udpateHomeBanner($update,$where)
    {

        try {
            $updatedResult = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($update);

        } catch (\Exception $e) {

            print_r($e);
        }

    }


}